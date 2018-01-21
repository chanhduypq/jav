<?php

if(file_exists('../define_db.php')){
    include_once '../define_db.php';
}
else{
    include_once 'define_db.php';
}

// javfind
class Find {
    /* config db */

    public $dbhost = 'localhost';
    public $dbname = DB_NAME;
    public $dbuser = DB_USERNAME;
    public $dbpasswd = DB_PASSWORD;
    public $proxyAuth = 'galvin24x7:egor99';
    public $via_proxy = false;
    public $file_check = __DIR__ . '/lock.txt';
    public $cron_file_check = __DIR__ . '/cron_lock.txt';
    public $createdAt;
    public $mysqli;

    public function __construct() {
        // DB
        $this->mysqli = new mysqli($this->dbhost, $this->dbuser, $this->dbpasswd, $this->dbname);
        $this->mysqli->query('SET NAMES utf8;');

        $this->createdAt = date('Y-m-d H:i:s');
    }

    public function getSiteNum($code_id) {

        $sql = "SELECT COUNT(DISTINCT(`link`)) FROM videos WHERE `code_id` = '{$code_id}' ";
        $result = $this->mysqli->query($sql);
        $count = $result->fetch_row();

        if (isset($count[0]) && $count[0] >= 1)
            return $count[0];
        else
            return 0;
    }

    public function getSourceNum($code_id) {

        $sql = "SELECT COUNT(DISTINCT(`source`)) FROM videos WHERE `source`<>'' and `code_id` = '{$code_id}' ";
        $result = $this->mysqli->query($sql);
        $count = $result->fetch_row();

        if (isset($count[0]) && $count[0] >= 1)
            return $count[0];
        else
            return 0;
    }

