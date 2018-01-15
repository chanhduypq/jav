<?php
require_once 'simple_html_dom.php';
set_time_limit(0);
ini_set ( 'max_execution_time', 1200);
date_default_timezone_set('Asia/Singapore');


// javfind
class javfind{
	
	/* config db */
	public $dbhost = 'localhost';
	public $dbname = 'admin_prontv';
//        public $dbname = 'admin_prontv_test';
	public $dbuser = 'root';
//	public $dbpasswd 	= 'JZ5hNjM$@7zh';
        public $dbpasswd 	= '';
	public $proxyAuth 	= 'galvin24x7:egor99';
	public $apikey = 'a56c1dc1143c8a71ae671bd8726cb0cf';// '43cff1c7c5e5c8cc217774b590aeb60c' '149beff6acf922dd37079795cfeee8c1';
	public $via_proxy 	= true;
	public $file_check 	= __DIR__.'/lock.txt';
	public $cron_file_check = __DIR__.'/cron_lock.txt';
        public $mysqli;
        public $number_result=0;
        public $increase=0;
        public $createdAt;
                function __construct(){
		// DB
		$this->mysqli = new mysqli($this->dbhost, $this->dbuser, $this->dbpasswd, $this->dbname);
		$this->mysqli->query('SET NAMES utf8;');
                
                $this->createdAt=date('Y-m-d H:i:s');
                
	}


        function getCurrentVideos($code,$number_result){
            $html=$this->renVideosHtml($code,$number_result);
            if(trim($html)!=''){
                $status=1;
            }
            else{
                $status=0;
            }
            return array('status'=>$status, 'html'=>$html);
        }

	// @findVideos
	function findVideos($code,$number_result,$code_id=0){
            $this->increase=0;
            $this->number_result=$number_result;

//		file_put_contents($this->file_check, '1');
		$check = false;
		$page_limit = 10;
                if(is_numeric($number_result)){
                    $count="&count=$number_result";
                }
                else{
                    $count="&count=100";
                }
		

		$url = 'http://pron.tv/api/search/stream/?apikey='.$this->apikey.'&query='.trim($code)."$count&from=0";
		$json_string = $this->curl_execute($url);
		$datas = json_decode($json_string,true);
                
		if(isset($datas['status']) && isset($datas['resultcount']) && isset($datas['result']) && $datas['status']=='success' && $datas['resultcount']>0 && is_array($datas['result']) ){
			//getDetails
                    
			if($this->getDetails($datas['result'],$code_id,$code)){
				$check = true;
			}

			// get sub page
			$numpage = (int) ceil($datas['resultcount']/10);
			if($numpage>1){
				for ($i=2; $i <= $numpage ; $i++) { 
					//check run
//					if($code_id==0){
//						if (!file_exists($this->file_check)) break;
//					}
//					else{
//						if (!file_exists($this->cron_file_check)) break;
//					}
					$sub_url = str_replace('&from=0', '&from='.(($page_limit*($i-1))-1), $url);
					$sub_json_string = $this->curl_execute($sub_url);
					$sub_datas = json_decode($sub_json_string,true);
					if(isset($sub_datas['status']) && isset($sub_datas['resultcount']) && isset($sub_datas['result']) && $sub_datas['status']=='success' && $sub_datas['resultcount']>0 && is_array($sub_datas['result']) ){
						//getDetails
						if($this->getDetails($sub_datas['result'],$code_id,$code)){
							$check = true;
						}
					}
					break;//debug
				}
			}
		}
                
		//check
		if($check == true){
			$result = array('status'=>1, 'html'=>$this->renVideosHtml($code,$number_result));
		}
                else{
                    $result = array('status'=>0, 'html'=>'');
                }
                

//		if (file_exists($this->file_check)) {
//			unlink($this->file_check);
//		}

		return $result;
	}
        
