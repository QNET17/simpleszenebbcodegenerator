<?php

/**
 * Zerlegt einen String anhand eines Regex
 *
 * @param string $regex
 * @param string $content
 * @param number $pos
 * @return string
 */

function get_match($regex, $content, $pos = 1)
{
    /* do your job */
    preg_match($regex, $content, $matches);
    /* return our result */
    return $matches[intval($pos)];
}

function array2xml($array, $xml = false)
{
    if ($xml === false) {
        $xml = new SimpleXMLElement('<root/>');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            array2xml($value, $xml->addChild($key));
        } else {
            $xml->addChild($key, $value);
        }
    }
    return $xml->asXML();
}

/* ZUFÄLLIGER HASHCODE
------------------------------------------------------- */
function random_hashcode($length, $letters_only = false)
{
    $str = '';
    if (!$letters_only) {
        while (strlen($str) <= $length) {
            $str .= md5(uniqid(rand(), true));
        }

        return substr($str, 0, $length);
    }

    for ($i = 0; $i < $length; $i++) {
        switch (mt_rand(1, 2)) {
            case 1:
                $str .= chr(mt_rand(65, 90));
                break;

            case 2:
                $str .= chr(mt_rand(97, 122));
                break;
        }
    }

    return $str;
}

/* TAG CLEANER
------------------------------------------------------- */
function clean_input($text)
{
    $text = strip_tags($text);
    $text = trim($text);
    return $text;
}

/* CLICK'N'LOAD LINK
------------------------------------------------------- */
function cnl($links)
{
    $cnl_links = preg_replace('/\s\s+/', '/r/n', $links);
    $cnl       = "http://127.0.0.1:9666/flash/add?source=http://jdownloader.org/spielwiese&urls=" . $cnl_links;
    return $cnl;
}

/* UPLOADFORM: OWN-TEMPLATE
------------------------------------------------------- */
function uploadTemplate($name, $random)
{
    $handle = new Upload($_FILES[$name]);
    if ($handle->uploaded) {
        $handle->allowed = array(
            'text/plain',
        );
        $handle->file_new_name_body = $random;
        $handle->file_overwrite     = true;
        $handle->file_max_size      = '2560'; // 2,5 KB
        $handle->Process("tmp");
        if ($handle->processed) {
            $upl_tpl  = 'tmp/' . $handle->file_dst_name;
            $template = file_get_contents($upl_tpl);
        } else {
            echo '<p class="result">';
            echo '  <b>File not uploaded to the wanted location</b><br />';
            echo '  Error: ' . $handle->error . '';
            echo '</p>';
        }

        $handle->Clean();
        unlink("tmp/" . $random . ".txt");
    } else {
        $template = '';
    }

    return $template;
}

