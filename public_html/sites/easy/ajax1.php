<?php

include 'config.php';

@set_time_limit(0);
@ini_set('max_execution_time', 0);
@error_reporting(E_ALL);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL);
@ini_set("auto_detect_line_endings", true);

require_once 'simple_html_dom.php';


if (isset($_POST['action'])) {


    if ($_POST['action'] == 'findvideos' && isset($_POST['dvdcode']) && trim($_POST['dvdcode']) != '') {

        $search_data = array();
        if(isset($_POST['site_id']) && ctype_digit(trim($_POST['site_id']))){
            $where="where id='".trim($_POST['site_id'])."'";
        }
        else{
            $where="";
        }
        $results = $mysqli->query("SELECT * FROM sites $where");
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_array()) {
                $video = get_video($_POST['dvdcode'], $row['url'], $row['search_parameter'], $row['search_result_parameter'], $row['product_parameter'], $row['video_parameter']);
                $row['real_url'] = $video['url'];
                $row['real_title'] = $video['title'];
                $row['real_host'] = $video['embed'];
                $row['status'] = ($row['real_url'] == '') ? 0 : 1;
                $search_data[] = $row;
            }
        }
        $html='';
        foreach ($search_data as $row) {
            $html.='<tr class="'.trim($_POST['dvdcode']).'" id="site_'.$row['id'].'" data-video_host="'.$row['video_host'].'" data-name="'. $row['name'].'" data-url="'.$row['url'].'"
                    data-search_parameter="'.$row['search_parameter'].'" data-detail_parameter="'.$row['detail_parameter'].'" data-product_parameter="'.$row['product_parameter'].'"
                    data-real_url="'.$row['real_url'].'" data-real_title="'.$row['real_title'].'" data-real_host="'.$row['real_host'].'"
                    data-video_parameter="'.$row['video_parameter'].'" data-search_result_parameter="'.$row['search_result_parameter'].'">
                    <td id="site_'.$row['id'].'_name" style="word-break: break-word;">'.$row['name'].'</td>
                    <td id="site_'.$row['id'].'_url" style="word-break: break-word;">'.$row['url'].'</td>
                    <td id="site_'.$row['id'].'_real_url" style="word-break: break-word;">'.$row['real_url'].'</td>
                    <td id="site_'.$row['id'].'_real_title" style="word-break: break-word;">'.$row['real_title'].'</td>
                    <td id="site_'.$row['id'].'_real_host" style="word-break: break-word;">'.$row['real_host'].'</td>
                    <td>'.($row['status'] == 1?'<img style="width:80px" src="checked.png">':'<img style="width:80px" src="unchecked.png">').'</td>'.
                    '<td>
                        <div style="display: inline-block;" onclick="edit_site('.$row['id'].')" class="btn">Edit</div>
                    </td>'.
                '</tr>';
        }
        echo $html; exit;
    }
}
