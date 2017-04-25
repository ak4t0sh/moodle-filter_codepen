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

require_once(__DIR__ . '/filter.php');
if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_heading('filter_codepen/info',
                get_string('settingheading', 'filter_codepen'),
                get_string('settingheading_info', 'filter_codepen')));

    $settings->add(new admin_setting_configmulticheckbox('filter_codepen/formats',
            get_string('settingformats', 'filter_codepen'),
            get_string('settingformats_desc', 'filter_codepen'),
            [FORMAT_HTML => 1, FORMAT_MARKDOWN => 1, FORMAT_MOODLE => 1], format_text_menu()));

    $settings->add(new admin_setting_configtext('filter_codepen/height',
                get_string('settingheight', 'filter_codepen'),
                get_string('settingheight_desc', 'filter_codepen'),
                '268',
                PARAM_INT,
                3));
    $settings->add(new admin_setting_configselect('filter_codepen/embedversion',
        get_string('settingembedversion', 'filter_codepen'),
        get_string('settingembedversion_desc', 'filter_codepen'),
        2, [1 => 1, 2 => 2]));

    $settings->add(new admin_setting_configselect('filter_codepen/embedtheme',
        get_string('settingembedtheme', 'filter_codepen'),
        get_string('settingembedtheme_desc', 'filter_codepen'),
        'dark',
        [
            'dark' => get_string('settingembedtheme_dark', 'filter_codepen'),
            'light' => get_string('settingembedtheme_light', 'filter_codepen')
        ]
    ));

    $pen = 'http://codepen.io/thedannywahl/pen/Gbdaj';
    $filter = new filter_codepen(context_system::instance(), ['formats' => [FORMAT_HTML]]);
    $pen = $filter->filter($pen, ['originalformat' => FORMAT_HTML]);
    $settings->add(new admin_setting_heading('filter_codepen/preview',
                get_string('preview'),
                $pen));
}