/* UPLOADFORM: REMOTECOVER
------------------------------------------------------- */
function uploadRemoteCover($random, $location, $watermark_text = "", $wartermark_variant, $imghost)
{
    $handle = new Upload($location);
    if ($handle->uploaded) {
        $handle->allowed = array(
            'image/*',
        );
        $handle->file_new_name_body = $random;
        $handle->file_overwrite     = true;
        $if_image_width             = getimagesize($location);
        if ($if_image_width[0] < 600) {
            switch ($wartermark_variant) {
                case 'wartermark_bottom':
                    $handle->image_unsharp        = true;
                    $handle->image_border         = '0 0 16 0';
                    $handle->image_border_color   = '#000000';
                    $handle->image_text           = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_font      = 2;
                    $handle->image_text_position  = 'B';
                    $handle->image_text_padding_y = 2;
                    break;
                case 'wartermark_transparent_middle':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    break;
                case 'wartermark_reflect':
                    $handle->image_text              = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_background   = '#000000';
                    $handle->image_text_font         = 2;
                    $handle->image_text_padding      = 10;
                    $handle->image_text_line_spacing = 10;
                    $handle->image_reflection_height = '20%';
                    break;
                case 'wartermark_frame':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    $handle->image_frame                   = 1;
                    $handle->image_frame_colors            = '#000000 #FFFFFF #FFFFFF #000000';
                    break;
                case 'wartermark_greyscale':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    $handle->image_greyscale               = true;
                    break;
            }

            $handle->image_resize = false;
        } else {
            switch ($overlay) {
                case 'wartermark_bottom':
                    $handle->image_unsharp        = true;
                    $handle->image_border         = '0 0 16 0';
                    $handle->image_border_color   = '#000000';
                    $handle->image_text           = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_font      = 2;
                    $handle->image_text_position  = 'B';
                    $handle->image_text_padding_y = 2;
                    break;
                case 'wartermark_transparent_middle':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    break;
                case 'wartermark_reflect':
                    $handle->image_text              = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_background   = '#000000';
                    $handle->image_text_font         = 2;
                    $handle->image_text_padding      = 10;
                    $handle->image_text_line_spacing = 10;
                    $handle->image_reflection_height = '20%';
                    break;
                case 'wartermark_frame':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    $handle->image_frame                   = 1;
                    $handle->image_frame_colors            = '#000000 #FFFFFF #FFFFFF #000000';
                    break;
                case 'wartermark_greyscale':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    $handle->image_greyscale               = true;
                    break;
            }

            //$handle->image_ratio   = true;
            $handle->image_resize  = true;
            $handle->image_ratio_y = true;
            $handle->image_x       = 600;
        }

        $handle->Process("tmp");
        if ($handle->processed) {
            switch ($imghost) {
                case 'imgur':
                    $cover_out = imGUR('tmp/' . $handle->file_dst_name);
                    break;
                case 'imgdu':
                    $cover_out = directUpload($GLOBALS['domain'].'tmp/' . $handle->file_dst_name);
                    break;
            }
        } else {
            echo '<p class="result">';
            echo '  <b>File not uploaded to the wanted location (REMOTE)</b><br />';
            echo '  Error: ' . $handle->error . '';
            echo '</p>';
        }

        $handle->Clean();
        unlink("tmp/" . $handle->file_dst_name);
    } else {
        echo '<p class="result">';
        echo '  <b>File not uploaded on the server (REMOTE)</b><br />';
        echo '  Error: ' . $handle->error . '';
        echo '</p>';
    }

    return $cover_out;
}

