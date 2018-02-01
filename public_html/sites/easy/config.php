<?php
if(file_exists('../../define_db.php')){
    include_once '../../define_db.php';
}
else{
    include_once 'define_db.php';
}

$dbhost = 'localhost';
$dbname = DB_NAME;
$dbuser = DB_USERNAME;
$dbpasswd = DB_PASSWORD;
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    if(!$ssl) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    } else {
        curl_setopt($ch, CURLOPT_CAINFO, 'crawlera-ca.crt'); //required for HTTPS
    }
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

    $first_result = $search_html_base->find($search_result_parameter,0);

    $results = array(
        'url' => '',
        'title' => '',
        'embed' => ''
    );
    if (!empty($first_result)) {
        $first_result_href = trim($first_result->href);
        $first_result_href_parser = explode('!', $first_result_href);
        $first_result_href = $first_result_href_parser[0];

        $search_html_base->clear();
        unset($search_html_base);

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

        $embed_video = 'embed not found';
        if(detect_url($url) == 'http://xvideos.com' || detect_url($url) == 'https://xvideos.com') {
            $inputs = $detail_html_base->find('input[type="text"]');
            if(count($inputs) > 0) {
                foreach ($inputs as $input) {
                    if(!empty($input->value) && strpos($input->value, 'iframe') !== false
                        && strpos($input->value, 'allowfullscreen') !== false
                        && strpos($input->value, '&quot;') !== false) {
                        $iframe_str = $input->value;
                        $iframe_parser = explode('&quot;',$iframe_str);
                        $embed_video = $iframe_parser[1];
                        break;
                    }
                }
            }
        } else {
            $embed_video = $detail_html_base->find($video_parameter, 0);
            if (!empty($embed_video)) {
                $embed_video = trim($embed_video->src);
            }
        }

        if (substr($embed_video, 0, 1) == '/' && substr($embed_video, 1, 1) != '/') {
            $embed_video = $url . '' . $embed_video;
        }
        $detail_html_base->clear();
        unset($detail_html_base);
        if($item_title != 'title not found'
            && $embed_video != 'embed not found'
            && (strpos(strtolower($item_title), strtolower($dvdcode)) !== false
                ||strpos(strtolower($detail_url), strtolower($dvdcode)) !== false)
        ){
            $results = array(
                'url' => $detail_url,
                'title' => $item_title,
                'embed' => $embed_video
            );
        }
    }else {
        $search_html_base->clear();
        unset($search_html_base);
    }
    return $results;
}

function get_parent_class_str($node, $top=2, $current=true) {
    $parent = $node->parent;
    $result = '';
    if($top > 1) {
        $result .= get_parent_class_str($parent, $top-1, false).' ';
    }
    if(!empty($parent->class)) {
        $classes = explode(' ',$parent->class);
        $result .= '.'.$classes[0];
    }
    if($result == '') {
        $result = $parent->tag;
    }
    if($current) {
        $result .= ' '.$node->tag;
        if(!empty($node->class)) {
            $classes = explode(' ',$node->class);
            $result .= '.'.$classes[0];
        }
    }
    return trim($result);
}

function detect_url($url) {
    
    if(strpos($url, "http")!==0){
        $url="http://$url";
    }
    if(!filter_var($url, FILTER_VALIDATE_URL)){
        return 0;
    }
    return $url;
//    $url_parsers = explode('/',$url);
//    if(($url_parsers[0] != 'http:' && $url_parsers[0] != 'https:')
//        || !isset($url_parsers[1]) || $url_parsers[1] != ''
//        || !isset($url_parsers[2]) || $url_parsers[2] == '') {
//        return 0;
//    }
//    $main = $url_parsers[2];
//    $main_parser = explode('.',$main);
//    if(count($main_parser)<2) {
//        return 0;
//    }
//    return $url_parsers[0].'//'.$main_parser[count($main_parser)-2].'.'.$main_parser[count($main_parser)-1];
}

function find_parent($node, $tag) {
    $parent = $node->parent;
    if($parent->tag == 'html') {
        return 0;
    }
    if($parent->tag != $tag) {
        return find_parent($parent, $tag);
    }
    return $parent;
}

