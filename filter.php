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
 * plugin details
 *
 * @package filter
 * @subpackage codepen
 * @copyright 2017 Arnaud Trouvé <ak4t0sh@free.fr>
 * @copyright 2014 Danny Wahl www.iyWare.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class filter_codepen extends moodle_text_filter {

    /**
     * @var array global configuration for this filter
     *
     * This might be eventually moved into parent class if we found it
     * useful for other filters, too.
     */
    protected static $globalconfig;

    /**
     * Apply the filter to the text
     *
     * @see filter_manager::apply_filter_chain()
     * @param string $text to be processed by the text
     * @param array $options filter options
     * @return string text after processing
     */
    public function filter($text, array $options = []) {
        if (!isset($options['originalformat'])) {
            // If the format is not specified, we are probably called by {@see format_string()}
            // in that case, it would be dangerous to replace URL with the pen because it could be stripped.
            // Therefore, we do nothing.
            return $text;
        }
        if (!isset($this->localconfig['formats'])) {
            $this->localconfig['formats'] = explode(',', $this->get_global_config('formats'));
        }
        if (in_array($options['originalformat'], $this->localconfig['formats'])) {
            $this->convert_urls_into_codepens($text);
        }
        return $text;
    }

    /**
     * Returns the global filter setting
     *
     * If the $name is provided, returns single value. Otherwise returns all
     * global settings in object. Returns null if the named setting is not
     * found.
     *
     * @param mixed $name optional config variable name, defaults to null for all
     * @return string|object|null
     */
    protected function get_global_config($name=null) {
        $this->load_global_config();
        if (is_null($name)) {
            return self::$globalconfig;

        } else if (array_key_exists($name, self::$globalconfig)) {
            return self::$globalconfig->{$name};

        } else {
            return null;
        }
    }

    /**
     * Makes sure that the global config is loaded in $this->globalconfig
     *
     * @return void
     */
    protected function load_global_config() {
        if (is_null(self::$globalconfig)) {
            self::$globalconfig = get_config('filter_codepen');
        }
    }

    /**
     * Given some text this function converts any URLs it finds into embedded codepens.
     *
     * @param string $text Passed in by reference. The string to be searched for urls.
     */
    protected function convert_urls_into_codepens(&$text) {
        // I've added img tags to this list of tags to ignore.
        // See MDL-21168 for more info. A better way to ignore tags whether or not
        // they are escaped partially or completely would be desirable. For example:
        // <a href="blah">
        // &lt;a href="blah"&gt;
        // &lt;a href="blah">
        $filterignoretagsopen  = ['<a\s[^>]+?>'];
        $filterignoretagsclose = ['</a>'];
        filter_save_ignore_tags($text, $filterignoretagsopen, $filterignoretagsclose, $ignoretags);

        static $unicoderegexp;
        if (!isset($unicoderegexp)) {
            $unicoderegexp = @preg_match('/\pL/u', 'a'); // This will fail silently, returning false.
        }

        $regex = '((https?://)?)(codepen.io\/)([a-zA-Z0-9]+)(\/pen\/)([a-zA-Z0-9]+)';

        if ($unicoderegexp) {
            $regex = '#' . $regex . '#ui';
        } else {
            $regex = '#' . preg_replace(['\pLl', '\PL'], 'a-z', $regex) . '#i';
        }

        // Get the height from the settings page.
        $height = get_config('filter_codepen', 'height');

        // Make sure that the value is set or set the default.
        if (($height === 0) || (empty($height))) {
            $height = 268;
        }
        $embedversion = get_config('filter_codepen', 'embedversion');
        $embedtheme = get_config('filter_codepen', 'embedtheme');
        $defaulttabs = get_config('filter_codepen', 'defaulttab');

        // Theme override settings.
        $embedborder = get_config('filter_codepen', 'embedborder');
        if ($embedborder != "none") {
            $embedborder = 'data-border="' . $embedborder . '" ';
        } else {
            $embedborder = '';
        }
        $embedbordercolor = get_config('filter_codepen', 'embedbordercolor');
        if (!empty($embedborder) && !empty($embedbordercolor)) {
            $embedbordercolor = 'data-border-color="' . $embedbordercolor . '" ';
        } else {
            $embedbordercolor = '';
        }
        $embedtabbarcolor = get_config('filter_codepen', 'embedtabbarcolor');
        if (!empty($embedtabbarcolor)) {
            $embedtabbarcolor = 'data-tab-bar-color="' . $embedtabbarcolor . '" ';
        }
        $embedtablinkcolor = get_config('filter_codepen', 'embedtablinkcolor');
        if (!empty($embedtablinkcolor)) {
            $embedtablinkcolor = 'data-tab-link-color="' . $embedtablinkcolor . '" ';
        }
        $embedactivetabcolor = get_config('filter_codepen', 'embedactivetabcolor');
        if (!empty($embedactivetabcolor)) {
            $embedactivetabcolor = 'data-active-tab-color="' . $embedactivetabcolor . '" ';
        }
        $embedactivelinkcolor = get_config('filter_codepen', 'embedactivelinkcolor');
        if (!empty($embedactivelinkcolor)) {
            $embedactivelinkcolor = 'data-active-link-color="' . $embedactivelinkcolor . '" ';
        }
        $embedlinklogocolor = get_config('filter_codepen', 'embedlinklogocolor');
        if (!empty($embedlinklogocolor)) {
            $embedlinklogocolor = 'data-link-logo-color="' . $embedlinklogocolor . '" ';
        }

        $text = preg_replace($regex, '<p data-embed-version="' . $embedversion .
            '" data-default-tab="' . $defaulttabs .
            '" data-height="' . $height .
            '" data-theme-id="' . $embedtheme . '" '
            . $embedborder
            . $embedbordercolor
            . $embedtabbarcolor
            . $embedtablinkcolor
            . $embedactivetabcolor
            . $embedactivelinkcolor
            . $embedlinklogocolor . '
             data-slug-hash="$6" data-user="$4" class="codepen">
            See the pen <a href="$0">$0</a> by (<a href="https://$3$4">@$4</a>) on <a href="https://$3">CodePen</a></p>
<script async src="//codepen.io/assets/embed/ei.js"></script>', $text);

        if (!empty($ignoretags)) {
            $ignoretags = array_reverse($ignoretags); // Reversed so "progressive" str_replace() will solve some nesting problems.
            $text = str_replace(array_keys($ignoretags), $ignoretags, $text);
        }
    }
}
