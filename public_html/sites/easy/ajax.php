<?php
include_once 'config.php';

@set_time_limit(0);
@ini_set('max_execution_time', 0);
@error_reporting (E_ALL);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL);
@ini_set("auto_detect_line_endings", true);

require_once 'simple_html_dom.php';
if(!empty($_POST['action'])) {
    if ($_POST['action'] == 'test_site') {
        $data = array(
            'url' => '',
            'title' => '',
            'embed' => ''
        );
        if ($_POST['dvdcode'] == ''
            || $_POST['url'] == ''
            || $_POST['search_parameter'] == ''
            || $_POST['search_result_parameter'] == ''
            || $_POST['product_parameter'] == ''
            || $_POST['video_parameter'] == '') {
        } else {
            $data = get_video($_POST['dvdcode'], $_POST['url'], $_POST['search_parameter'], $_POST['search_result_parameter'], $_POST['product_parameter'], $_POST['video_parameter']);
        }
        exit(json_encode($data));
    }

    if ($_POST['action'] == 'easy_add') {
        $data = easy_add($_POST['url']);
        exit(json_encode($data));
    }
}

if(isset($_GET['action'])&&$_GET['action']=='easy_add') {
    $data = easy_add($_GET['url']);
    exit(json_encode($data));
}

function easy_add($input_url) {
    $data = array(
        'message' => '',
        'url' => '',
        'search_parameter' => '',
        'search_result_parameter' => '',
        'detail_parameter' => '',
        'video_parameter' => '',
        'video_host' => '',
        'product_parameter' => '',
    );

    if (strpos($input_url, "javlibrary.com") !== FALSE) {
        $input_url= rtrim($input_url,'/');
        $input_url.='/en/';
    }
    $url = detect_url($input_url);
    if($url === 0) {
        $data['message'] = 'Site url is wrong';
        exit(json_encode($data));
    }

    $details = find_detail_parameter($url);
    if($details === 0) {
        $details = find_detail_parameter($input_url);
        if($details === 0) {
            $data['message'] = 'Detail parameter not found';
            exit(json_encode($data));
        }
    }
    $data['url'] = $url;
    $data['search_parameter'] = $details['search_parameter'];
    if(trim($data['search_parameter'])==''){
        $data['search_result_parameter'] = '';
    }
    else{
        if (strpos($input_url, "withjav.com") !== false) {
            $dvdcode = 'a';
        }
        else{
            $dvdcode = 'maria';
        }
        
        $search_url = $url.''.str_replace('[dvdcode]',$dvdcode,$data['search_parameter']);
        $data['search_result_parameter'] = find_search_result($search_url, $dvdcode);

        if(trim($data['search_result_parameter'])==''){
            $search_url = $url.''.str_replace('[dvdcode]',$dvdcode,str_replace(".php?q=", "/", $data['search_parameter']));
            $data['search_result_parameter'] = find_search_result($search_url, $dvdcode);
            if(trim($data['search_result_parameter'])!=''){
                $data['search_parameter']= str_replace(".php?q=", "/", $data['search_parameter']);
            }
        }
        if(trim($data['search_result_parameter'])==''){
            $data['search_result_parameter'] = find_search_result1($url);
        }
    }
    

    $data['detail_parameter'] = $details['detail_parameter'];
    $detail_url = $url.''.$details['detail_url'];

    $details = find_detail($detail_url);
    if($details !== 0) {
        $data['video_parameter'] = $details['video_parameter'];
        $data['video_host'] = $details['video_host'];
        $data['product_parameter'] = $details['product_parameter'];
    }

    return $data;
}