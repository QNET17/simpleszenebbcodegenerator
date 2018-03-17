<?php

/* BACKEND MODULE LADEN
------------------------------------------------------- */
require_once 'global.php';

/* GENERATOR SETTINGS
------------------------------------------------------- */
$settings['generator_name'] = $lang['generator_porn_name'];
$settings['color_sheme']    = 'porn';
$settings['wrapper_size']   = 'wrap-2';
$settings['head_icon']      = 'fa-transgender-alt';

/* CSFR CHECK
------------------------------------------------------- */

if ($csrf_protection_enable && $csrf_protection_frontend) {
    csrf_start(true);
}

/* GENERIERTE CODESEITE
------------------------------------------------------- */
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {

    /* FORMULARDATEN SAMMELN
    ------------------------------------------------------- */
    $random                 = random_hashcode('25'); /* einmaliger unique string */
    $input                  = array();
    $input['hashcode']      = $random;
    $input['title']         = (!empty($_POST['title']) ? $_POST['title'] : '');
    $input['subtitle']      = (!empty($_POST['untertitel']) ? clean_input($_POST['untertitel']) : '');
    $input['passw']         = (!empty($_POST['password']) ? clean_input($_POST['password']) : 'Kein Passwort');
    $input['archive']       = (!empty($_POST['archive']) ? clean_input($_POST['archive']) : '');
    $input['zinfo']         = (!empty($_POST['zinfo']) ? clean_input($_POST['zinfo']) : '');
    $input['format']        = (!empty($_POST['format']) ? clean_input($_POST['format']) : '');
    $input['size']          = (!empty($_POST['size']) ? clean_input($_POST['size']) : '');
    $input['size_unit']     = (!empty($_POST['units']) ? clean_input($_POST['units']) : '');
    $input['ochlinks']      = (!empty($_POST['ochlinks']) ? clean_input($_POST['ochlinks']) : '');
    $input['genre']         = (!empty($_POST['genre']) ? clean_input($_POST['genre']) : '');
    $input['description']   = (!empty($_POST['description']) ? clean_input($_POST['description']) : '');
    $input['containername'] = (!empty($_POST['containername']) ? clean_input($_POST['title']) : $random);
    $input['cnlink']        = (!empty($_POST['cnl']) ? '[URL="' . cnl(clean_input($_POST['ochlinks'])) . '"]Click\'n\'Load[/URL]' : '');
    $input['text1']         = (!empty($_POST['text1']) ? '[COLOR="' . $_POST['text1'] . '"]' : '');
    $input['text2']         = (!empty($_POST['text2']) ? '[COLOR="' . $_POST['text2'] . '"]' : '');

    /* REMOTE / UPLOAD - COVER
    ------------------------------------------------------- */
    $if_watermark = (!empty($_POST['watermark']) ? $_POST['watermark'] : '');
    if (isset($_POST['remotecover']) && !empty($_POST['remotecover'])) {
        copy($_POST['remotecover'], "tmp/" . $random . "-TEMP-.png");
        $input['cover'] = uploadRemoteCover($random, "tmp/" . $random . "-TEMP-.png", $_POST['covertextunder'], $if_watermark, $_POST['imghost']);
    } elseif (isset($_FILES['uploadcover']) && !empty($_FILES['uploadcover'])) {
        $input['cover'] = uploadCover("uploadcover", $random, $_POST['covertextunder'], $if_watermark, $_POST['imghost']);
    } else {
        $input['cover'] = ''; /* use fallbackcover, defined in function bbCode */
    }

    /* CRYPTER DEFINIEREN
    ------------------------------------------------------- */
    if (isset($_POST['fc_api']) && !empty($_POST['fc_api'])) {
        $fcData              = filecryptCC($input['ochlinks'], clean_input($_POST['fc_api']), $input['containername']);
        $input['fc_link']    = '[URL="' . $fcData[0] . '"]' . $lang['download'] . '[/URL]';
        $input['fc_img']     = $fcData[1];
        $input['fc_control'] = $_POST['fc_api'];
    }

    if (isset($_POST['sl_api']) && !empty($_POST['sl_api'])) {
        $slData              = shareLinksBIZ($input['ochlinks'], clean_input($_POST['sl_api']), $input['containername'], clean_input($_POST['sl_username']), trim($_POST['sl_password']));
        $input['sl_link']    = '[URL="' . $slData[0] . '"]' . $lang['download'] . '[/URL]';
        $input['sl_img']     = $slData[1];
        $input['sl_control'] = $_POST['sl_api'];
    }

    if (isset($_POST['rl_api']) && !empty($_POST['rl_api'])) {
        $rlData              = reLink($input['ochlinks'], clean_input($_POST['rl_api']), $input['containername']);
        $input['rl_link']    = '[URL="' . $rlData[0] . '"]' . $lang['download'] . '[/URL]';
        $input['rl_img']     = $rlData[1];
        $input['rl_control'] = $_POST['rl_api'];
    }

    if (isset($_POST['nc_api']) && !empty($_POST['nc_api'])) {
        $ncData              = nCryptIN($input['ochlinks'], clean_input($_POST['nc_api']), $input['containername']);
        $input['nc_link']    = '[URL="' . $ncData[0] . '"]' . $lang['download'] . '[/URL]';
        $input['nc_img']     = $ncData[1];
        $input['nc_control'] = $_POST['nc_api'];
    }

    /* COOKIE SETZEN - DAUER 30 TAGE
    ------------------------------------------------------- */
    setcookie('ssbg_sl_username', clean_input($_POST['sl_username']), time() + (86400 * 30), "/");
    setcookie('ssbg_sl_password', clean_input($_POST['sl_password']), time() + (86400 * 30), "/");
    setcookie('ssbg_sl_api', clean_input($_POST['sl_api']), time() + (86400 * 30), "/");
    setcookie('ssbg_fc_api', clean_input($_POST['fc_api']), time() + (86400 * 30), "/");
    setcookie('ssbg_rl_api', clean_input($_POST['rl_api']), time() + (86400 * 30), "/");
    setcookie('ssbg_nc_api', clean_input($_POST['nc_api']), time() + (86400 * 30), "/");
    setcookie('ssbg_cnl', $_POST['cnl'], time() + (86400 * 30), "/");
    setcookie('ssbg_foldername', $_POST['containername'], time() + (86400 * 30), "/");
    setcookie('ssbg_game_grosse', $input['size'], time() + (86400 * 30), "/");
    setcookie('ssbg_game_unit', $input['size_unit'], time() + (86400 * 30), "/");
    setcookie('ssbg_game_archive', $input['archive'], time() + (86400 * 30), "/");
    setcookie('ssbg_nydus_fix', $_POST['nydus_fix'], time() + (86400 * 30), "/");

    /* TEMPLATE UPLOAD
    ------------------------------------------------------- */
    $upl_tpl = uploadTemplate("upl_tpl", $random);

    /* TEMPLATE BBCODE GENERATOR
    ------------------------------------------------------- */
    $tpl_bbcode = bbCode($input, $upl_tpl, $GLOBALS['bbCodePorn']);

    /* IF NYDUS FIX
    ------------------------------------------------------- */
    if (isset($_POST['nydus_fix']) && !empty($_POST['nydus_fix'])) {
        $tag_fixer = tag_fixer($tpl_bbcode, 'nydus');
    } else {
        $tag_fixer = $tpl_bbcode;
    }

    /* CRYPTER FILTERN UND AUSGEBEN
    ------------------------------------------------------- */
    if (is_array($tag_fixer)) {
        $count = 0;
        $out   = ''; /* php notice fix */
        foreach ($tag_fixer as $key => $elem) {
            $count++;
            $site_template->register_vars(array(
                "count"            => $count,
                "crypter_name"     => crypter_name($elem),
                "output"           => trim($elem),
                "github_gist_link" => githubGist($elem, $input['title'], $random, $settings['generator_name']),
                "color_sheme"      => $settings['color_sheme'],
                "lang_gist_link"   => $lang['personal_gist_link'],
                "lang_copy_code"   => $lang['copy_code'],
            ));
            $out .= $site_template->parse_template("gen_bit");
        }
    } else {
        $out = $tag_fixer;
    }

    /* LADE TEMPLATE
    ------------------------------------------------------- */
    $site_template->register_vars(array(
        "out"                 => $out,
        "lang_follow"         => $lang['follow'],
        "lang_donate"         => $lang['donate'],
        "lang_generator_name" => "BBcode",
        "navigation"          => $load_navigation,
        "head_icon"           => $settings['head_icon'],
        "wrapper"             => $settings['wrapper_size'],
    ));
    $site_template->print_template($site_template->parse_template("gen_take"));

}
/* GENERATOR STARTSEITE
------------------------------------------------------- */
else {

    /* LADE TEMPLATE
    ------------------------------------------------------- */

    $site_template->register_vars(array(
        "lang_error_info"       => $lang['error_info'],
        "lang_error_title"      => $lang['error_title'],
        "lang_error_cover"      => $lang['error_cover'],
        "lang_error_size"       => $lang['error_size'],
        "lang_error_units"      => $lang['error_units'],
        "lang_error_format"     => $lang['error_format'],
        "lang_error_ochlinks"   => $lang['error_ochlinks'],
        "lang_error_fileformat" => $lang['error_fileformat'],
    ));
    $validation = $site_template->parse_template("validation_porn");

    $site_template->register_vars(array(

        /* SETTINGS
        ------------------------------------------------------- */
        "form_action"                 => $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'],
        "color_sheme"                 => $settings['color_sheme'],
        "navigation"                  => $load_navigation,
        "validation"                  => $validation,
        "wrapper"                     => $settings['wrapper_size'],
        "head_icon"                   => $settings['head_icon'],
        "website_title"               => $lang['website_title_porn'],
        "rules_button"                => '                <div id="rules_button">
                    <a class="btn btn-danger" data-toggle="modal" data-target="#rulesplant">
                        <span class="badge">' . $lang['rulesplant'] . '</span></a>
                </div>',
        "donate_modal"                => $load_donate_modal,

        /* ALLGEMEIN
        ------------------------------------------------------- */
        "lang_generator_name"         => $settings['generator_name'],
        "lang_generator_variant_info" => $lang['generator_variant_info'],
        "lang_template_variant"       => $lang['template_variant'],
        "lang_standard"               => $lang['standard'],
        "lang_own"                    => $lang['own'],
        "lang_choose_template"        => $lang['choose_template'],
        "lang_no_choosed_template"    => $lang['no_choosed_template'],
        "lang_subtitle"               => $lang['subtitle'],
        "lang_spezified_settings"     => $lang['spezified_settings'],
        "lang_title"                  => $lang['title'],
        "lang_subtitle_tip"           => $lang['subtitle_tip'],
        "lang_text_color"             => $lang['text_color'],
        "lang_yes"                    => $lang['yes'],
        "lang_no"                     => $lang['no'],
        "lang_reset_field"            => $lang['reset_field'],
        "lang_use_cnl"                => $lang['use_cnl'],
        "lang_title_as_foldername"    => $lang['title_as_foldername'],
        "lang_cover_image"            => $lang['cover_image'],
        "lang_remote"                 => $lang['remote'],
        "lang_choose_cover"           => $lang['choose_cover'],
        "lang_no_cover_choosed"       => $lang['no_cover_choosed'],
        "lang_cover_settings"         => $lang['cover_settings'],
        "lang_watermark_text"         => $lang['watermark_text'],
        "lang_watermark_text_tip"     => $lang['watermark_text_tip'],
        "lang_bottom"                 => $lang['bottom'],
        "lang_center"                 => $lang['center'],
        "lang_reflektion"             => $lang['reflection'],
        "lang_greyscale"              => $lang['greyscale'],
        "lang_frame"                  => $lang['frame'],
        "lang_details"                => $lang['details'],
        "lang_genre"                  => $lang['genre'],
        "lang_optional_info"          => $lang['optional_info'],
        "lang_crack"                  => $lang['crack'],
        "lang_format"                 => $lang['format'],
        "lang_format_tip"             => str_replace('{format}', 'AVI, MKV, MP4, ...', $lang['format_tip']),
        "lang_choose_language"        => $lang['choose_language'],
        "lang_size"                   => $lang['size'],
        "lang_size_unit"              => $lang['size_unit'],
        "lang_archive"                => $lang['archive'],
        "lang_password"               => $lang['password'],
        "lang_password_tip"           => $lang['password_info'],
        "lang_information"            => $lang['information'],
        "lang_description"            => $lang['description'],
        "lang_description_tip"        => $lang['description_tip'],
        "lang_extra_information"      => $lang['extra_information'],
        "lang_extra_information_tip"  => $lang['extra_information_tip'],
        "lang_links_crypt"            => $lang['links_crypt'],
        "lang_crypt_info_faq"         => $lang['crypt_info_faq'],
        "lang_ochlinks_tip"           => $lang['ochlinks_tip'],
        "lang_sl_info_tip"            => $lang['sl_info_tip'],
        "lang_generate"               => $lang['generate'],
        "lang_follow"                 => $lang['follow'],
        "lang_donate"                 => $lang['donate'],
        "lang_rulesplant"             => $lang['rulesplant'],
        "lang_the_rulesplant"         => $lang['the_rulesplant'],
        "lang_rulesplant_info"        => $lang['rulesplant_info'],
        "lang_rules"                  => $lang['rules'],
        "lang_close"                  => $lang['close'],
        "lang_nydus_fix"              => $lang['nydus_fix'],

        /* SELECTOR
        ------------------------------------------------------- */
        "archive_select"              => lang_archive_select(),
        "size_unit_select"            => lang_unit_select(),

        /* COOKIES
        ------------------------------------------------------- */
        "cookie_fc_api"               => (isset($_COOKIE['ssbg_fc_api']) ? $_COOKIE['ssbg_fc_api'] : ''),
        "cookie_sl_username"          => (isset($_COOKIE['ssbg_sl_username']) ? $_COOKIE['ssbg_sl_username'] : ''),
        "cookie_sl_password"          => (isset($_COOKIE['ssbg_sl_password']) ? $_COOKIE['ssbg_sl_password'] : ''),
        "cookie_sl_api"               => (isset($_COOKIE['ssbg_sl_api']) ? $_COOKIE['ssbg_sl_api'] : ''),
        "cookie_rl_api"               => (isset($_COOKIE['ssbg_rl_api']) ? $_COOKIE['ssbg_rl_api'] : ''),
        "cookie_nc_api"               => (isset($_COOKIE['ssbg_nc_api']) ? $_COOKIE['ssbg_nc_api'] : ''),
        "cookie_cnl"                  => (isset($_COOKIE['ssbg_cnl']) && !empty($_COOKIE['ssbg_cnl'])) ? 'checked' : '',
        "cookie_foldername"           => (isset($_COOKIE['ssbg_foldername']) && !empty($_COOKIE['ssbg_foldername'])) ? 'checked' : '',
    ));
    $site_template->print_template($site_template->parse_template("porn"));
}