/* UPLOADFORM: UPLOADCOVER
------------------------------------------------------- */
function uploadCover($name, $random, $watermark_text = "", $wartermark_variant, $imghost)
{
    $handle = new Upload($_FILES[$name]);
    if ($handle->uploaded) {
        $handle->allowed = array(
            'image/*',
        );
        $handle->file_new_name_body = $random;
        $handle->file_overwrite     = true;
        $if_image_width             = getimagesize($_FILES[$name]['tmp_name']);
        if ($if_image_width[0] < 600) {
            switch ($wartermark_variant) {
                case 'wartermark_bottom':
                    $handle->image_unsharp        = true;
                    $handle->image_border         = '0 0 16 0';
                    $handle->image_border_color   = '#000000';
                    $handle->image_text           = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_font      = 2;
                    $handle->image_text_position  = 'B';
                    $handle->image_text_padding_y = 2;
                    break;
                case 'wartermark_transparent_middle':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    break;
                case 'wartermark_reflect':
                    $handle->image_text              = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_background   = '#000000';
                    $handle->image_text_font         = 2;
                    $handle->image_text_padding      = 10;
                    $handle->image_text_line_spacing = 10;
                    $handle->image_reflection_height = '20%';
                    break;
                case 'wartermark_frame':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    $handle->image_frame                   = 1;
                    $handle->image_frame_colors            = '#000000 #FFFFFF #FFFFFF #000000';
                    break;
                case 'wartermark_greyscale':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    $handle->image_greyscale               = true;
                    break;
            }

            $handle->image_resize = false;
        } else {
            switch ($wartermark_variant) {
                case 'wartermark_bottom':
                    $handle->image_unsharp        = true;
                    $handle->image_border         = '0 0 16 0';
                    $handle->image_border_color   = '#000000';
                    $handle->image_text           = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_font      = 2;
                    $handle->image_text_position  = 'B';
                    $handle->image_text_padding_y = 2;
                    break;
                case 'wartermark_transparent_middle':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    break;
                case 'wartermark_reflect':
                    $handle->image_text              = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_background   = '#000000';
                    $handle->image_text_font         = 2;
                    $handle->image_text_padding      = 10;
                    $handle->image_text_line_spacing = 10;
                    $handle->image_reflection_height = '20%';
                    break;
                case 'wartermark_frame':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    $handle->image_frame                   = 1;
                    $handle->image_frame_colors            = '#000000 #FFFFFF #FFFFFF #000000';
                    break;
                case 'wartermark_greyscale':
                    $handle->image_text                    = (!empty($watermark_text) ? clean_input($watermark_text) : 'NO TEXT');
                    $handle->image_text_color              = '#000000';
                    $handle->image_text_opacity            = 80;
                    $handle->image_text_background         = '#FFFFFF';
                    $handle->image_text_background_opacity = 70;
                    $handle->image_text_font               = 5;
                    $handle->image_text_padding            = 20;
                    $handle->image_greyscale               = true;
                    break;
            }

            //$handle->image_ratio   = true;
            $handle->image_resize  = true;
            $handle->image_ratio_y = true;
            $handle->image_x       = 600;
        }

        $handle->Process("tmp");
        if ($handle->processed) {
            switch ($imghost) {
                case 'imgur':
                    $cover_out = imGUR('tmp/' . $handle->file_dst_name);
                    break;
                case 'imgdu':
                    $cover_out = directUpload($GLOBALS['domain'].'tmp/' . $handle->file_dst_name);
                    break;
            }
        } else {
            echo '<p class="result">';
            echo '  <b>File not uploaded to the wanted location (FILEUPLOAD)</b><br />';
            echo '  Error: ' . $handle->error . '';
            echo '</p>';
        }

        $handle->Clean();
        if (file_exists("tmp/" . $handle->file_dst_name)) {
            unlink("tmp/" . $handle->file_dst_name);
        }
    } else {
        echo '<p class="result">';
        echo '  <b>File not uploaded on the server (FILEUPLOAD)</b><br />';
        echo '  Error: ' . $handle->error . '';
        echo '</p>';
    }

    return (isset($cover_out) && !empty($cover_out) ? $cover_out : "Cover could not be uploaded!");
}

/* IMAGEHOSTER: IMGUR
------------------------------------------------------- */
function imGUR($tmp)
{
    global $imgur_api;
    $image = file_get_contents($tmp);
    $ch    = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Client-ID $imgur_api",
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'image' => base64_encode($image),
    ));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $reply = curl_exec($ch);
    curl_close($ch);
    $reply     = json_decode($reply);
    $cover_out = $reply->data->link;
    return $cover_out;
}

/* IMAGEHOSTER: IMGUR / DIRECTUPLOAD
------------------------------------------------------- */
function directUpload($tmp)
{
    $image       = file_get_contents($tmp);
    $conf['url'] = 'http://www.directupload.net/index.php?mode=upload&image_link=' . $tmp;
    /* do some curl magic */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $conf['url']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0");
    $content = curl_exec($ch);
    curl_close($ch);
    $grab['image'] = @get_match('/\[URL=http:\/\/www\.directupload\.net]\[IMG](.*?)\[\/IMG]\[\/URL]/s', $content);
    return $grab['image'];
}

