<?php

/* SSBG TEMPLATE-VORLAGEN
------------------------------------------------------- */
$GLOBALS['bbCodeMovies']     = file_get_contents('templates/upload/movies.ssbg');
$GLOBALS['bbCodeMoviesXrel'] = file_get_contents('templates/upload/moviesXrel.ssbg');
$GLOBALS['bbCodeMusic']      = file_get_contents('templates/upload/music.ssbg');
$GLOBALS['bbCodeGames']      = file_get_contents('templates/upload/games.ssbg');
$GLOBALS['bbCodePorn']       = file_get_contents('templates/upload/porn.ssbg');
$GLOBALS['bbCodeSoftware']   = file_get_contents('templates/upload/software.ssbg');
$GLOBALS['bbCodeSimple']     = file_get_contents('templates/upload/simple.ssbg');

/**
 * Erstellt den endgültigen BB-Code
 *
 * Example: <code> movieTemplate(array('title' => '1.2.3-Scene', 'size' => '8457mb'), 'template.txt');</code>
 *
 * @param string $template
 * @param array $input
 * @return array $result
 */

function bbCode($input, $template = '', $globaltemplate)
{
    global $lang;

    /* WUNSCHTEMPLATE ÜBERPRÜFEN, SONST STANDARD-TEMPLATE
    ------------------------------------------------------- */
    $template = (empty($template) ? $globaltemplate : $template);

    /* LEERE ARRAYS ENTFERNEN
    ------------------------------------------------------- */
    $input = array_filter($input);

    /* NACH SCHLÜSSELWÖRTER SUCHEN
    ------------------------------------------------------- */
    $searchFor = array(
        '{##TITLE##}',
        '{##IF_TITLE2##}',
        '{##SPRACHE##}',
        '{##LIFETIME##}',
        '{##ASTREAM##}',
        '{##VSTREAM##}',
        '{##VCODEC##}',
        '{##RELEASED##}',
        '{##YEAR##}',
        '{##SIZE##}',
        '{##HASHCODE##}',
        '{##DESCRIPTION##}',
        '{##NFO##}',
        '{##CNLINK##}',
        '{##GENRE##}',
        '{##COVER##}',
        '{##PASSWORD##}',
        '{##IF_UPDATE##}',
        '{##IF_GENRE##}',
        '{##IF_CRACK##}',
        '{##UPDATE_MINI##}',
        '{##ARCHIVE##}',
        '{##IF_DESCRIPTION##}',
        '{##IF_Z_INFO##}',
        '{##QUALITY##}',
        '{##IF_ARCHIVE##}',
        '{##FORMAT##}',
        '{##TRACKLIST##}',
        '{##BUILD##}',
        '{##LANGUAGE##}',
        '{##VERSION##}',
        '{##COLOR1##}',
        '{##COLOR2##}',
        '{##COLOR##}',
        '{##COLOR##}',
        '{##MEDIAINFO##}',
    );
    /* SCHLÜSSELWÖRTER ERSETZEN - REIHENFOLGE BEACHTEN !
    ------------------------------------------------------- */
    $replaceWith = array(
        array_key_exists('title', $input) ? htmlentities($input['title'], ENT_QUOTES) : '!!!' . $lang['error_title'] . '!!!',
        array_key_exists('subtitle', $input) ? htmlentities($input['subtitle'], ENT_QUOTES) : '',
        array_key_exists('language', $input) ? htmlentities($input['language'], ENT_QUOTES) : '',
        array_key_exists('runtime', $input) ? htmlentities($input['runtime'], ENT_QUOTES) : '',
        array_key_exists('astream', $input) ? htmlentities($input['astream'], ENT_QUOTES) : '',
        array_key_exists('vstream', $input) ? htmlentities($input['vstream'], ENT_QUOTES) : '',
        array_key_exists('vcodec', $input) ? htmlentities($input['vcodec'], ENT_QUOTES) : '',
        array_key_exists('released', $input) ? htmlentities($input['released'], ENT_QUOTES) : '',
        array_key_exists('year', $input) ? htmlentities($input['year'], ENT_QUOTES) : '',
        array_key_exists('size', $input) ? htmlentities($input['size'] . " " . $input['size_unit'], ENT_QUOTES) : '!!!' . $lang['error_size'] . '!!!',
        array_key_exists('hashcode', $input) ? htmlentities($input['hashcode'], ENT_QUOTES) : '',
        array_key_exists('description', $input) ? htmlentities($input['description'], ENT_QUOTES) : $lang['error_description'],
        array_key_exists('nfo', $input) ? htmlentities($input['nfo'], ENT_QUOTES) : $lang['error_nfo'],
        array_key_exists('cnlink', $input) ? $input['cnlink'] : '',
        array_key_exists('genre', $input) ? htmlentities($input['genre'], ENT_QUOTES) : '',
        array_key_exists('cover', $input) ? htmlentities($input['cover'], ENT_QUOTES) : 'http://i.imgur.com/3YXnFTi.gif',
        array_key_exists('passw', $input) ? htmlentities($input['passw'], ENT_QUOTES) : '',
        array_key_exists('update', $input) ? htmlentities('inkl. Update ' . $input['update'], ENT_QUOTES) : '',
        array_key_exists('genre', $input) ? htmlentities($input['genre'], ENT_QUOTES) : '',
        array_key_exists('crack', $input) ? htmlentities($input['crack'], ENT_QUOTES) : '',
        array_key_exists('update', $input) ? htmlentities($input['update'], ENT_QUOTES) : '',
        array_key_exists('archive', $input) ? htmlentities($input['archive'], ENT_QUOTES) : '',
        array_key_exists('description', $input) ? htmlentities($input['description'], ENT_QUOTES) : $lang['error_description'],
        array_key_exists('zinfo', $input) ? htmlentities($input['zinfo'], ENT_QUOTES) : '',
        array_key_exists('quality', $input) ? htmlentities($input['quality'], ENT_QUOTES) : '',
        array_key_exists('archive', $input) ? htmlentities($input['archive'], ENT_QUOTES) : '',
        array_key_exists('format', $input) ? htmlentities($input['format'], ENT_QUOTES) : '',
        array_key_exists('tracklist', $input) ? htmlentities($input['tracklist'], ENT_QUOTES) : '',
        array_key_exists('build', $input) ? htmlentities($input['build'], ENT_QUOTES) : '',
        array_key_exists('language', $input) ? htmlentities($input['language'], ENT_QUOTES) : '',
        array_key_exists('version', $input) ? htmlentities($input['version'], ENT_QUOTES) : '',
        array_key_exists('text1', $input) ? htmlentities($input['text1'], ENT_QUOTES) : '',
        array_key_exists('text2', $input) ? htmlentities($input['text2'], ENT_QUOTES) : '',
        array_key_exists('text1', $input) ? htmlentities('[/COLOR]', ENT_QUOTES) : '',
        array_key_exists('text2', $input) ? htmlentities('[/COLOR]', ENT_QUOTES) : '',
        array_key_exists('mediainfo', $input) ? htmlentities($input['mediainfo'], ENT_QUOTES) : '',
    );

    /* ERSETZE ALLES WAS MÖGLICH WAR
    ------------------------------------------------------- */
    $firstStroke = preg_replace($searchFor, $replaceWith, $template);

    /* CRYPTER FILTERN UND AUSGABE ERSTELLEN
    ------------------------------------------------------- */
    $result = array();
    /* FILECRYPT.CC
    ------------------------------------------------------- */
    if (array_key_exists('fc_control', $input)) {
        $result['fc'] = preg_replace(array(
            '{##STATUS##}',
            '{##DLINK##}',
        ), array(
            array_key_exists('fc_img', $input) ? '[img]' . htmlentities($input['fc_img'], ENT_QUOTES) . '[/img]' : '##STATUS##',
            array_key_exists('fc_link', $input) ? htmlentities($input['fc_link'], ENT_QUOTES) : '##DLINK##',
        ), $firstStroke);
    }
    /* SHARE-LINKS.BIZ
    ------------------------------------------------------- */
    if (array_key_exists('sl_control', $input)) {
        $result['sl'] = preg_replace(array(
            '{##STATUS##}',
            '{##DLINK##}',
        ), array(
            array_key_exists('sl_img', $input) ? '[img]' . htmlentities($input['sl_img'], ENT_QUOTES) . '[/img]' : '##STATUS##',
            array_key_exists('sl_link', $input) ? htmlentities($input['sl_link'], ENT_QUOTES) : '##DLINK##',
        ), $firstStroke);
    }
    /* RELINK.US/.TO
    ------------------------------------------------------- */
    if (array_key_exists('rl_control', $input)) {
        $result['rl'] = preg_replace(array(
            '{##STATUS##}',
            '{##DLINK##}',
        ), array(
            array_key_exists('rl_img', $input) ? '[img]' . htmlentities($input['rl_img'], ENT_QUOTES) . '[/img]' : '##STATUS##',
            array_key_exists('rl_link', $input) ? htmlentities($input['rl_link'], ENT_QUOTES) : '##DLINK##',
        ), $firstStroke);
    }
    /* NCRYPT.IN
    ------------------------------------------------------- */
    if (array_key_exists('nc_control', $input)) {
        $result['nc'] = preg_replace(array(
            '{##STATUS##}',
            '{##DLINK##}',
        ), array(
            array_key_exists('nc_img', $input) ? '[img]' . htmlentities($input['nc_img'], ENT_QUOTES) . '[/img]' : '##STATUS##',
            array_key_exists('nc_link', $input) ? htmlentities($input['nc_link'], ENT_QUOTES) : '##DLINK##',
        ), $firstStroke);
    }
    return preg_replace('#(<br */?>\s*)+#i', '<br />', $result);
}

