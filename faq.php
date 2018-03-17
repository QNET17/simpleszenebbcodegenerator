<?php
/* BACKEND MODULE LADEN
------------------------------------------------------- */
require_once 'global.php';

/* GENERATOR SETTINGS
------------------------------------------------------- */
$settings['generator_name'] = $lang['faq_title'];
$settings['color_sheme']    = 'faq';
$settings['wrapper_size']   = 'wrap-1';
$settings['head_icon']      = 'fa-info-circle';

$head_insert = '
<script type="text/javascript">
function popitup(url) {
    newwindow=window.open(url,\'name\',\'height=900,width=800\');
    if (window.focus) {newwindow.focus()}
    return false;
}
</script>
<style>
    .faqHeader {
        font-size: 27px;
        margin: 20px;
    }

    .panel-heading [data-toggle="collapse"]:after {
        font-family: \'Glyphicons Halflings\';
        content: "\e072"; /* "play" icon */
        float: right;
        color: #F58723;
        font-size: 18px;
        line-height: 22px;
        /* rotate "play" icon from > (right arrow) to down arrow */
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        transform: rotate(-90deg);
    }

    .panel-heading [data-toggle="collapse"].collapsed:after {
        /* rotate "play" icon from > (right arrow) to ^ (up arrow) */
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        transform: rotate(90deg);
        color: #454444;
    }
#language_icons {
    position: absolute;
    z-index: 1;
    left: 810px;
    top: -1px;
    width: 47px;
}

#donate_button {
    position: absolute;
    z-index: 1;
    left: 780px;
    top: 28px;
}

#twitter_button {
    position: absolute;
    z-index: 1;
    left: 715px;
    top: 28px;
}
</style>
';

$site_template->register_vars(array(
    "color_sheme"                 => $settings['color_sheme'],
    "navigation"                  => $load_navigation,
    "head_include"                => $head_insert,
    "wrapper"                     => $settings['wrapper_size'],
    "lang_generator_name"         => $settings['generator_name'],
    "website_title"               => $lang['website_title_faq'],
    "lang_follow"                 => $lang['follow'],
    "lang_general"                => $lang['general'],
    "lang_text_color"             => $lang['text_color'],
    "lang_donate"                 => $lang['donate'],
    "head_icon"                   => $settings['head_icon'],
    "lang_upload_template_info"   => $lang['faq_upload_template_info'],
    "lang_upload_template_title"  => $lang['faq_upload_template_title'],
    "lang_faq_title_music"        => $lang['music'],
    "lang_faq_title_game"         => $lang['games'],
    "lang_faq_title_software"     => $lang['software'],
    "lang_faq_title_movie"        => $lang['faq_tab_movie'],
    "lang_faq_title_movie_xrel"   => $lang['faq_tab_movie_xrel'],
    "lang_faq_title_porn"         => $lang['porn'],
    "lang_faq_title_simple"       => $lang['simple'],
    "lang_utemp_title"            => $lang['faq_utemp_title'],
    "lang_utemp_if_title2"        => $lang['faq_utemp_if_title2'],
    "lang_utemp_status"           => $lang['faq_utemp_status'],
    "lang_utemp_cover"            => $lang['faq_utemp_cover'],
    "lang_utemp_if_genre"         => $lang['faq_utemp_if_genre'],
    "lang_utemp_quality"          => $lang['faq_utemp_quality'],
    "lang_utemp_size_unit"        => $lang['faq_utemp_size_unit'],
    "lang_utemp_if_archive"       => $lang['faq_utemp_if_archive'],
    "lang_utemp_format"           => $lang['faq_utemp_format'],
    "lang_utemp_hashcode"         => $lang['faq_utemp_hashcode'],
    "lang_utemp_tracklist"        => $lang['faq_utemp_tracklist'],
    "lang_utemp_dlink"            => $lang['faq_utemp_dlink'],
    "lang_utemp_cnlink"           => $lang['faq_utemp_cnlink'],
    "lang_utemp_if_update"        => $lang['faq_utemp_if_update'],
    "lang_utemp_update_mini"      => $lang['faq_utemp_update_mini'],
    "lang_utemp_if_crack"         => $lang['faq_utemp_if_crack'],
    "lang_utemp_language"         => $lang['faq_utemp_language'],
    "lang_utemp_format"           => $lang['faq_utemp_format'],
    "lang_utemp_description_game" => $lang['faq_utemp_description_game'],
    "lang_utemp_nfo"              => $lang['faq_utemp_nfo'],
    "lang_utemp_if_zinfo"         => $lang['faq_utemp_if_zinfo'],
    "lang_utemp_description"      => $lang['faq_utemp_description'],
    "lang_utemp_lifetime"         => $lang['faq_utemp_liefetime'],
    "lang_utemp_astream"          => $lang['faq_utemp_astream'],
    "lang_utemp_vstream"          => $lang['faq_utemp_vstream'],
    "lang_utemp_vcodec"           => $lang['faq_utemp_vcodec'],
    "lang_utemp_genre"            => $lang['faq_utemp_genre'],
    "lang_utemp_released"         => $lang['faq_utemp_released'],
    "lang_utemp_year"             => $lang['faq_utemp_year'],
    "lang_utemp_if_archive"       => $lang['faq_utemp_if_archive'],
    "lang_utemp_description_porn" => $lang['faq_utemp_description_porn'],
    "lang_utemp_version"          => $lang['faq_utemp_version'],
    "lang_utemp_if_build"         => $lang['faq_utemp_if_build'],
    "lang_utemp_color"            => $lang['faq_utemp_color'],
    "lang_description"            => $lang['description'],
    "lang_placeholder"            => $lang['placeholder'],
    "lang_faq_title_cookie"       => $lang['faq_cookies_title'],
    "lang_faq_cookie_info"        => $lang['faq_cookies_info'],
    "lang_faq_api_title"          => $lang['faq_api_login_title'],
    "lang_faq_api_login_info"     => $lang['faq_api_login'],
    "lang_text_color_info"        => $lang['faq_text_color_info'],
    "lang_utemp_mediainfo"        => $lang['faq_utemp_mediainfo'],
    "lang_nydus_fix"              => $lang['nydus_fix'],
    "lang_nydus_fix_info"         => $lang['nydus_fix_info'],
));
$site_template->print_template($site_template->parse_template("faq"));
