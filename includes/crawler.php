<?php
function multiexplode($delimiters, $input)
{
    $ready  = str_replace($delimiters, $delimiters[0], $input);
    $launch = explode($delimiters[0], $ready);
    return $launch;
}

/**
 * file_get_contents ersatzfunktion, benötigt curl
 *
 * @param string $url
 * @return string
 */

function get_data($url)
{
    /* do some curl magic */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function getHTMLByID($id, $url)
{
    $html = file_get_contents($url);
    $dom  = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $node = $dom->getElementById($id);
    if ($node) {
        return $dom->saveXML($node);
    }
    return false;
}

function googleImdbByRlsName($releasename)
{
    /* try to clean releasename with multiple split-keywords */
    $exploded = multiexplode(array(
        ".koeln",
        ".stuttgart",
        ".5113",
        ".muenchen",
        ".leipzig",
        ".wismar",
        "S0",
        "S1",
        "S2",
        "S3",
        "E0",
        "E1",
        "E2",
        "E3",
        "E4",
        "E5",
        "E6",
        "E7",
        "E8",
        "E9",
        "german",
        "dvdrip",
        "bdrip",
        "hdtv",
        "hdtvrip",
        "doku",
        "1080p",
        "720p",
        "complete",
        "WebHD",
        "x264",
        "-CiHD",
    ), strtolower(trim(htmlspecialchars($releasename))));
    /* remove specialchars and replace . with + signs which is google friendly */
    $cleanStr = str_replace(".", "+", preg_replace("/[^0-9a-zA-Z.]/", "", $exploded[0]));
    /* fetch complete first page from google */
    $google_data = get_data('https://www.google.de/search?q=' . $cleanStr . '+imdb');
    /* extract imdb id with a regex */
    $google_imdb = get_match('/tt\\d{7}/is', $google_data, 0);
    /* return our result */
    return $google_imdb;
}

function xrelByReleasename($releasename)
{
    /* define some standard variables */
    $conf['xrel_url']  = 'https://www.xrel.to';
    $conf['xrel_qurl'] = 'https://www.xrel.to/search.html?mode=full&xrel_search_query=';
    /* grab some data from xrel-nfo page */
    $xrel_content      = get_data($conf['xrel_qurl'] . $releasename);
    $xrel['release']   = htmlentities($releasename, ENT_QUOTES);
    $xrel['size']      = @get_match('!<div class="l_left"> Release-Größe: </div>.<div class="l_right">(.+)</div>!iUm', $xrel_content);
    $xrel['vstream']   = @get_match('!<div class="l_left"> Video-Stream: </div>.<div class="l_right">(.+)</div>!iUm', $xrel_content);
    $xrel['astream']   = @get_match('!<div class="l_left"> Audio-Stream: </div>.<div class="l_right">(.+)</div>!iUm', $xrel_content);
    $xrel['group']     = strip_tags(@get_match('!<div class="l_left"> Release-Group: </div>.<div class="l_right">(.+)</div>!iUm', $xrel_content));
    $xrel['info_page'] = $conf['xrel_url'] . @get_match('!<div class="article">.<p class="article_text" style="width:690px">(.+)<a href="(.+)">weiter...</a></p>!iUm', $xrel_content, 2);
    /* grab some data from xrel-INFO page */
    $xrel_content        = @get_data($xrel['info_page']);
    $xrel['poster']      = $conf['xrel_url'] . @get_match('!<div id="poster" style="line-height:0;"><div>.*<img src="(.*)"!iUm', $xrel_content);
    $xrel['kinode']      = @get_match('/<div class="box_list2_right" style="text-align:right;"> <a href="(.+)" title="(.+)">Kino\.de<\/a> <\/div> <div class="clear"><\/div>/i', $xrel_content, 2);
    $xrel['imdb_link']   = @get_match('!<div class="box_list2_right" style="text-align:right;"> <a href="(.+)" title="(.+)">IMDb.com</a>!iUm', $xrel_content, 2);
    $xrel['imdb_id']     = get_match('/tt\\d{7}/is', $xrel['imdb_link'], 0);
    $xrel['plot']        = @get_match('/<div class="article_text" style="margin\:0\;">(.*?)<\/div>/s', $xrel_content);
    $xrel['description'] = preg_replace('/<table class="bb_table">.*<\/table>/s', '', $xrel['plot']);
    $xrel['genre']       = @get_match('!<div class="l_left">Genre:</div>.<div class="l_right">(.+)</div>!iUm', $xrel_content);
    /* return our result array which is hopefully fullfilled */
    $array = array(
        $xrel['release'],
        $xrel['size'],
        $xrel['vstream'],
        $xrel['astream'],
        $xrel['group'],
        $xrel['kinode'],
        $xrel['imdb_link'],
        $xrel['imdb_id'],
        $xrel['description'],
        $xrel['genre'],
        $xrel['poster'],
    );
    return ($array);
}

function xrelnfo_p2p($releasename)
{
    /* define some standard variables */
    $conf['xrel_url']  = 'https://www.xrel.to';
    $conf['xrel_qurl'] = 'https://www.xrel.to/search.html?mode=full&xrel_search_query=';
    /* grab some data from xrel-nfo page */
    $xrel_content = get_data($conf['xrel_qurl'] . $releasename);
    $xrel['nfo']  = @get_match('/<div id="nfo_text" style="padding:10px;  display: none;">.<pre>(.+)<\/pre>.<\/div>/s', $xrel_content);
    return $xrel['nfo'];
}

function xrelnfo_scene($releasename)
{

    $conf['xrel_qurl'] = 'https://www.xrel.to/search.html?mode=full&xrel_search_query=';
    /* do some curl magic */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $conf['xrel_qurl'] . $releasename);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_REFERER, "https://www.xrel.to/releases.html");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0");
    $xrel_content = curl_exec($ch);
    curl_close($ch);
    $xrel['nfo'] = @get_match('/<div id="nfo_text" style="padding:10px;  display: none;">.<pre>(.+)<\/pre>.<\/div>/s', $xrel_content);
    return $xrel['nfo'];
}

