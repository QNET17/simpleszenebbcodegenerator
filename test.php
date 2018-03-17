<?php
define('YES', '1');
define('NO', '');

// SEPERATE BY KOMMAR
// LIKE: http://ul.to/123;http://ul.to/456;http://ul.to/789
$links = '';

// READ THE API DOCUMENTATION
// USE AS TEMPLATE-VARIANT
// STANDARD: THE SYSTEM USE THE STANDARD-TEMPLATE
// CODE    : USE CODE LIKE: [B]##TITLE##[/B]...
// REMOTE  : USE RAW-LINK FROM PASTEBIN OR GITHUBGIST
$template = '[B]##TITLE##[/B]';

$postdata = array(
    'type'              => 'xml',
    'action'            => 'games',
    'cnl'               => YES,
    'containername'     => YES,
    'text1'             => 'TEST-COLOR1',
    'text2'             => 'TEST-COLOR2',
    'fc_api'            => '7985f3f72278079d4247ede30cc0553911579a7f',
    'sl_api'            => '2ed183155346a56a',
    'sl_name'           => 'BoeseBZ',
    'sl_pw'             => 'Sum7ale-88',
    'rl_api'            => 'e595146fff2f6dab58094cd50d2ec13fbc77c0d3',
    'nc_api'            => 'niW9UNLTZij5wj768NmkGWLCglr6rrsX',
    'remotecover'       => 'http://fs5.directupload.net/images/170507/p9lrv8e7.jpg',
    'watermark_text'    => 'WATERMARK-TEXT',
    'watermark_variant' => 'wartermark_bottom',
    'ochlinks'          => $links,
    'template_variant'  => 'code',
    'template_code'     => $template,
    // SPEZIFIED
    'title'             => 'TEST-TITEL',
    'subtitle'          => 'TEST-UNTERTITEL',
    'password'          => 'TEST-PASSWORT',
    'archive'           => 'TEST-ARCHIVE',
    'zinfo'             => 'TEST-ZINFO',
    'update'            => 'TEST-UPDATE',
    'size'              => 'TEST-SIZE',
    'units'             => 'TEST-UNITS',
    'crack'             => 'TEST-CRACK',
    'genre'             => 'TEST-GENRE',
    'description'       => 'TEST-DESC',
    'nfo'               => 'DIE-NFO',
    'language'          => 'TEST-LANGUAGE',
);
$ssbg = curl_init();
curl_setopt($ssbg, CURLOPT_URL, 'http://localhost/ssbgv2/api.php');
curl_setopt($ssbg, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ssbg, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ssbg, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ssbg, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ssbg, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0");
$output = curl_exec($ssbg);
curl_close($ssbg);
if (in_array("xml", $postdata)) {
    header('Content-type: text/xml');
    header('Pragma: public');
    header('Cache-control: private');
    header('Expires: -1');
    echo $output;
} else {
    echo "<pre>" . $output . "<pre>";
}