/* CRYPTER: FILECRYPT.CC
------------------------------------------------------- */
function filecryptCC($links, $api, $foldername)
{
    define('YES', 1);
    define('NO', 0);
    $USE_SSL  = true;
    $api_key  = $api;
    $name     = $foldername;
    $group    = "";
    $mirror_1 = array(explode(PHP_EOL, $links));
    $postdata = http_build_query(array(
        "fn"          => "containerV2",
        "sub"         => "create",
        "api_key"     => $api_key,
        "name"        => $name,
        "mirror_1"    => $mirror_1,
        "captcha"     => YES,
        "allow_cnl"   => YES,
        "allow_dlc"   => YES,
        "allow_links" => YES,
        "group"       => $group,
    ));
    $opts = array('http' => array(
        "method"  => "POST",
        'header'  => "Connection: close\r\n" .
        "Content-type: application/x-www-form-urlencoded\r\n" .
        "Content-Length: " . strlen($postdata) . "\r\n",
        "content" => $postdata,
    ));
    $context = stream_context_create($opts);
    $result  = file_get_contents('http' . (($USE_SSL) ? 's' : '') . '://www.filecrypt.cc/api.php', false, $context);
    if (!$result) {
        throw new Exception("filecrypt.cc api down");
    } else {
        $json    = json_decode($result);
        $fc_link = $json->container->link;
        $fc_simg = $json->container->bigimg;
    }

    $fc_array = array(
        $fc_link,
        $fc_simg,
    );
    return ($fc_array);
}

/* CRYPTER: SHARE-LINKS.BIZ
------------------------------------------------------- */
function shareLinksBIZ($links, $api, $foldername, $username = "", $password = "")
{
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, "http://share-links.biz/api/insert");
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_POST, 1);
    curl_setopt($ch2, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0");
    curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
    $optionen = array(
        'apikey'     => $api,
        'folderName' => $foldername,
        'links'      => $links,
        'captcha'    => 1,
        'c_web'      => 1,
        'c_dlc'      => 1,
        'c_cnl'      => 1,
        'c_ccf'      => 1,
        'c_rsdf'     => 1,
        'comment'    => "Hashcode: $foldername\n",
    );
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $optionen);
    $rueckgabe = explode(';', curl_exec($ch2));
    curl_close($ch2);
    $sl_link = str_replace("URL: ", "", $rueckgabe[0]);

    /* WENN LOGIN ANGEGEBEN WURDE, STATUS ABFRAGEN
    ------------------------------------------------------- */
    if (isset($username) && !empty($username) && isset($password) && !empty($password)) {
        $loginUrl     = 'http://share-links.biz/login';
        $sl_unique_id = str_replace("http://share-links.biz/_", "", $sl_link);
        $ch           = curl_init();
        curl_setopt($ch, CURLOPT_URL, $loginUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0");
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'remember_me=1&user=' . $username . '&pass=' . $password . '&submit=Login');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $store = curl_exec($ch);
        curl_setopt($ch, CURLOPT_URL, 'http://share-links.biz/manage?search=' . $sl_unique_id); // Search by unique-id
        $content = curl_exec($ch);

        /* REALE ORDNER-ID FILTERN
        ------------------------------------------------------- */
        preg_match('/<input type="checkbox" name="chkFolder\[\]" value="(\d+)" class="chkFolder vtext-middle" \/>/', $content, $match);
        if (!isset($match[1])) {
            die('could not extract real folder id');
        }

        curl_setopt($ch, CURLOPT_URL, 'http://share-links.biz/manage');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'op'          => 'stimg_png',
            'chkFolder[]' => $match[1],
        ));
        $content = curl_exec($ch);
        preg_match('/(http:\/\/stats\.share-links\.biz\/[0-9a-z]+\.png)/i', $content, $link);
        if (!isset($link[1])) {
            die('could not extract status image link');
        }

        $status_image = $link[1];
    }

    /* WENN KEIN LOGIN ANGEGEBEN WURDE, EIN STATISCHES BILD AUSGEBEN
    ------------------------------------------------------- */
    else {
        $status_image = 'http://share-links.biz/template/images/download/status/online_s.gif';
    }

    $sl_simg  = $status_image;
    $sl_array = array(
        $sl_link,
        $sl_simg,
    );
    //curl_close($ch);
    return ($sl_array);
}