function tmdbByImdb($imdbid)
{
    global $tmdb_api;
    /* our tmdb.org api-key */
    $apikey = $tmdb_api;
    /* two step verfication for correct imdb-id */
    $imdbid = get_match('/tt\\d{7}/is', $imdbid, 0);
    /* grab some data from tmdb.org api */
    $content          = json_decode(get_data('http://api.themoviedb.org/3/movie/' . $imdbid . '?api_key=' . $apikey));
    $genre            = json_decode(get_data('http://api.themoviedb.org/3/movie/' . $imdbid . '?api_key=' . $apikey), true);
    $tmdb['cover']    = 'http://image.tmdb.org/t/p/original' . $content->poster_path;
    $tmdb['tmdbid']   = $content->id;
    $tmdb['otitle']   = $content->original_title; // mit originaltitel finden sich leichter infos auf google, amazon, etc.
    $tmdb['runtime']  = $content->runtime;
    $tmdb['language'] = $content->original_language;
    foreach ($genre['genres'] as $key => $val) {
        $genre_out .= $val['name'] . ', ';
    }
    $genre_str     = str_replace('Array', '', $genre_out);
    $tmdb['genre'] = substr($genre_str, 0, -2);
    /* return our result array which is hopefully fullfilled */

    $array = array(
        $tmdb['cover'],
        $tmdb['runtime'],
        $tmdb['language'],
        $tmdb['genre'],
    );
    return ($array);
}

function kinode_runtime($url)
{
    $html = file_get_contents($url);
    $dom  = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);

    $xpath    = new DomXPath($dom);
    $nodeList = $xpath->query("//dd[@class='length']");
    $node     = $nodeList->item(0);
    return $node->nodeValue;
}

function kinode_cover($code)
{
    $html = $code;
    $dom  = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $tags = $dom->getElementsByTagName('img');
    foreach ($tags as $tag) {
        $img = $tag->getAttribute('src');
    }
    return $img;
}

function layer13ByRlsname($releasename)
{
    /* define some standard variables */
    $conf['lay13_rlsurl'] = 'https://layer13.net/';
    $conf['lay13_qurl']   = 'https://layer13.net/browse?q=';
    /* search on page with releasename and grab id from details page */
    $lay13_search     = get_data($conf['lay13_qurl'] . $releasename);
    $lay13['rlsid']   = @get_match('/(rls\?id\=[0-9a-z]+)/i', $lay13_search);
    $lay13_search_nfo = get_data($conf['lay13_rlsurl'] . $lay13['rlsid']);
    preg_match('!<pre class=nfo>(.*)</pre>!isUm', $lay13_search_nfo, $lay13_nfo);
    $lay13_nfo = $lay13_nfo[1];
    /* return our result array which is hopefully fullfilled */
    return nfo_stripper($lay13_nfo);
}

function omdb($imdbid)
{
    /* define some standard variables */
    $conf['omdb_qurl'] = 'http://www.omdbapi.com/?i=';
    /* grab some data from xrel-nfo page */
    $omdb_content     = json_decode(get_data($conf['omdb_qurl'] . $imdbid . '&plot=full'));
    $omdb['runtime']  = $omdb_content->Runtime;
    $omdb['genre']    = $omdb_content->Genre;
    $omdb['desc']     = $omdb_content->Plot;
    $omdb['cover']    = $omdb_content->Poster;
    $omdb['language'] = $omdb_content->Language;
    $array            = array(
        $omdb['runtime'],
        $omdb['genre'],
        $omdb['desc'],
        $omdb['cover'],
        $omdb['language'],
    );
    return ($array);
}
