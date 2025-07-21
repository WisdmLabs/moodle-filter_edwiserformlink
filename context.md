# Project Context: edwiserformlink

Generated: 2025-07-18T06:40:39.603Z

## Directory Structure

```
├── 📁 .cursor/
│   ├── 📁 rules/
│   │   └── 📄 mdc-rules-organization.md
├── 📁 classes/
│   ├── 📄 CommonFilterTrait.php
│   └── 📄 text_filter.php
├── 📁 lang/
│   ├── 📁 en/
│   │   └── 📄 filter_edwiserformlink.php
├── 📄 README.md
├── 📄 filter.php
└── 📄 version.php
```

## File Contents

### `./classes/CommonFilterTrait.php`

```php
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
            $sitekey = get_config('local_edwiserform', 'google_recaptcha_sitekey');
            if (trim($sitekey) == '') {
                $sitekey = 'null';
            }
            $PAGE->requires->js_call_amd('local_edwiserform/render_form', 'init', array($sitekey));
            $PAGE->requires->data_for_js('sitekey', $sitekey);
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

```

### `./classes/text_filter.php`

```php
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
 * Class text_filter
 *
 * @package    filter_edwiserformlink
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class text_filter extends \moodle_text_filter {
    use CommonFilterTrait;
}

```

### `./.cursor/rules/mdc-rules-organization.md`

```markdown
---
description: Instructs Cursor to always create new MDC rules in .cursor/rules as separate files.
globs: []
alwaysApply: true
---

# MDC Rules Organization

## Rule Creation Guidelines

When creating new MDC (Model Context) rules for this project:

1. **Location**: Always create new MDC rules in the `.cursor/rules/` folder
2. **File Structure**: Each rule should be a separate file with a descriptive name
3. **Naming Convention**: Use kebab-case for file names (e.g., `api-documentation.md`, `code-review-standards.md`)
4. **File Extension**: Use `.md` extension for all rule files
5. **Content Format**: Write rules in clear, actionable language with specific instructions

## File Organization

- Keep related rules together but separate
- Use descriptive file names that clearly indicate the rule's purpose
- Avoid creating monolithic rule files - prefer multiple focused files
- Consider grouping related rules in subdirectories if the rules folder grows large

## Example Rule Structure

```markdown
# Rule Name

## Purpose
Brief description of what this rule accomplishes

## Instructions
Specific, actionable instructions for the AI

## Examples
Concrete examples of how to apply the rule

## Exceptions
Any cases where this rule doesn't apply (if applicable)
```

## Maintenance

- Review and update rules regularly
- Remove obsolete rules
- Consolidate overlapping rules when appropriate
- Keep rules focused and specific to avoid conflicts 
```

### `./filter.php`

```php
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

/**
 * This filter provides automatic linking to
 * Form builder entries, aliases and categories when
 * found inside every Moodle text.
 *
 * @package     filter_edwiserformlink
 * @copyright   2018 WisdmLabs <support@wisdmlabs.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author      Yogesh Shirsath
 */

use filter_edwiserformlink\CommonFilterTrait;

if ($CFG->branch > '404') { // Moodle 4.5 and newer
    class_alias('\filter_edwiserpbf\text_filter', 'filter_edwiserpbf');
} else {
    class filter_edwiserformlink extends moodle_text_filter {
        use CommonFilterTrait;
    }
}

```

### `./lang/en/filter_edwiserformlink.php`

```php
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

/**
 * This filter provides automatic linking to
 * Form builder entries, aliases and categories when
 * found inside every Moodle text.
 *
 * @package     filter_edwiserformlink
 * @copyright   2018 WisdmLabs <support@wisdmlabs.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author      Yogesh Shirsath
 */

defined('MOODLE_INTERNAL') || die();

$string['filtername'] = "Edwiser Forms Linker";
$string['pluginname'] = "Edwiser Forms Link";

```

### `./README.md`

```markdown
This filter is required for the Edwiser Forms Free plugin. 

Edwiser Forms is your go-to Forms solution for your Moodle. 
With Edwiser Forms, you can create multipurpose forms in Moodle, without knowing a single line of code.


STEPS FOR INSTALLATION
1 - Install Edwiser Forms Free Plugin;
2 - Install (this filter plugin) Edwiser Forms Embedder Plugin;

Follow below mentioned guide for instructions.

Installation Guide & Documentation
==================================
You can view the documentation by following this link https://docs.google.com/document/d/1DfNx1x6en2tAwExv7oCCBbpLbjHP9qcUCedbPe_wlAc/edit?usp=sharing

Version :
v1.0.0 - Plugin Released
v1.0.1 - Bug Fixes


Plugin Description
==================

*Edwiser Forms comes packed with the following features:
*Drag & Drop Form Builder(Pro Feature)
*Ready to use Form Templates
*Conditional Logic
*Multi-page Forms(Pro Feature)
*Responsive Mobile Layouts
*Instant Notifications
*Entry Management
*Easy to Embed
*Translation Ready
*Spam Protection
*File Uploads(Pro Feature)
*20+ Ready to Use Form Fields(Pro Feature)


-Responsive Mobile Layouts: Edwiser Forms is completely responsive and can be displayed seamlessly across devices of varying widths or layouts. Users can customize the width of form fields as well. 

-Easy to Embed: Using shortcodes, you can embed the form anywhere you want, on your Moodle website, using HTML blocks. 

-Drag & Drop Form Builder(Pro Feature): The drag & drop form builder further simplifies your form creation experience. You can just drag & drop form elements wherever you like. 

-Multi-page Forms(Pro Feature): In case of lengthy forms, you can use the Multipage Form feature available in the plugin, to split the forms into separate sections. For example, having two sections for personal and education details of the user.

-Conditional Logic: In addition to the above, you can also create forms with an input-dependent workflow, thanks to Conditional Logic in Edwiser Forms.

-Ready to use Form Templates: The Readymade Form templates like Simple Support Form, User Registration Form, Survey Form etc. can prove to be a real time-saver. You can either start from scratch or choose a template based on your form’s needs and build further. 

-Entry Management: A robust Entry Management makes it easier for you to analyze, download, or even export this collected data in form of .csv files. 

-Translation Ready: The labels and form fields can be translated to any language, and can also be updated based on the language being used on the Moodle website.

-Spam Protection: A dependable Spam protection in place ensures the entries you receive are 100% genuine and correct. 

-Instant Notifications: Get notified every time a user fills up your form. You can customize the notification mail the way you need.

-File Uploads(Pro Feature): For applications where your users might have to upload documents, Edwiser Forms lets you upload PDFs, Word Docs, Text, Audio, Video and a lot more.

You can also add custom colors and styling, decide the width of the form, and a lot more. All of this, without needing the help of any developer. 

Edwiser Forms also has a Pro version: Edwiser Forms Pro, with additional premium features. Try Out Edwiser Forms Pro Now! - http://bit.ly/2EnkvCF


```

### `./version.php`

```php
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

/**
 * This filter provides automatic linking to
 * Form builder entries, aliases and categories when
 * found inside every Moodle text.
 *
 * @package     filter_edwiserformlink
 * @copyright   2018 WisdmLabs <support@wisdmlabs.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author      Yogesh Shirsath
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version  = 2025021300;
$plugin->requires = 2016052314;  // Requires this Moodle version.
$plugin->release  = '2.0.3';
$plugin->maturity = MATURITY_STABLE;
$plugin->component= 'filter_edwiserformlink';

// $plugin->dependencies = array('local_edwiserform' => 2017110800);

```

