<?php

/* BACKEND MODULE LADEN
------------------------------------------------------- */
require_once 'global.php';

/* LADE TEMPLATE
------------------------------------------------------- */
$site_template->register_vars(array(
    "navigation"    => $load_navigation,
    "copyright"     => "(c) 2016-" . date("Y", $timestamp) . " by Uranjtsu.xyz",
    "lang_aboutme"  => $lang['start_about_me'],
    "lang_min_info" => $lang['start_min_info'],
    "lang_news"     => $lang['news'],
    "lang_follow"   => $lang['follow'],
    "lang_feedback" => $lang['feedback'],
    "website_title" => $lang['website_title_start'],
    "donate_modal"  => $load_donate_modal,
));
$site_template->print_template($site_template->parse_template("startseite"));
