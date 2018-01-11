<?php
include 'config.php';

@set_time_limit(0);
@ini_set('max_execution_time', 0);
@error_reporting (E_ALL);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL);
@ini_set("auto_detect_line_endings", true);

require_once 'simple_html_dom.php';
$data = array(
    'url' => '',
    'title' => '',
    'embed' => ''
);
if($_POST['dvdcode'] == ''
    || $_POST['url'] == ''
    || $_POST['search_parameter'] == ''
    || $_POST['search_result_parameter'] == ''
    || $_POST['product_parameter'] == ''
    || $_POST['video_parameter'] == '') {
    exit(json_encode($data));
}

$video = get_video($_POST['dvdcode'], $_POST['url'], $_POST['search_parameter'], $_POST['search_result_parameter'], $_POST['product_parameter'], $_POST['video_parameter']);
if ($video === false) {
    $video = $data;
}
exit(json_encode($video));