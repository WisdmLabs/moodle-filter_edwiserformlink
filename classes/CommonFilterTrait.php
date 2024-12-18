<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace filter_edwiserformlink;

/**
 * Class CommonFilterTrait
 *
 * @package    filter_edwiserformlink
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait CommonFilterTrait {
    /**
     * Filter tags and convert to object
     * @param  array $tags Tags array
     * @return array       Forms array
     */
    private function filter_tags($tags) {
        $forms = [];
        for ($i = 0; $i < count($tags[0]); $i++) {
            $form = new \stdClass;
            $form->tag = $tags[0][$i];
            $form->id = $tags[1][$i];
            $forms[] = $form;
        }
        return $forms;
    }

    /**
     * Function filter edwiser form tags in content
     *
     * @param  string $text    HTML content to process
     * @param  array  $options options passed to the filters
     * @return string
     */
    public function filter($text, array $options = array()) {
        global $PAGE, $CFG;
        preg_match_all(
            "(\[edwiser\-form[ ]+id\=[\'\"’‘“”]([0-9]+)[\'\"’‘“”]\])",
            $text,
            $tags
        );
        if ($tags[0]) {
            $css = '';
            if (!isset($CFG->formsloaded)) {
                $themedependentcss = '/local/edwiserform/style/common_' . $PAGE->theme->name . '.css';
                if (file_exists($CFG->dirroot . $themedependentcss)) {
                    $css = '<link rel="stylesheet" type="text/css" href="' . $CFG->wwwroot . $themedependentcss . '">';
                }
                $CFG->formsloaded = true;
            }
            $stringmanager = get_string_manager();
            $strings = $stringmanager->load_component_strings('local_edwiserform', 'en');
            $PAGE->requires->strings_for_js(array_keys($strings), 'local_edwiserform');
            $PAGE->requires->js(new \moodle_url('https://www.google.com/recaptcha/api.js'));
            $PAGE->requires->js_call_amd('local_edwiserform/render_form', 'init');
            $tags = $this->filter_tags($tags);
            foreach ($tags as $form) {
                $container = "<div class='edwiserform-root-container'>
                    " . $css . "
                    <div class='edwiserform-wrap-container'>
                    <input type='hidden' class='id' value='" . $form->id . "'>
                    <form class='edwiserform-container' action='' method='post'></form>
                    </div>
                </div>";
                $text = str_replace($form->tag, $container, $text);
            }
        }
        return $text;
    }
}