function find_detail_parameter(&$url) {
    $result = array(
        'search_parameter' => '',
        'detail_url' => '',
        'detail_parameter' => ''
    );
    $main_url = detect_url($url);

    if (strpos($url, "http") !== 0) {
        $url = "https://$url";
    } else {
        $url = str_replace("http://", "https://", $url);
    }
    $html = curl_get_content($url);
    if (trim($html) == '') {
        $url=str_replace("https://", "http://", $url);
        $html = curl_get_content($url);
    }
//    if (trim($html) == '') {
//        if(strpos($url, "http")!==0){
//            $url="https://$url";
//        }
//        else{
//            $url=str_replace("http://", "https://", $url);
//        }
//        $html = file_get_contents($url);
//    }
    $html_base = new simple_html_dom();
    $html_base->load($html);

    if(empty($html_base)) {
        $html_base->clear();
        unset($html_base);
        return 0;
    }

    if(strpos($main_url, 'javhub.net') !== false) {
        $result['search_parameter'] = '/search/[dvdcode]';
    } elseif(strpos($main_url, 'javdoe.com') !== false) {
        $result['search_parameter'] = '/search/movie/[dvdcode].html';
    } else {
        $first_input_text = $html_base->find('input', 0);
        if (!empty($first_input_text)) {
            $form = find_parent($first_input_text, 'form');
            $action = '/';
            $k = '?';
            if (!empty($form->action)) {
                $action = $form->action;
                if (strpos($action, $main_url) === 0) {
                    $action = str_replace($main_url, '', $action);
                }
                if (strpos($action, '?') !== false) {
                    $k = '&';
                }
            }
            $result['search_parameter'] = $action . $k . $first_input_text->name . '=[dvdcode]';
           
            $temp = $url;
            $temp = str_replace("https://", "", $temp);
            $temp = str_replace("http://", "", $temp);
            $temp = str_replace("www.", "", $temp);
            $result['search_parameter'] = str_replace("https://", "", $result['search_parameter']);
            $result['search_parameter'] = str_replace("http://", "", $result['search_parameter']);
            $result['search_parameter'] = str_replace("//", "", $result['search_parameter']);
            $result['search_parameter'] = str_replace("www.", "", $result['search_parameter']);
            $result['search_parameter'] = str_replace($temp, "", $result['search_parameter']);
        }
    }

    $as = $html_base->find('a');
    $data = array();
    $parameters_checked = array();
    $urls_checked = array();
    $temp = $url;
    $temp = str_replace("https://", "", $temp);
    $temp = str_replace("http://", "", $temp);
    $temp = str_replace("www.", "", $temp);
    foreach ($as as $a) {
        
        $href = $a->href;
        if(strpos($href, $main_url) === 0) {
            $href = str_replace($main_url, '', $href);
        }
        $href_parser = explode('?',$href);
        if(count($href_parser) > 1) {
            $href = $href_parser[0];
        }
        if(substr($href, -1) == '/') {
            $href = substr($href, 0,-1);
        }
        $href = str_replace("https://", "", $href);
        $href = str_replace("http://", "", $href);
        $href = str_replace("//", "", $href);
        $href = str_replace("www.", "", $href);
        $href = str_replace($temp, "", $href);
        $href_parser = explode('/',$href);
        if(count($href_parser) > 2 && strlen($href_parser[2]) > 3) {
            $parameter = $href_parser[1];
            if($href_parser[2] == 'watch') {
                $parameter .= '/watch';
            }
            if(strpos($main_url, 'xvideos.com') !== false) {
                if(substr($parameter,0,5) == 'video') {
                    $parameter = 'video';
                }
            }
            if(in_array($parameter, $parameters_checked)) {
                $data[$parameter]++;
                if($data[$parameter] == 5) {
                    $urls_checked[$parameter] = $href;
                }
            } else {
                $data[$parameter] = 1;
                $parameters_checked[] = $parameter;
                $urls_checked[$parameter] = $href;
            }
        }
    }
    $max = 0;
    foreach ($data as $parameter => $num) {
        if($max<$num) {
            $max = $num;
            $result['detail_parameter'] = '/'.$parameter.'/';
            if(strpos($main_url, 'xvideos.com') !== false) {
                $result['detail_parameter'] = '/'.$parameter;
            }
            $result['detail_url'] = $urls_checked[$parameter];
        }
    }
    $html_base->clear();
    unset($html_base);
    return $result;
}

function find_search_result($url,$dvdcode) {
    $result = '';
    $main_url = detect_url($url);
    if (strpos($url, "http") !== 0) {
        $url = "https://$url";
    } else {
        $url = str_replace("http://", "https://", $url);
    }
    $html = curl_get_content($url);
    if (trim($html) == '') {
        $url=str_replace("https://", "http://", $url);
        $html = curl_get_content($url);
    }
//    if (trim($html) == '') {
//        if(strpos($url, "http")!==0){
//            $url="https://$url";
//        }
//        else{
//            $url=str_replace("http://", "https://", $url);
//        }
//        $html = file_get_contents($url);
//    }

    $html_base = new simple_html_dom();
    $html_base->load($html);

    $as = $html_base->find('a');
    $data = array();
    $parameters_checked = array();
    $tag_checked = array();
    
    $temp = $url;
    $temp = str_replace("https://", "", $temp);
    $temp = str_replace("http://", "", $temp);
    $temp = str_replace("www.", "", $temp);
        
    foreach ($as as $a) {
        $href = $a->href;
        if(strpos($href, $main_url) === 0) {
            $href = str_replace($main_url, '', $href);
        }
        $href_parser = explode('?',$href);
        if(count($href_parser) > 1) {
            $href = $href_parser[0];
        }
        if(substr($href, -1) == '/') {
            $href = substr($href, 0,-1);
        }
        
        $href = str_replace("https://", "", $href);
        $href = str_replace("http://", "", $href);
        $href = str_replace("//", "", $href);
        $href = str_replace("www.", "", $href);
        $href = str_replace($temp, "", $href);
        $href_parser = explode('/',$href);
        if(count($href_parser) > 2 && strlen($href_parser[2]) > 3) {
            $parameter = $href_parser[1];
            if($href_parser[2] == 'watch') {
                $parameter .= '/watch';
            }
            if(strpos($main_url, 'xvideos.com') !== false) {
                if(substr($parameter,0,5) == 'video') {
                    $parameter = 'video';
                }
            }
            if(in_array($parameter, $parameters_checked)) {
                $data[$parameter]++;
                if(strpos($href, $dvdcode) !== false) {
                    $tag_checked[$parameter] = get_parent_class_str($a);
                }
            } else {
                $data[$parameter] = 1;
                $parameters_checked[] = $parameter;
                $tag_checked[$parameter] = get_parent_class_str($a);
            }
        }
    }
    $max = 0;
    foreach ($data as $parameter => $num) {
        if($max<$num) {
            $max = $num;
            $result = $tag_checked[$parameter];
        }
    }

    $html_base->clear();
    unset($html_base);
    return $result;
}

