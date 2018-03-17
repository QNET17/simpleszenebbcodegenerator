<?php
require_once 'global.php'; // including service class to work with database
require_once 'api_class.php'; // including service class to work with database
$oServices = new SSBG();
// set method
$oServices->setMethod($_REQUEST['type']);
// process actions
switch ($_REQUEST['action']) {
    case 'games':
        echo post_required('title:remotecover:size:units:description:nfo:ochlinks', $_POST);
        $oServices->getGamesCode($_POST['title'], $_POST['subtitle'], $_POST['password'], $_POST['archive'], $_POST['zinfo'], $_POST['update'], $_POST['size'], $_POST['units'], $_POST['crack'], $_POST['ochlinks'], $_POST['genre'], $_POST['description'], $_POST['nfo'], $_POST['language'], $_POST['containername'], $_POST['cnl'], $_POST['text1'], $_POST['text2'], $_POST['fc_api'], $_POST['sl_api'], $_POST['sl_name'], $_POST['sl_pw'], $_POST['rl_api'], $_POST['nc_api'], $_POST['remotecover'], $_POST['watermark_text'], $_POST['watermark_variant'], $_POST['template_code'], $_POST['template_variant']);
        break;
}
