<?php

$dbhost = 'localhost';
$dbname = 'admin_prontv';
//$dbname = 'admin_prontv_test';
$dbuser = 'root';
//$dbpasswd = 'JZ5hNjM$@7zh';
$dbpasswd = '';
$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
$mysqli->query('SET NAMES utf8mb4;');

function curl_get_content($url, $ssl = false, $count = 1, $via_proxy = true) {
    $headers = array();
    $headers[] = "Accept-Encoding: gzip, deflate";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "Upgrade-Insecure-Requests: 1";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
    $headers[] = "Cache-Control: max-age=0";
    $headers[] = "Connection: keep-alive";

    $ch = curl_init();

    if ($via_proxy) {
        if(file_exists("proxies.txt")){
            $f_contents = file("proxies.txt");
            $line = trim($f_contents[rand(0, count($f_contents) - 1)]);
        }
        else{
            $line='199.115.116.233:1040';
        }
        
        curl_setopt($ch, CURLOPT_PROXY, 'http://' . $line);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'galvin24x7:egor99');
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_CAINFO, 'crawlera-ca.crt'); //required for HTTPS
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $content = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status != 200 && $via_proxy) {
        if ($count < 5) {
            $count++;
            return curl_get_content($url, $ssl, $count, $via_proxy);
        }
    }
    return $content;
}

function get_video($dvdcode = 'rctd-034', $url, $search_parameter, $search_result_parameter, $product_parameter, $video_parameter) {

    if (strpos($url, 'javdoe.com') !== false) {
        $dvdcode = strtolower($dvdcode);
    }
    $search_url = $url . str_replace('[dvdcode]', $dvdcode, $search_parameter);

    $search_html = curl_get_content($search_url);


    $search_html_base = new simple_html_dom();
    $search_html_base->load($search_html);

    $search_items = $search_html_base->find($search_result_parameter);

    $search_html_base->clear();
    unset($search_html_base);

    $results = array();
    if (count($search_items) > 0) {
        for ($i = 0; $i < count($search_items); $i++) {
            $first_result = $search_items[$i];
            $first_result_href = trim($first_result->href);
            $first_result_href_parser = explode('!', $first_result_href);
            $first_result_href = $first_result_href_parser[0];

            $detail_url = $url . '' . $first_result_href;
            if (strpos($first_result_href, $url) === 0) {
                $detail_url = $url;
            }

            $detail_html = curl_get_content($detail_url);
            $detail_html_base = new simple_html_dom();
            $detail_html_base->load($detail_html);

            $item_title = $detail_html_base->find($product_parameter, 0);
            if (!empty($item_title)) {
                $item_title = trim($item_title->plaintext);
            } else {
                $item_title = 'title not found';
            }
            

            if (strpos($url, 'javdoe.com') !== false) {
                $embed_video = $detail_html_base->find('textarea.select-all', 0);
                if (!empty($embed_video)) {
                    $embed_video_text = trim($embed_video->innertext);
                    $tmp_parser = explode('src="', $embed_video_text);
                    $embed_video_text = $tmp_parser[1];
                    $tmp_parser = explode('"', $embed_video_text);
                    $embed_video = $tmp_parser[0];
                } else {
                    $embed_video = 'embed not found';
                }
            } else {
                $embed_video = $detail_html_base->find($video_parameter, 0);
                if (!empty($embed_video)) {
                    $embed_video = trim($embed_video->src);
                } else {
                    $embed_video = 'embed not found';
                }
            }
            if (substr($embed_video, 0, 1) == '/' && substr($embed_video, 1, 1) != '/') {
                $embed_video = $url . '' . $embed_video;
            }
            
            $detail_html_base->clear();
            unset($detail_html_base);
            
            if($item_title != 'title not found'&&$embed_video != 'embed not found'&&(strpos(strtolower($item_title), strtolower($dvdcode))!==FALSE||strpos(strtolower($detail_url), strtolower($dvdcode))!==FALSE)){
                $results[] = array(
                    'url' => $detail_url,
                    'title' => $item_title,
                    'embed' => $embed_video
                );

                break;
            }
            
        }
    }
    if (count($results)==0) {
        return false;
    }
    return $results;
}