function find_detail($url) {
    $result = array(
        'video_parameter' => '',
        'video_host' => '',
        'product_parameter' => ''
    );
    $main_url = detect_url($url);

    if (strpos($url, "http") !== 0) {
        $url = "https://$url";
    } else {
        $url = str_replace("http://", "https://", $url);
    }
    $html = curl_get_content($url);
    if (trim($html) == '') {
        $url=str_replace("https://", "http://", $url);
        $html = curl_get_content($url);
    }
//    if (trim($html) == '') {
//        if(strpos($url, "http")!==0){
//            $url="https://$url";
//        }
//        else{
//            $url=str_replace("http://", "https://", $url);
//        }
//        $html = file_get_contents($url);
//    }
    

    $html_base = new simple_html_dom();
    $html_base->load($html);

    if(empty($html_base)) {
        $html_base->clear();
        unset($html_base);
        return 0;
    }

    $video_url = '';
    if(strpos($main_url, 'xvideos.com') !== false) {
        $inputs = $html_base->find('input[type="text"]');
        if(count($inputs) > 0) {
            foreach ($inputs as $input) {
                if(!empty($input->value) && strpos($input->value, 'iframe') !== false
                    && strpos($input->value, 'allowfullscreen') !== false
                    && strpos($input->value, '&quot;') !== false) {
                    $iframe_str = $input->value;
                    $iframe_parser = explode('&quot;',$iframe_str);
                    $video_url = $iframe_parser[1];
                    $result['video_parameter'] = get_parent_class_str($input);
                    break;
                }
            }
        }
    } else {
        $iframes = $html_base->find('iframe');
        if(count($iframes) > 0) {
            foreach ($iframes as $iframe) {
                if(!empty($iframe->src)
                    && (!empty($iframe->allowfullscreen) || !empty($iframe->webkitallowfullscreen) || !empty($iframe->mozallowfullscreen))) {
                    $video_url = $iframe->src;
                    $result['video_parameter'] = get_parent_class_str($iframe);
                    break;
                }
            }
        }
        if($video_url == '') {
            $iframes = $html_base->find('embed');
            if(count($iframes) > 0) {
                foreach ($iframes as $iframe) {
                    if(!empty($iframe->src)
                        && (!empty($iframe->allowfullscreen) || !empty($iframe->webkitallowfullscreen) || !empty($iframe->mozallowfullscreen))) {
                        $video_url = $iframe->src;
                        $result['video_parameter'] = get_parent_class_str($iframe);
                        break;
                    }
                }
            }
        }
    }

    if($video_url != '') {
        if(strpos($video_url, 'openload') !== false || strpos($video_url, 'embed') !== false) {
            $result['video_host'] = 'openload';
        }
        if(strpos($video_url, 'drive.google') !== false) {
            $result['video_host'] = 'google_drive';
        }
    }

    $title = trim($html_base->find('title',0)->plaintext);

    $body = $html_base->find('body',0);
    $eles = $body->find('p, h2, span, div, li');
    foreach($eles as $e) {
        $innertext = trim($e->plaintext);
        $innertext = rtrim($innertext,'.');
        if(strpos($main_url, 'xvideos.com') !== false) {
            $parser = explode(' ',$innertext);
            if(count($parser)> 3) {
                unset($parser[count($parser) - 1]);
                unset($parser[count($parser) - 1]);
            }
            $innertext = implode(' ',$parser);
        }
        if(!empty($innertext)) {
            if (strpos($title, $innertext) === 0 && strlen($innertext) > 7) {
                $result['product_parameter'] = get_parent_class_str($e);
                break;
            }
        }
    }

    $html_base->clear();
    unset($html_base);
    return $result;
}