/* CRYPTER: NCRYPT.IN
------------------------------------------------------- */
function nCryptIN($links, $api, $foldername)
{

    $links = array(
        $links,
    );

    // POST DATA

    $postdata = array(
        'auth_code'      => $api,
        'foldername'     => $foldername,
        'captcha'        => '4',
        'show_container' => '1',
        'dlc'            => '1',
        'cnl'            => '1',
        'ccf'            => '1',
        'rsdf'           => '1',
        'links'          => implode("\n", $links),
    );
    $ch = curl_init('http://ncrypt.in/api.php');

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

    $result                = curl_exec($ch);
    list($folder, $status) = explode("\n", $result);

    $nc_link  = $folder;
    $nc_simg  = $status;
    $nc_array = array(
        $nc_link,
        $nc_simg,
    );
    return ($nc_array);
}

/* CRYPTER: NCRYPT.IN
------------------------------------------------------- */
function reLink($links, $api, $foldername)
{

    $explode = explode(PHP_EOL, $links);
    $implode = implode(';', $explode);
    $trim    = trim($implode, ';');

    $your_mirrors = array(
        0 => $trim,
    );
    $backup_links = array();
    $postdata     = array(
        "api"                 => $api,
        "url"                 => $your_mirrors,
        "title"               => $foldername,
        "comment"             => "",
        "captcha"             => "yes",
        "password"            => "",
        "password_redirect"   => "",
        "web"                 => "yes",
        "dlc"                 => "yes",
        "cnl"                 => "yes",
        "password_zip"        => "",
        "password_zip_public" => "",
        "backup_links"        => $backup_links,
    );
    $decode = urldecode(http_build_query($postdata));
    $curl   = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_URL, "http://relink.to/api/api.php");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $decode); //Setting post data as xml
    $json_out  = curl_exec($curl);
    $container = json_decode($json_out);
    $rl_link   = $container->{'message'};
    $status    = str_replace("/f/", "/st/", $rl_link);
    $rl_simg   = $status . ".png";
    $rl_array  = array(
        $rl_link,
        $rl_simg,
    );
    return ($rl_array);
}

/* CRYPTERNAME ERKENNEN (BIT-TEMPLATE)
------------------------------------------------------- */
function crypter_name($crypter)
{
    preg_match_all('/(www\.)?(share-links|filecrypt|relink|ncrypt)\.(biz|cc|to|us|in?)/', $crypter, $matches, PREG_SET_ORDER, 0);
    if ($matches[0][0] == "www.filecrypt.cc") {
        $cryptername = "FileCrypt.cc";
    } elseif ($matches[0][0] == "share-links.biz") {
        $cryptername = "Share-Links.biz";
    } elseif ($matches[0][0] == "relink.us") {
        $cryptername = "Relink.us";
    } elseif ($matches[0][0] == "relink.to") {
        $cryptername = "Relink.to";
    } elseif ($matches[0][0] == "ncrypt.in") {
        $cryptername = "nCrypt.in";
    }

    $out = $cryptername;
    return $cryptername;
}

/* SELECT BOXEN
------------------------------------------------------- */
function lang_unit_select()
{
    global $lang, $_COOKIE;
    $lang_unit_selected = "";
    foreach ($lang['size_unit_select'] as $key => $val) {
        if (isset($_COOKIE['ssbg_game_unit']) && $_COOKIE['ssbg_game_unit'] == $key) {
            $select_cookie = 'selected';
        } else {
            $select_cookie = '';
        }

        $lang_unit_selected .= "<option value=\"$key\" $select_cookie>" . $val . "</option>";
    }

    return $lang_unit_selected;
}

function lang_language_select()
{
    global $lang, $_COOKIE;
    $lang_language_selected = "";
    foreach ($lang['language_select'] as $key => $val) {
        if (isset($_COOKIE['ssbg_game_language']) && $_COOKIE['ssbg_game_language'] == $key) {
            $select_cookie = 'selected';
        } else {
            $select_cookie = '';
        }

        $lang_language_selected .= "<option value=\"" . $key . "\" $select_cookie>" . $val . "</option>";
    }

    return $lang_language_selected;
}