/* Beispiel wie die eingabe erfolgt für $input
$input = array();
$input['title'] = 'Ich.bin.ein.Release.German.Dubbed.x264.fgr';
$input['subtitle'] = 'Romania';
$input['language'] = 'German';
$input['runtime'] = '94min';
$input['astream'] = 'AC3';
$input['vstream'] = 'BDRiP';
$input['cinedate'] = '2016';
$input['size'] = '8457mb';
$input['hashcode'] = '14521782xxjehdnm';

oder

$input = array('title' => 'Ich.bin.ein.Release.German.Dubbed.x264.fgr', 'subtitle' => 'Romania');
 */
/* Beispiel wie man es ausgibt um den User das gewünschte Ergebniss anzuzeigen
Standardvorlagen sind global erreichbar (zb. $GLOBALS['bbCodeMovies'], $GLOBALS['bbCodeMusic'] anstatt $template)

$bb_code = bbCode($input, $template);

if (is_array($bb_code)) {
foreach($bb_code as $key => $elem){
echo '<pre>'.$elem.'</pre><hr />';
}
} else {
echo $bb_code;
}

 */

/**
 * Erstellt eine BB-Code Ausgabe zum debuggen
 *
 * Example: <code> parsebb('<b>Test</b>');</code>
 *
 * @param string $body
 * @return array $result
 */

