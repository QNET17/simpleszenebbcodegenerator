<?php
class SSBG
{
    private $sMethod;
    // constructor
    public function __construct()
    {
        $this->sMethod = 'plain';
    }
    // set method
    public function setMethod($s)
    {
        $this->sMethod = $s;
    }
    // return list of videos
    public function getGamesCode($title, $subtitle, $password, $archive, $zinfo, $update, $size, $size_unit, $crack, $links, $genre, $desc, $nfo, $lang, $foldername, $cnlink, $color1, $color2, $fc_api, $sl_api, $sl_name, $sl_pw, $rl_api, $nc_api, $remote_cover, $watermark_text, $wartermark_variant, $template_code, $template_variant)
    {

        $linkfix = str_replace(";", "\r\n", $links); // Fix Links for OCH

        /* FORMULARDATEN SAMMELN
        ------------------------------------------------------- */
        $random                 = random_hashcode('25'); /* einmaliger unique string */
        $input                  = array();
        $input['hashcode']      = $random;
        $input['title']         = (!empty($title) ? $title : '');
        $input['subtitle']      = (!empty($subtitle) ? clean_input($subtitle) : '');
        $input['passw']         = (!empty($password) ? clean_input($password) : 'Kein Passwort');
        $input['archive']       = (!empty($archive) ? clean_input($archive) : '');
        $input['zinfo']         = (!empty($zinfo) ? clean_input($zinfo) : '');
        $input['update']        = (!empty($update) ? clean_input($update) : '');
        $input['update_mini']   = (!empty($update) ? clean_input($update) : '');
        $input['size']          = (!empty($size) ? clean_input($size) : '');
        $input['size_unit']     = (!empty($size_unit) ? clean_input($size_unit) : '');
        $input['crack']         = (!empty($crack) ? clean_input($crack) : '');
        $input['ochlinks']      = (!empty($linkfix) ? clean_input($linkfix) : '');
        $input['genre']         = (!empty($genre) ? clean_input($genre) : '');
        $input['description']   = (!empty($desc) ? clean_input($desc) : '');
        $input['nfo']           = (!empty($nfo) ? clean_input($nfo) : '');
        $input['language']      = (!empty($lang) ? clean_input($lang) : '');
        $input['containername'] = (!empty($foldername) ? clean_input($title) : $random);
        $input['cnlink']        = (!empty($cnlink) ? '[URL="' . cnl(clean_input($linkfix)) . '"]Click\'n\'Load[/URL]' : '');
        $input['text1']         = (!empty($color1) ? '[COLOR="' . $color1 . '"]' : '');
        $input['text2']         = (!empty($color2) ? '[COLOR="' . $color2 . '"]' : '');

        /* REMOTE / UPLOAD - COVER
        ------------------------------------------------------- */
        $if_watermark = (!empty($wartermark_variant) ? $wartermark_variant : '');
        if (isset($remote_cover) && !empty($remote_cover)) {
            copy($remote_cover, "tmp/" . $random . "-TEMP-.png");
            $input['cover'] = uploadRemoteCover($random, "tmp/" . $random . "-TEMP-.png", $watermark_text, $if_watermark);
        } elseif (isset($upload_cover) && !empty($upload_cover)) {
            $input['cover'] = uploadCover($upload_cover, $random, $watermark_text, $if_watermark);
        } else {
            $input['cover'] = ''; /* use fallbackcover, defined in function bbCode */
        }

        /* CRYPTER DEFINIEREN
        ------------------------------------------------------- */
        $lang    = array();
        $lang_dl = $lang["download"];

        if (isset($fc_api) && !empty($fc_api)) {
            $fcData              = filecryptCC($input['ochlinks'], clean_input($fc_api), $input['containername']);
            $input['fc_link']    = '[URL="' . $fcData[0] . '"]' . $lang_dl . '[/URL]';
            $input['fc_img']     = $fcData[1];
            $input['fc_control'] = $fc_api;
        }

        if (isset($sl_api) && !empty($sl_api)) {
            $slData              = shareLinksBIZ($input['ochlinks'], clean_input($sl_api), $input['containername'], clean_input($sl_name), trim($sl_pw));
            $input['sl_link']    = '[URL="' . $slData[0] . '"]' . $lang_dl . '[/URL]';
            $input['sl_img']     = $slData[1];
            $input['sl_control'] = $sl_api;
        }

        if (isset($rl_api) && !empty($rl_api)) {
            $rlData              = reLink($input['ochlinks'], clean_input($rl_api), $input['containername']);
            $input['rl_link']    = '[URL="' . $rlData[0] . '"]' . $lang_dl . '[/URL]';
            $input['rl_img']     = $rlData[1];
            $input['rl_control'] = $rl_api;
        }

        if (isset($nc_api) && !empty($nc_api)) {
            $ncData              = nCryptIN($input['ochlinks'], clean_input($nc_api), $input['containername']);
            $input['nc_link']    = '[URL="' . $ncData[0] . '"]' . $lang_dl . '[/URL]';
            $input['nc_img']     = $ncData[1];
            $input['nc_control'] = $nc_api;
        }

        /* TEMPLATE UPLOAD
        ------------------------------------------------------- */
        switch ($template_variant) {
            case 'code':
                $upl_tpl = $template_code;
                break;
            case 'remote':
                $upl_tpl = file_get_contents($template_code);
                break;
            case 'standard':
                $upl_tpl = '';
                break;
        }

        /* TEMPLATE BBCODE GENERATOR
        ------------------------------------------------------- */
        $tpl_bbcode = bbCode($input, $upl_tpl, $GLOBALS['bbCodeGames']);

        // output in necessary format
        switch ($this->sMethod) {
            case 'plain':

                /* CRYPTER FILTERN UND AUSGEBEN: TEXT
                ------------------------------------------------------- */
                if (is_array($tpl_bbcode)) {
                    $count = 0;
                    $out   = ''; /* php notice fix */
                    foreach ($tpl_bbcode as $key => $elem) {
                        $count++;
                        $search = array(
                            "{count}",
                            "{crypter_name}",
                            "{github_gist_link}",
                            "{output}",
                        );
                        $replace = array(
                            $count,
                            crypter_name($elem),
                            githubGist($elem, $input['title'], $random, 'Games'),
                            trim($elem),
                        );
                        $out = file_get_contents("templates/api_out.html");
                        echo str_replace($search, $replace, $out);
                    }
                } else {
                    echo $tpl_bbcode;
                }
                break;
            case 'bbcode':

                /* CRYPTER FILTERN UND AUSGEBEN: BBCODE ONLY
                ------------------------------------------------------- */
                if (is_array($tpl_bbcode)) {
                    $count = 0;
                    $out   = ''; /* php notice fix */
                    foreach ($tpl_bbcode as $key => $elem) {
                        $count++;
                        $search = array(
                            "{output}",
                        );
                        $replace = array(
                            trim($elem),
                        );
                        $out = file_get_contents("templates/api_bbcode_out.html");
                        echo str_replace($search, $replace, $out);
                    }
                } else {
                    echo $tpl_bbcode;
                }
                break;
            case 'json':

                /* CRYPTER FILTERN UND AUSGEBEN: JSON
                ------------------------------------------------------- */
                if (is_array($tpl_bbcode)) {
                    $count   = 0;
                    $out     = ''; /* php notice fix */
                    $myArray = array();
                    foreach ($tpl_bbcode as $key => $elem) {
                        $count++;
                        $myArray[] = array(
                            'crypter'    => crypter_name($elem),
                            'githubgist' => githubGist($elem, $input['title'], $random, 'Games'),
                            'code'       => trim($elem),
                        );
                    }
                    $json = json_encode($myArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
                    print_r($json);
                } else {
                    echo $tpl_bbcode;
                }
                break;
            case 'xml':

                /* CRYPTER FILTERN UND AUSGEBEN: XML
                ------------------------------------------------------- */
                if (is_array($tpl_bbcode)) {
                    $count = 0;
                    $out   = ''; /* php notice fix */
                    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
                    echo '<xml>';
                    foreach ($tpl_bbcode as $key => $elem) {
                        $count++;
                        echo '
                            <bbcode>
                              <crypter>' . crypter_name($elem) . '</crypter>
                              <githubgist>' . githubGist($elem, $input['title'], $random, 'Games') . '</githubgist>
                              <code>' . trim($elem) . '</code>
                            </bbcode>
                        ';
                    }
                    echo '</xml>';

                } else {
                    echo $tpl_bbcode;
                }
                break;
            case 'array':

                /* CRYPTER FILTERN UND AUSGEBEN: ARRAY
                ------------------------------------------------------- */
                if (is_array($tpl_bbcode)) {
                    $count = 0;
                    $out   = ''; /* php notice fix */
                    foreach ($tpl_bbcode as $key => $elem) {
                        $count++;
                        $myArray[] = array(
                            'crypter'    => crypter_name($elem),
                            'githubgist' => githubGist($elem, $input['title'], $random, 'Games'),
                            'code'       => trim($elem),
                        );
                    }
                    print_r($myArray);
                } else {
                    echo $tpl_bbcode;
                }
                break;
        }
    }
}