        // @findVideos
	function findVideos1($code,$number_result,$code_id=0){
            $this->increase=0;
            $this->number_result=$number_result;

		$check = false;
		$page_limit = 10;
                if(is_numeric($number_result)){
                    $count="&count=$number_result";
                }
                else{
                    $count="&count=100";
                }
		

		$url = 'http://pron.tv/api/search/stream/?apikey='.$this->apikey.'&query='.trim($code)."$count&from=0";
		$json_string = $this->curl_execute($url);
		$datas = json_decode($json_string,true);
                
		if(isset($datas['status']) && isset($datas['resultcount']) && isset($datas['result']) && $datas['status']=='success' && $datas['resultcount']>0 && is_array($datas['result']) ){
			//getDetails
                    
			if($this->getDetails($datas['result'],$code_id,$code)){
				$check = true;
			}

			// get sub page
			$numpage = (int) ceil($datas['resultcount']/10);
			if($numpage>1){
				for ($i=2; $i <= $numpage ; $i++) { 
					
					$sub_url = str_replace('&from=0', '&from='.(($page_limit*($i-1))-1), $url);
					$sub_json_string = $this->curl_execute($sub_url);
					$sub_datas = json_decode($sub_json_string,true);
					if(isset($sub_datas['status']) && isset($sub_datas['resultcount']) && isset($sub_datas['result']) && $sub_datas['status']=='success' && $sub_datas['resultcount']>0 && is_array($sub_datas['result']) ){
						//getDetails
						if($this->getDetails($sub_datas['result'],$code_id,$code)){
							$check = true;
						}
					}
					break;//debug
				}
			}
		}
                
		
	}

	function foomatVideoSize($size){

		if($size!='' && $size!=0){

			$size = (int) ($size/1024);
			$slug = " MB";
			if($size>0){
				$size = round(($size/1024),2);
			}
			if($size/1024>1){
				$slug = " GB";
				$size = round(($size/1024),2);
			}
			$size .=$slug;

		}
		if($size==0){
			$size = '';
		}

		return $size;
	}

