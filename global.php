<?php
/* ERROR REPORTING
------------------------------------------------------- */
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

/* SESSION
------------------------------------------------------- */
session_start();
header('Cache-control: private'); // IE 6 FIX
header('Content-type: text/html; charset=utf-8');

/* LANGUAGE-SYSTEM
------------------------------------------------------- */
if (isset($_GET['lang'])) {
    $lang             = $_GET['lang'];
    $_SESSION['lang'] = $lang;
    setcookie('lang', $lang, time() + (3600 * 24 * 30));
} else if (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
} else if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'de';
}
switch ($lang) {
    case 'en':
        $lang_file = 'en.php';
        break;
    case 'de':
        $lang_file = 'de.php';
        break;
    default:
        $lang_file = 'de.php';
}
include_once 'lang/' . $lang_file;

/* SETTINGS
------------------------------------------------------- */

$GLOBALS['domain']     = 'http://ssbg.esy.es/';

$imgur_api    = 'xxxxxxxxxxxxxx';
$pastebin_api = 'xxxxxxxxxxxxxx';
$tmdb_api     = 'xxxxxxxxxxxxxx';


date_default_timezone_set("Europe/Berlin");
$timestamp = time();

/* TEMPLATE-SYSTEM
------------------------------------------------------- */
require_once 'includes/class.template.php';
$site_template = new Template('templates');

/* CSFR CHECK / PROTECTION
------------------------------------------------------- */
$csrf_protection_enable   = 1;
$csrf_protection_frontend = 1;
$csrf_protection_backend  = 0;
$csrf_protection_expires  = 7200;
$csrf_protection_name     = '__csrf';
$csrf_protection_xhtml    = 1;
require_once 'includes/csrf_utils.php';

/* BACKEND MODULE
------------------------------------------------------- */
require_once 'includes/class.upload.php';
require_once 'includes/functions.php';
require_once 'includes/upltemplate_parser.php';

/* LADE TEMPLATE: NAVIGATION
------------------------------------------------------- */
$site_template->register_vars(array(
    "lang_friends_and_partner" => $lang['friends_and_partner'],
    "lang_support"             => $lang['support'],
    "lang_donate_support"      => $lang['donate_support'],
    "lang_games"               => $lang['games'],
    "lang_music"               => $lang['music'],
    "lang_simple"              => $lang['simple'],
    "lang_movie"               => $lang['movie'],
    "lang_software"            => $lang['software'],
    "lang_porn"                => $lang['porn'],
    "lang_moviexrel"           => $lang['moviexrel'],
));
$load_navigation = $site_template->parse_template("navigation");

/* LADE TEMPLATE: DONATE
------------------------------------------------------- */
$site_template->register_vars(array(
    "lang_donate" => $lang['donate'],

));
$load_donate_modal = $site_template->parse_template("donate");