function lang_archive_select()
{
    global $lang, $_COOKIE;
    $lang_archive_selected = "";
    foreach ($lang['archive_select'] as $key => $val) {
        if (isset($_COOKIE['ssbg_game_archive']) && $_COOKIE['ssbg_game_archive'] == $key) {
            $select_cookie = 'selected';
        } else {
            $select_cookie = '';
        }

        $lang_archive_selected .= "<option value=\"$key\" $select_cookie>" . $val . "</option>";
    }

    return $lang_archive_selected;
}

/* GITHUB GISTS GENERATED BBCODE
------------------------------------------------------- */
function githubGist($code, $title, $hash, $generator)
{
    global $lang, $timestamp;
    $git_info = "
    Titel     : " . $title . "\n
    Erstell am: " . date("d.m.Y", $timestamp) . " um " . date("H:i", $timestamp) . " Uhr\n
    Generator : Simple Szene BBcode Generator: http://uranjtsu.xyz\n
    Variante  : " . $generator . "\n\n
    ";

    $data = json_encode([
        'description' => $title . ' | Gist erstellt mit Simple Szene BBcode Generator: http://uranjtsu.xyz',
        'public'      => 'false',
        'files'       => [
            '' . $hash . '.txt' => ['content' => $git_info . $code],
        ],
    ]);
    $url = "https://api.github.com/gists";
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
        ['User-Agent: php-curl']
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $gist = json_decode($result, true);
    if ($gist) {
        $gistslink = $gist['html_url'];
    }
    return $gistslink;
}

/* NFO STRIPPER
------------------------------------------------------- */
function nfo_stripper($text)
{
    // NFO Zeichen entfernen
    $text = preg_replace("/[^a-zA-Z0-9öäüÖÄÜß\\-_?!&[\\]().,;:+=#*~@\\/\\\\'\"><\\s]/", "", $text);
    $text = preg_replace('/([^\x21-\x7E\xA9\xAE\r\n\s])+/', '', $text);

    // <br>,<br />, <br > Am Anfang und Ende entfernen
    $text = preg_replace('/^\s*(?:<br\s*\/?>\s*)*/i', '', $text);
    $text = preg_replace('/\s*(?:<br\s*\/?>\s*)*$/i', '', $text);

    $text = preg_replace("/&#[0-9]+;/", "", $text);

    $text = preg_replace('/(\<br \/\>){3,}/', '<br /><br />', $text);

    $text = trim($text);
    return $text;
}

/* TAG FIXER
------------------------------------------------------- */
function tag_fixer($code, $site)
{

    switch ($site) {
        case 'nydus':
            $preg_find = array(
                "/\[B\]NFO:\[\/B\](.+?)\[spoiler\]\[(.+?)\](.+?)\[\/(.+?)\]\[\/spoiler\]/is",
            );
            $preg_replace = array(
                "[B]NFO:[/B]$1[spoiler][nfo]$3[/nfo][/spoiler]",
            );
            $str_find = array(
                "[spoiler]",
                "[/spoiler]",
            );
            $str_replace = array(
                "[hide]",
                "[/hide]",
            );
            break;
    }

    $body = preg_replace($preg_find, $preg_replace, $code);
    $body = str_replace($str_find, $str_replace, $body);
    return $body;
}

/* confirm / whitelist insert post param */
function post_required($required, $post)
{
    if (!is_array($post)) {
        exit('$post must be an array');
    }

    $explode = explode(":", $required);
    $out     = '';
    foreach ($explode as $v) {
        if (!isset($post[$v]) || empty($post[$v])) {
            $out .= $v . " fehlt.\n";
            //exit($v . " fehlt.\n");
            //$out .= $v . " fehlt.\n";
        }
    }
    $exit = (!empty($out) ? exit($out) : '');
    return $exit;
}
