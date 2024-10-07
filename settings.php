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
 * @copyright 2017 Arnaud Trouvé <moodle@arnaudtrouve.fr>
 * @copyright 2014 Danny Wahl www.iyWare.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_heading('filter_codepen/info',
                get_string('settingheading', 'filter_codepen'),
                get_string('settingheading_info', 'filter_codepen')));

    // Settings.
    $settings->add(new admin_setting_heading('filter_codepen/settings',
        get_string('settings'),
        ''));
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
    $settings->add(new admin_setting_configmulticheckbox('filter_codepen/defaulttab',
        get_string('settingdefaulttab', 'filter_codepen'),
        get_string('settingdefaulttab_desc', 'filter_codepen'),
        ['result' => 1],
        [
            'css' => get_string('settingdefaulttab_css', 'filter_codepen'),
            'html' => get_string('settingdefaulttab_html', 'filter_codepen'),
            'js' => get_string('settingdefaulttab_js', 'filter_codepen'),
            'result' => get_string('settingdefaulttab_result', 'filter_codepen')
        ]));

    // Theme override settings.
    $settings->add(new admin_setting_heading('filter_codepen/theme_override',
        get_string('theme_override', 'filter_codepen'),
        get_string('theme_override_desc', 'filter_codepen')));

    $settings->add(new admin_setting_configselect('filter_codepen/embedborder',
        get_string('settingembedborder', 'filter_codepen'),
        get_string('settingembedborder_desc', 'filter_codepen'),
        'none',
        [
            'none' => get_string('none'),
            'thin' => get_string('settingembedborder_thin', 'filter_codepen'),
            'thick' => get_string('settingembedborder_thick', 'filter_codepen')
        ]
    ));
    $settings->add(new admin_setting_configcolourpicker('filter_codepen/embedbordercolor',
        get_string('settingembedbordercolor', 'filter_codepen'),
        get_string('settingembedbordercolor_desc', 'filter_codepen'),
        ''
    ));
    $settings->add(new admin_setting_configcolourpicker('filter_codepen/embedtabbarcolor',
        get_string('settingembedtabbarcolor', 'filter_codepen'),
        get_string('settingembedtabbarcolor_desc', 'filter_codepen'),
        ''
    ));
    $settings->add(new admin_setting_configcolourpicker('filter_codepen/embedtablinkcolor',
        get_string('settingembedtablinkcolor', 'filter_codepen'),
        get_string('settingembedtablinkcolor_desc', 'filter_codepen'),
        ''
    ));
    $settings->add(new admin_setting_configcolourpicker('filter_codepen/embedactivetabcolor',
        get_string('settingembedactivetabcolor', 'filter_codepen'),
        get_string('settingembedactivetabcolor_desc', 'filter_codepen'),
        ''
    ));
    $settings->add(new admin_setting_configcolourpicker('filter_codepen/embedactivelinkcolor',
        get_string('settingembedactivelinkcolor', 'filter_codepen'),
        get_string('settingembedactivelinkcolor_desc', 'filter_codepen'),
        ''
    ));
    $settings->add(new admin_setting_configcolourpicker('filter_codepen/embedlinklogocolor',
        get_string('settingembedlinklogocolor', 'filter_codepen'),
        get_string('settingembedlinklogocolor_desc', 'filter_codepen'),
        ''
    ));

    $pen = 'http://codepen.io/thedannywahl/pen/Gbdaj';
    $filter = new \filter_codepen\text_filter(context_system::instance(), ['formats' => [FORMAT_HTML]]);
    $pen = $filter->filter($pen, ['originalformat' => FORMAT_HTML]);
    $settings->add(new admin_setting_heading('filter_codepen/preview',
                get_string('preview'),
                get_string('preview_desc', 'filter_codepen').$pen));
}