    public function getFirstDate($code_id) {

        $result_date = '';
        $sql = "SELECT  `date` FROM `videos` WHERE `code_id` = '{$code_id}' and `date` is not null ORDER BY `date` ASC LIMIT 0,1 ";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $result_date = $row['date'];
            break;
        }
        return $result_date;
    }

    public function getLatestDate($code_id) {

        $result_date = '';
        $sql = "SELECT  `date` FROM `videos` WHERE `code_id` = '{$code_id}' and `date` is not null ORDER BY `date` DESC LIMIT 0,1 ";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $result_date = $row['date'];
            break;
        }
        return $result_date;
    }

    public function renCodeHtml($codeId = null) {
        if ($codeId !== null && ctype_digit($codeId)) {
            $where = 'where id=' . $codeId;
        } else {
            $where = '';
        }
        $html = '';
        $sql = "SELECT * FROM codes $where ORDER BY id ";
        $result = $this->mysqli->query($sql);
        $num = 0;
        while ($row = $result->fetch_assoc()) {

            $site_num = $this->getSiteNum($row['id']);
            $source_num = $this->getSourceNum($row['id']);
            $first_date = $this->getFirstDate($row['id']);
            $latest_date = $this->getLatestDate($row['id']);

            $num++;
            $html .= '<tr>';
            $html .= '<td>' . $num . '</td>';
            $html .= '<td>' . $row['value'] . '</td>';
            if ($site_num == 0) {
                $html .= '<td>' . $site_num . '</td>';
            } else {
                $html .= '<td><a href="#" class="site_detail" data-id="' . $row['id'] . '" >' . $site_num . '</a></td>';
            }
            if ($source_num == 0) {
                $html .= '<td>' . $source_num . '</td>';
            } else {
                $html .= '<td><a href="#" class="source_detail" data-id="' . $row['id'] . '" >' . $source_num . '</a></td>';
            }
            $html .= '<td>' . $first_date . '</td>';
            $html .= '<td>' . $latest_date . '</td>';
            $html .= '<td><a class="btn btn-danger delete" data-id="' . $row['id'] . '" href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a></td>';
            $html .= '</tr>';
        }

        return $html;
    }

    public function showSitesDetails($code_id) {

        $sql = "SELECT DISTINCT `link`,`host` FROM videos WHERE`code_id` = '{$code_id}' AND `link` <> '' ORDER BY `link` ASC ";
        $result = $this->mysqli->query($sql);
        $num = 0;
        $html = '<table style="width: 100%;"><tr><td style="width: 70%;word-break: break-all;">&nbsp;</td><td style="width: 15%;">Database Search</td><td style="width: 15%;">Instant Search</td></tr>';
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr><td style="width: 70%;word-break: break-all;">' . '<a href="' . $row['link'] . '" target="_blank" class="list-group-item">' . $row['link'] . '</a></td>';
            if (trim($row['host']) != '') {
                $html .= '<td style="width: 15%;">X</td>';
            } else {
                $html .= '<td style="width: 15%;">&nbsp;</td>';
            }
            if (trim($row['host']) == '') {
                $html .= '<td style="width: 15%;">X</td>';
            } else {
                $html .= '<td style="width: 15%;">&nbsp;</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return array('status' => 1, 'html' => $html);
    }

    public function showSourceDetails($code_id) {

        $sql = "SELECT DISTINCT `source`,`host` FROM videos WHERE`code_id` = '{$code_id}' AND `source` <> '' ORDER BY `source` ASC ";
        $result = $this->mysqli->query($sql);
        $html = '<table style="width: 100%;"><tr><td style="width: 70%;word-break: break-all;">&nbsp;</td><td style="width: 15%;">Database Search</td><td style="width: 15%;">Instant Search</td></tr>';
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr><td style="width: 70%;word-break: break-all;">' . '<a href="' . $row['source'] . '" target="_blank" class="list-group-item">' . $row['source'] . '</a></td>';
            if (trim($row['host']) != '') {
                $html .= '<td style="width: 15%;">X</td>';
            } else {
                $html .= '<td style="width: 15%;">&nbsp;</td>';
            }
            if (trim($row['host']) == '') {
                $html .= '<td style="width: 15%;">X</td>';
            } else {
                $html .= '<td style="width: 15%;">&nbsp;</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return array('status' => 1, 'html' => $html);
    }

    public function getAllTrackCode() {

        $results = array();
        $sql = "SELECT * FROM codes ORDER BY id ";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $results[] = array('id' => $row['id'], 'value' => $row['value']);
        }

        return $results;
    }

    public function updatecodesresults() {

        if (file_exists($this->file_check)) {
            return array('loadding' => 1, 'html' => $this->renCodeHtml());
        } else {
            return array('loadding' => 0, 'html' => $this->renCodeHtml());
        }
    }

    public function renCodeHtmlForHost() {

        $host = array();
        $sql = "SELECT * FROM videos where host<>''";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            if (strpos($row['link'], $row['host']) !== FALSE) {
                $host[$row['host']][] = '1';
            }
        }

        $html = '';
        $sql = "SELECT host,COUNT(*) AS count FROM videos where host<>'' GROUP BY host ";
        $result = $this->mysqli->query($sql);
        $num = 0;
        while ($row = $result->fetch_assoc()) {

            $num++;
            $html .= '<tr>';
            $html .= '<td>' . $num . '</td>';
            $html .= '<td>' . $row['host'] . '</td>';
            $html .= '<td>' . $row['count'] . '</td>';
            if (isset($host[$row['host']])) {
                $html .= '<td>' . count($host[$row['host']]) . '</td>';
            } else {
                $html .= '<td>0</td>';
            }

            $html .= '</tr>';
        }

        return $html;
    }

    public function renCodeHtmlForDomain() {

        $domains = array();

        $sql = "SELECT * FROM videos";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $domains[$row['domain']][$row['host']] = '1';
        }

        $html = '';
        $sql = "SELECT domain,COUNT(*) AS count FROM videos where domain<>'' GROUP BY domain ";
        $result = $this->mysqli->query($sql);
        $num = 0;
        while ($row = $result->fetch_assoc()) {

            $num++;
            $html .= '<tr>';
            $html .= '<td>' . $num . '</td>';
            $html .= '<td>' . $row['domain'] . '</td>';
            if (isset($domains[$row['domain']])) {
                $html .= '<td>' . implode(", ", array_keys($domains[$row['domain']])) . '</td>';
            } else {
                $html .= '<td></td>';
            }

            $html .= '<td>' . $row['count'] . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }

    public function checkCode($value) {

        $sql = "SELECT COUNT(*) FROM codes WHERE  `value` like '{$value}' ";
        $result = $this->mysqli->query($sql);
        $count = $result->fetch_row();

        if (isset($count[0]) && $count[0] >= 1)
            return false;
        else
            return true;
    }

    public function insertCode($code) {

        //sql
        $sql = "INSERT INTO  `codes`( `value`, `createdAt`) 
				VALUES ( 
				'" . $this->mysqli->real_escape_string($code) . "',
				 '" . date('Y-m-d H:i:s') . "');";


        // mysqli_query
        $this->mysqli->query($sql);
    }

    public function addDVDCode($code) {
        //checkCode to insertCode
        if ($this->checkCode($code)) {
            //insertCode
            $this->insertCode($code);
            return array('status' => 1, 'html' => $this->renCodeHtml());
        } else {
            return array('status' => 0);
        }
    }

    public function deleteDVDCode($id) {

        $sql = "DELETE FROM codes WHERE id = '{$id}' ";
        $this->mysqli->query($sql);
        $sql = "DELETE FROM videos WHERE code_id = '{$id}' ";
        $this->mysqli->query($sql);
        return array('status' => 1, 'html' => $this->renCodeHtml());
    }

    public function deleteVideo($code_value) {

        $code_value = trim($code_value);
        $sql = "DELETE FROM videos WHERE code_id='0' and code_value like '$code_value'";
        $this->mysqli->query($sql);
    }

    public function getProxy() {
        return '199.115.116.233:1041';
//		$f_contents = file("proxies.txt");
//		$line = trim($f_contents[rand(0, count($f_contents) - 1)]);
//		return $line;
    }

    public function getCurrentTracker() {

        $result = array('status' => 1, 'html' => $this->renCodeHtml());

        return $result;
    }

    public function getCodeIdByValue($value) {

        $value = str_replace("'", "\'", $value);
        $results = array();
        $sql = "SELECT id FROM codes where like '$value' ";
        $result = $this->mysqli->query($sql);
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }

        return 0;
    }

    public function insertData($data) {

        //convert date
        if (substr_count($data['date'], '.') == 2) {
            $date = DateTime::createFromFormat('d.m.Y', $data['date']);
            $data['date'] = $date->format('Y-m-d');
        }

        $sql = "INSERT INTO  `videos`(`code_id`,`code_value`, `title` , `link` , `host` , `source`, `domain`, `language`, `size`, `quality`, `date` , `createdAt`) 
                                    VALUES ( 
                                    '" . $data['code_id'] . "',
                                    '" . $data['code_value'] . "',
                                    '" . $this->mysqli->real_escape_string($data['title']) . "',
                                    '" . $this->mysqli->real_escape_string($data['link']) . "',
                                    '" . $this->mysqli->real_escape_string($data['host']) . "',
                                    '" . $this->mysqli->real_escape_string($data['source']) . "',
                                    '" . $this->mysqli->real_escape_string($data['domain']) . "',
                                    '" . $this->mysqli->real_escape_string($data['language']) . "',
                                    '" . $this->mysqli->real_escape_string($data['size']) . "',
                                    '" . $this->mysqli->real_escape_string($data['quality']) . "',
                                    '" . $this->mysqli->real_escape_string($data['date']) . "',
                                     '" . $this->createdAt . "');";
        // mysqli_query
        $this->mysqli->query($sql);
    }

    public function checkData($url, $code_id) {

        $sql = "SELECT COUNT(*) FROM videos WHERE  `link` = '{$url}' AND  `code_id` = '{$code_id}'";
        $result = $this->mysqli->query($sql);
        $count = $result->fetch_row();

        if (isset($count[0]) && $count[0] >= 1)
            return false;
        else
            return true;
    }

    public function getBetweenXandY($string, $a, $b) {

        $result = false;
        if (strrpos($string, $a) !== false) {
            $tmp = explode($a, $string);
            if (strrpos($tmp[1], $b) !== false) {
                $tmp = explode($b, $tmp[1]);
                $result = trim($tmp[0]);
            }
        }
        return $result;
    }

    public function renVideosHtml($code_value, $limit = 'All') {
        if (ctype_digit($limit)) {
            $limit = "limit $limit";
        } else {
            $limit = "";
        }
        $html = '';
        $sql = "SELECT * FROM videos where code_id='0' and code_value like '$code_value' ORDER BY createdAt DESC $limit";
        $result = $this->mysqli->query($sql);
        $num = 0;
        while ($row = $result->fetch_assoc()) {
            $num++;
            $html .= '<tr id="' . $row['id'] . '">';
            $html .= '<td>' . $num . '</td>';
            $html .= '<td>' . $row['title'] . '</td>';
            $html .= '<td><a href="' . $row['source'] . '" target="_blank">' . $row['host'] . '</a></td>';
            $html .= '<td><a href="' . $row['link'] . '" target="_blank">' . $row['domain'] . '</a></td>';
            $html .= '<td>' . $row['language'] . '</td>';
            $html .= '<td>' . $row['size'] . '</td>';
            $html .= '<td>' . $row['quality'] . '</td>';
            $html .= '<td>' . $row['date'] . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }

    public function exportAllTocsv() {

        $results = array();
        $results[] = array('Title', 'Link', 'Host', 'Source', 'Domain', 'Language', 'Size', 'Quality', 'Date');
        $sql = "SELECT * FROM videos ORDER BY code_id";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $results[] = array(
                $row['title'],
                $row['link'],
                $row['host'],
                $row['source'],
                $row['domain'],
                $row['language'],
                $row['size'],
                $row['quality'],
                $row['date']);
        }
        return $results;
    }

    public function exportTocsv($ids) {
        if (!is_array($ids) || count($ids) == 0) {
            $ids[] = '-1';
        }
        $results = array();
        $results[] = array('Title', 'Link', 'Host', 'Source', 'Domain', 'Language', 'Size', 'Quality', 'Date');
        $sql = "SELECT * FROM videos where id IN (" . implode(',', $ids) . ")";
        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $results[] = array(
                $row['title'],
                $row['link'],
                $row['host'],
                $row['source'],
                $row['domain'],
                $row['language'],
                $row['size'],
                $row['quality'],
                $row['date']);
        }
        return $results;
    }

    public function foomatVideoSize($size) {

        if ($size != '' && $size != 0) {

            $size = (int) ($size / 1024);
            $slug = " MB";
            if ($size > 0) {
                $size = round(($size / 1024), 2);
            }
            if ($size / 1024 > 1) {
                $slug = " GB";
                $size = round(($size / 1024), 2);
            }
            $size .= $slug;
        }
        if ($size == 0) {
            $size = '';
        }

        return $size;
    }

    public function getCurrentVideos($code, $number_result) {
        $html = $this->renVideosHtml($code, $number_result);
        if (trim($html) != '') {
            $status = 1;
        } else {
            $status = 0;
        }
        return array('status' => $status, 'html' => $html);
    }

}