function parsebb($body)
{
    $find = array(
        "@\n@",
        "@[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]@is",
        "/\[url\=(.+?)\](.+?)\[\/url\]/is",
        "/\[b\](.+?)\[\/b\]/is",
        "/\[i\](.+?)\[\/i\]/is",
        "/\[u\](.+?)\[\/u\]/is",
        "/\[color\=(.+?)\](.+?)\[\/color\]/is",
        "/\[size\=(.+?)\](.+?)\[\/size\]/is",
        "/\[font\=(.+?)\](.+?)\[\/font\]/is",
        "/\[center\](.+?)\[\/center\]/is",
        "/\[right\](.+?)\[\/right\]/is",
        "/\[left\](.+?)\[\/left\]/is",
        "/\[IMG\](.+?)\[\/IMG\]/is",
        "/\[email\](.+?)\[\/email\]/is",
    );
    $replace = array(
        "<br />",
        "<a href=\"\\0\">\\0</a>",
        "<a href=\"$1\" target=\"_blank\">$2</a>",
        "<strong>$1</strong>",
        "<em>$1</em>",
        "<span style=\"text-decoration:underline;\">$1</span>",
        "<font color=\"$1\">$2</font>",
        "<font size=\"$1\">$2</font>",
        "<span style=\"font-family: $1\">$2</span>",
        "<div style=\"text-align:center;\">$1</div>",
        "<div style=\"text-align:right;\">$1</div>",
        "<div style=\"text-align:left;\">$1</div>",
        "<img src=\"$1\" alt=\"Image\" />",
        "<a href=\"mailto:$1\" target=\"_blank\">$1</a>",
    );
    $body = htmlspecialchars($body);
    $body = preg_replace($find, $replace, $body);
    return $body;
}