	// @getDetails
	function getDetails($datas,$code_id,$code_value){

		$check = false;
		
		foreach ($datas as $video_data) {
			//data
			$data = array(
						'title'=>(isset($video_data['sourcetitle']))?trim($video_data['sourcetitle']):'',
						'code_id'=>$code_id,
                                                'code_value'=>$code_value,
						'link'=>(isset($video_data['sourceurl']))?rtrim(trim($video_data['sourceurl']),'/'):'',
						'host'=>(isset($video_data['hostername']))?trim($video_data['hostername']):'',
						'source'=>'',
						'domain'=>(isset($video_data['sourcename']))?trim($video_data['sourcename']):'',
						'language'=>(isset($video_data['lang']))?trim($video_data['lang']):'',
						'size'=>(isset($video_data['sizeinternal']))?trim($video_data['sizeinternal']):'',
						'quality'=>'',
						'date'=>(isset($video_data['created']))?trim($video_data['created']):'',
						);
			//source
			if(isset($video_data['hosterurls']) && is_array($video_data['hosterurls'])){
				foreach ($video_data['hosterurls'] as $hosterurl) {
					if(isset($hosterurl['url']) ){
						$data['source'] = rtrim(trim($hosterurl['url']),'/');
						break;
					}
				}
			}
			//size
			$data['size'] = $this->foomatVideoSize($data['size']);

			//add to databse
			if($data['title']!='' ){
                                if(!is_numeric($this->number_result)||$this->increase< $this->number_result){
                                    $this->insertData($data);
                                    $check = true;
                                    $this->increase++;
                                }
				
			}
		}

		return $check;
	}

	
	// @exportTocsv
	function exportTocsv_bk($dvd_code,$limit){
            if(ctype_digit($limit)){
                $limit='limit '.$limit;
            }
            else{
                $limit='';
            }
		$results = array();
		$results[]= array('Title','Link','Host','Source','Domain','Language','Size','Quality','Date');
		$sql = "SELECT * FROM videos where code_id='0' and code_value like '$dvd_code' ORDER BY createdAt DESC $limit";
		$result = $this->mysqli->query($sql);
		while ($row = $result->fetch_assoc()) {
			$results[]= array(
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
        
        function exportTocsv($ids){
            if(!is_array($ids)||count($ids)==0){
                $ids[]='-1';
            }
		$results = array();
		$results[]= array('Title','Link','Host','Source','Domain','Language','Size','Quality','Date');
		$sql = "SELECT * FROM videos where id IN (". implode(',', $ids).")";
		$result = $this->mysqli->query($sql);
		while ($row = $result->fetch_assoc()) {
			$results[]= array(
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
        
        function exportAllTocsv(){

		$results = array();
		$results[]= array('Title','Link','Host','Source','Domain','Language','Size','Quality','Date');
		$sql = "SELECT * FROM videos ORDER BY code_id";
		$result = $this->mysqli->query($sql);
		while ($row = $result->fetch_assoc()) {
			$results[]= array(
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

	// @getBetweenXandY
	function getBetweenXandY($string,$a,$b){

		$result = false;
		if(strrpos($string, $a)!==false ){
			$tmp = explode($a, $string);
			if(strrpos($tmp[1], $b)!==false ){
				$tmp = explode($b, $tmp[1]);
				$result = trim($tmp[0]);
			}
		}
		return $result;
	}

	// @renHtml
	function renVideosHtml($code_value,$limit='All'){
                if(ctype_digit($limit)){
                    $limit="limit $limit";
                }
                else{
                    $limit="";
                }
		$html = '';
                $sql = "SELECT * FROM videos where code_id='0' and code_value like '$code_value' ORDER BY createdAt DESC $limit";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {
			$num++;
			$html .= '<tr id="'.$row['id'].'">';
			$html .= '<td>'.$num.'</td>';
			$html .= '<td>'.$row['title'].'</td>';
			$html .= '<td><a href="'.$row['source'].'" target="_blank">'.$row['host'].'</a></td>';
			$html .= '<td><a href="'.$row['link'].'" target="_blank">'.$row['domain'].'</a></td>';
			$html .= '<td>'.$row['language'].'</td>';
			$html .= '<td>'.$row['size'].'</td>';
			$html .= '<td>'.$row['quality'].'</td>';
			$html .= '<td>'.$row['date'].'</td>';
			$html .= '</tr>';
		}

		return $html;
	}
        
        function renVideosHtmlCode0(){

		$html = '';
		$sql = "SELECT * FROM videos WHERE code_id='0' ORDER BY id";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {
			$num++;
			$html .= '<tr id="'.$row['id'].'">';
			$html .= '<td>'.$num.'</td>';
			$html .= '<td>'.$row['title'].'</td>';
			$html .= '<td><a href="'.$row['source'].'" target="_blank">'.$row['host'].'</a></td>';
			$html .= '<td><a href="'.$row['link'].'" target="_blank">'.$row['domain'].'</a></td>';
			$html .= '<td>'.$row['language'].'</td>';
			$html .= '<td>'.$row['size'].'</td>';
			$html .= '<td>'.$row['quality'].'</td>';
			$html .= '<td>'.$row['date'].'</td>';
			$html .= '</tr>';
		}

		return $html;
	}


	// @checkData
	function checkData($url,$code_id){

		$sql = "SELECT COUNT(*) FROM videos WHERE  `link` = '{$url}' AND  `code_id` = '{$code_id}'";
		$result = $this->mysqli->query($sql);
		$count = $result->fetch_row();

		if(isset($count[0]) && $count[0]>=1)
			return false;
		else 
			return true;
	}

	// @insertData
	function insertData($data){

		//convert date
		if(substr_count($data['date'], '.')==2){
			$date = DateTime::createFromFormat('d.m.Y', $data['date']);
			$data['date'] = $date->format('Y-m-d');
		}
                
                $sql = "INSERT INTO  `videos`(`code_id`,`code_value`, `title` , `link` , `host` , `source`, `domain`, `language`, `size`, `quality`, `date` , `createdAt`) 
                                    VALUES ( 
                                    '". $data['code_id']."',
                                    '". $data['code_value']."',
                                    '". $this->mysqli->real_escape_string($data['title'])."',
                                    '". $this->mysqli->real_escape_string($data['link'])."',
                                    '". $this->mysqli->real_escape_string($data['host'])."',
                                    '". $this->mysqli->real_escape_string($data['source'])."',
                                    '". $this->mysqli->real_escape_string($data['domain'])."',
                                    '". $this->mysqli->real_escape_string($data['language'])."',
                                    '". $this->mysqli->real_escape_string($data['size'])."',
                                    '". $this->mysqli->real_escape_string($data['quality'])."',
                                    '". $this->mysqli->real_escape_string($data['date'])."',
                                     '". $this->createdAt."');";
                    // mysqli_query
                    $this->mysqli->query($sql);

		
	}


	// ----------------------------------TRACK---------------------------------

	// @startCronTrackcode
	function startCronTrackcode_bk(){

		file_put_contents($this->cron_file_check, '1');
		$check = false;
		$result = array('status'=>0, 'html'=>'');
		//loop all dvd code
		$codes = $this->getAllTrackCode();
		foreach ($codes as $code) {

			if (!file_exists($this->cron_file_check)) break;

			if($this->findVideos($code['value'], $code['id'])){
				$check = true;
			}
		}
		//unlink
		if (file_exists($this->cron_file_check)) {
			unlink($this->cron_file_check);
		}
		//check
		if($check == true){
			$result = array('status'=>1, 'html'=>$this->renCodeHtml());
		}

		return $result;
	}
        
        function startCronTrackcode($number_result,$database_search,$instant_search){

            $createdAt=date('Y-m-d H:i:s');
            $sites=array();
            $search_data=array();
            if($instant_search=='1'){
                include_once '../sites/config.php';;
                $results = $this->mysqli->query("SELECT * FROM sites");
                if ($results->num_rows > 0) {
                    while ($row = $results->fetch_array()) {
                        $sites[]=$row;
                    }
                }
            }
            
		//loop all dvd code
            $codes = $this->getAllTrackCode();
            foreach ($codes as $code) {
                if($database_search=='1'){
                    $this->findVideos1($code['value'],$number_result, $code['id']);
                }
                if($instant_search=='1'){
                    foreach ($sites as $row){
                        $video = get_video($code['value'], $row['url'], $row['search_parameter'], $row['search_result_parameter'], $row['product_parameter'], $row['video_parameter']);
                        if ($video !== FALSE) {
                            foreach ($video as $temp) {
                                if(count($search_data)<$number_result){
                                    $row['real_url'] = $temp['url'];
                                    $row['real_title'] = $temp['title'];
                                    $row['real_host'] = $temp['embed'];
                                    $row['status'] = 1;
                                    $row['code_id'] = $code['id'];
                                    $row['code_value'] = $code['value'];
                                    $row['host'] = '';
                                    $row['source'] = '';
                                    $row['domain'] = '';
                                    $row['language'] = '';
                                    $row['size'] = '';
                                    $row['quality'] = '';
                                    $row['date']=date('Y-m-d');
                                    $search_data[] = $row;
                                }
                            }
                        } 
                    }
                }
                    
            }
            
            if($instant_search=='1'){
                foreach ($search_data as $data) {

                    $sql = "INSERT INTO  `videos`(`code_id`,`code_value`, `title` , `link` , `host` , `source`, `domain`, `language`, `size`, `quality`, `date` , `createdAt`) 
                                        VALUES ( 
                                        '". $data['code_id']."',
                                        '". $data['code_value']."',
                                        '". $this->mysqli->real_escape_string($data['real_title'])."',
                                        '". $this->mysqli->real_escape_string($data['real_url'])."',
                                        '". $this->mysqli->real_escape_string($data['host'])."',
                                        '". $this->mysqli->real_escape_string($data['source'])."',
                                        '". $this->mysqli->real_escape_string($data['domain'])."',
                                        '". $this->mysqli->real_escape_string($data['language'])."',
                                        '". $this->mysqli->real_escape_string($data['size'])."',
                                        '". $this->mysqli->real_escape_string($data['quality'])."',
                                        '". $this->mysqli->real_escape_string($data['date'])."',
                                         '". $createdAt."');";
                        // mysqli_query
//                        $this->mysqli->query($sql);
                    
                }
            }
		
		
            $result = array('status'=>1, 'html'=>$this->renCodeHtml());

            return $result;
	}

	// @getAllTrackCode
	function getAllTrackCode(){

		$results = array();
		$sql = "SELECT * FROM codes ORDER BY id ";
		$result = $this->mysqli->query($sql);
		while ($row = $result->fetch_assoc()) {
			$results[] = array('id'=>$row['id'],'value'=>$row['value']);
		}

		return $results;
	}
        
        function getCodeIdByValue($value){

            $value= str_replace("'", "\'", $value);
		$results = array();
		$sql = "SELECT id FROM codes where value='$value' ";
		$result = $this->mysqli->query($sql);
		if ($row = $result->fetch_assoc()) {
			return $row['id'];
		}

		return 0;
	}

	// @updatecodesresults
	function updatecodesresults(){

		if (file_exists($this->file_check)) {
			return array('loadding'=>1, 'html'=>$this->renCodeHtml());
		}
		else{
			return array('loadding'=>0, 'html'=>$this->renCodeHtml());
		}
		
	}
	

	// @showSitesDetails
	function showSitesDetails($code_id){

		$html = '';
		$sql = "SELECT DISTINCT(`link`) FROM videos WHERE`code_id` = '{$code_id}' AND `link` <> '' ";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {
			$html .= '<a href="'.$row['link'].'" target="_blank" class="list-group-item">'.$row['link'].'</a>';
		}

		return array('status'=>1,'html'=>$html);
	}

	// @showSourceDetails
	function showSourceDetails($code_id){

		$html = '';
		$sql = "SELECT DISTINCT(`source`) FROM videos WHERE`code_id` = '{$code_id}' AND `source` <> '' ";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {
			$html .= '<a href="'.$row['source'].'" target="_blank" class="list-group-item">'.$row['source'].'</a>';
		}

		return array('status'=>1,'html'=>$html);

	}

	// @getSiteNum
	function getSiteNum($code_id){

		$sql = "SELECT COUNT(DISTINCT(`link`)) FROM videos WHERE `code_id` = '{$code_id}' ";
		$result = $this->mysqli->query($sql);
		$count = $result->fetch_row();

		if(isset($count[0]) && $count[0]>=1)
			return $count[0];
		else 
			return 0;
	}

	// @getSourceNum
	function getSourceNum($code_id){

		$sql = "SELECT COUNT(DISTINCT(`source`)) FROM videos WHERE `code_id` = '{$code_id}' ";
		$result = $this->mysqli->query($sql);
		$count = $result->fetch_row();

		if(isset($count[0]) && $count[0]>=1)
			return $count[0];
		else 
			return 0;
	}


	// @getFirstDate
	function getFirstDate($code_id){
		
		$result_date = '';
		$sql = "SELECT  `date` FROM `videos` WHERE `code_id` = '{$code_id}' ORDER BY `date` ASC LIMIT 0,1 ";
		$result = $this->mysqli->query($sql);
		while ($row = $result->fetch_assoc()) {
			$result_date = $row['date'];
			break;
		}
		return $result_date;
	}

	// @getLatestDate
	function getLatestDate($code_id){

		$result_date = '';
		$sql = "SELECT  `date` FROM `videos` WHERE `code_id` = '{$code_id}' ORDER BY `date` DESC LIMIT 0,1 ";
		$result = $this->mysqli->query($sql);
		while ($row = $result->fetch_assoc()) {
			$result_date = $row['date'];
			break;
		}
		return $result_date;
	}

	// @renCodeHtml
	function renCodeHtml(){

		$html = '';
		$sql = "SELECT * FROM codes ORDER BY id ";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {

			$site_num = $this->getSiteNum($row['id']);
			$source_num = $this->getSourceNum($row['id']);
			$first_date = $this->getFirstDate($row['id']);
			$latest_date = $this->getLatestDate($row['id']);

			$num++;
			$html .= '<tr>';
			$html .= '<td>'.$num.'</td>';
			$html .= '<td>'.$row['value'].'</td>';
			if($site_num==0){
				$html .= '<td>'.$site_num.'</td>';
			}
			else{
				$html .= '<td><a href="#" class="site_detail" data-id="'.$row['id'].'" >'.$site_num.'</a></td>';
			}
			if($source_num==0){
				$html .= '<td>'.$source_num.'</td>';
			}
			else{
				$html .= '<td><a href="#" class="source_detail" data-id="'.$row['id'].'" >'.$source_num.'</a></td>';
			}
			$html .= '<td>'.$first_date.'</td>';
			$html .= '<td>'.$latest_date.'</td>';
			$html .= '<td><a class="btn btn-danger delete" data-id="'.$row['id'].'" href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a></td>';
			$html .= '</tr>';
		}

		return $html;
	}
        
        function renCodeHtmlForHost(){

                $host=array();
                $sql = "SELECT * FROM videos";
		$result = $this->mysqli->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if(strpos($row['link'], $row['host'])!==FALSE){
                        $host[$row['host']][]='1';
                    }
		}
                
		$html = '';
		$sql = "SELECT host,COUNT(*) AS count FROM videos GROUP BY host ";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {

			$num++;
			$html .= '<tr>';
			$html .= '<td>'.$num.'</td>';
			$html .= '<td>'.$row['host'].'</td>';
                        $html .= '<td>'.$row['count'].'</td>';
                        if(isset($host[$row['host']])){
                            $html .= '<td>'.count($host[$row['host']]).'</td>';
                        }
                        else{
                            $html .= '<td>0</td>';
                        }
                        
			$html .= '</tr>';
		}

		return $html;
	}
        
        function renCodeHtmlForDomain(){

                $domains=array();
            
                $sql = "SELECT * FROM videos";
		$result = $this->mysqli->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $domains[$row['domain']][$row['host']]='1';
		}
                
		$html = '';
		$sql = "SELECT domain,COUNT(*) AS count FROM videos GROUP BY domain ";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {

			$num++;
			$html .= '<tr>';
			$html .= '<td>'.$num.'</td>';
			$html .= '<td>'.$row['domain'].'</td>';
                        if(isset($domains[$row['domain']])){
                            $html .= '<td>' . implode(", ", array_keys($domains[$row['domain']])) . '</td>';
                        }
                        else{
                            $html .= '<td></td>';
                        }
                        
                        $html .= '<td>'.$row['count'].'</td>';
			$html .= '</tr>';
		}

		return $html;
	}

	// @addDVDCode
	function addDVDCode($code){
		//checkCode to insertCode
		if($this->checkCode($code)){
			//insertCode
			$this->insertCode($code);
			return array('status'=>1,'html'=>$this->renCodeHtml());
		}
		else{
			return array('status'=>0);
		}
	}

	// @deleteDVDCode
	function deleteDVDCode($id){

		$sql = "DELETE FROM codes WHERE id = '{$id}' ";
		$this->mysqli->query($sql);
                $sql = "DELETE FROM videos WHERE code_id = '{$id}' ";
		$this->mysqli->query($sql);
		return array('status'=>1, 'html'=>$this->renCodeHtml());
		
	}
        
        function deleteVideo($code_value){

            $code_value=trim($code_value);
		$sql = "DELETE FROM videos WHERE code_id='0' and code_value like '$code_value'";
		$this->mysqli->query($sql);
		
	}

	// ----------------------------------END TRACK---------------------------------

	// @checkCode
	function checkCode($value){

		$sql = "SELECT COUNT(*) FROM codes WHERE  `value` = '{$value}' ";
		$result = $this->mysqli->query($sql);
		$count = $result->fetch_row();

		if(isset($count[0]) && $count[0]>=1)
			return false;
		else 
			return true;
	}

	// @insertcode
	function insertCode($code){

		//sql
		$sql = "INSERT INTO  `codes`( `value`, `createdAt`) 
				VALUES ( 
				'". $this->mysqli->real_escape_string($code)."',
				 '". date('Y-m-d H:i:s')."');";
                
                
		// mysqli_query
		$this->mysqli->query($sql);
	}

	// @getProxy
	public function getProxy()
	{
            return '199.115.116.233:1041';
//		$f_contents = file("proxies.txt");
//		$line = trim($f_contents[rand(0, count($f_contents) - 1)]);
//		return $line;
	}

	// @curl_execute
	function curl_execute($url,$time_call=0) {

		$time_call++;

		$headers = array();
		$headers[] = "Pragma: no-cache";
		$headers[] = "Accept-Encoding: gzip, deflate";
		$headers[] = "Accept-Language: en-US,en;q=0.9";
		$headers[] = "Upgrade-Insecure-Requests: 1";
		$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
		$headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
		$headers[] = "Cache-Control: no-cache";
		$headers[] = "Connection: keep-alive";

		$proxy = 'proxy.crawlera.com:8010';
		$proxy_auth = 'f0f1cbd3c91b4f8d8692953fb67fd4ce';

		$ch = curl_init($url);
		if($this->via_proxy) {
			curl_setopt($ch, CURLOPT_PROXY, 'http://' . $this->getProxy());
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxyAuth);
		}
		else{
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy_auth);
		}
		if(strrpos($url, 'https://')!==false && $time_call==0 ){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$content = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		// file_put_contents(__DIR__."/status.html", $status."\r\n", FILE_APPEND);
		// file_put_contents(__DIR__."/urls.html", $url."\r\n", FILE_APPEND);

		if($status!=200 && $status!=303 && $status!=307 && $status!=404 && $time_call<10 ){
			if(file_exists($this->file_check) || file_exists($this->cron_file_check)){
				sleep(1);
				return $this->curl_execute($url,$time_call);
			}
		}

		return $content;

	}



}





?>