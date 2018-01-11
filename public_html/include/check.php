<?php

require_once 'simple_html_dom.php';

// $ab = new javfind;
// $ttt = $ab->startCronTrackcode();
// $ab->findVideos("bomc");
// $url = $ab->decryptPronSource();
// var_dump($url);

$code = "MATU-99";
$smap = new javfind;
$html = $smap->findVideos($code);

var_dump($html);

// javfind
class javfind{
	
	/* config db */
	public $dbhost = 'localhost';
	public $dbname = 'admin_prontv';
	public $dbuser = 'admin_prontv';
	public $dbpasswd 	= 'xVnPDRFcpO';
	public $proxyAuth 	= 'galvin24x7:egor99';
	public $apikey = '149beff6acf922dd37079795cfeee8c1';
	public $via_proxy 	= true;
	public $file_check 	= __DIR__.'/lock.txt';
	public $cron_file_check = __DIR__.'/cron_lock.txt';

	function __construct(){
		// DB
		$this->mysqli = new mysqli($this->dbhost, $this->dbuser, $this->dbpasswd, $this->dbname);
		$this->mysqli->query('SET NAMES utf8;');
	}



	// @findVideos
	function findVideos($code,$code_id=0){

		file_put_contents($this->file_check, '1');
		$check = false;
		$page_limit = 10;
		$result = array('status'=>0, 'html'=>'');

		$url = 'http://pron.tv/api/search/stream/?apikey='.$this->apikey.'&query='.trim($code).'&count='.$page_limit."&from=0";
		$json_string = $this->curl_execute($url);
		$datas = json_decode($json_string,true);
		if(isset($datas['status']) && isset($datas['resultcount']) && isset($datas['result']) && $datas['status']=='success' && $datas['resultcount']>0 && is_array($datas['result']) ){
			//getDetails
			if($this->getDetails($datas['result'],$code_id)){
				$check = true;
			}

			// get sub page
			$numpage = (int) ceil($datas['resultcount']/10);
			if($numpage>1){
				for ($i=2; $i <= $numpage ; $i++) { 
					//check run
					if($code_id==0){
						if (!file_exists($this->file_check)) break;
					}
					else{
						if (!file_exists($this->cron_file_check)) break;
					}
					$sub_url = str_replace('&from=0', '&from='.(($page_limit*($i-1))-1), $url);
					$sub_json_string = $this->curl_execute($sub_url);
					$sub_datas = json_decode($sub_json_string,true);
					if(isset($sub_datas['status']) && isset($sub_datas['resultcount']) && isset($sub_datas['result']) && $sub_datas['status']=='success' && $sub_datas['resultcount']>0 && is_array($sub_datas['result']) ){
						//getDetails
						if($this->getDetails($sub_datas['result'],$code_id)){
							$check = true;
						}
					}
					break;//debug
				}
			}
		}

		//check
		if($check == true){
			$result = array('status'=>1, 'html'=>$this->renVideosHtml());
		}

		if (file_exists($this->file_check)) {
			unlink($this->file_check);
		}

		return $result;
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
	function getDetails($datas,$code_id){

		$check = false;
		
		foreach ($datas as $video_data) {
			//data
			$data = array(
						'title'=>(isset($video_data['sourcetitle']))?trim($video_data['sourcetitle']):'',
						'code_id'=>$code_id,
						'link'=>(isset($video_data['sourceurl']))?trim($video_data['sourceurl']):'',
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
						$data['source'] = trim($hosterurl['url']);
						break;
					}
				}
			}
			//size
			$data['size'] = $this->foomatVideoSize($data['size']);

			//add to databse
			if($data['title']!='' ){
				print_r($data['title']."\n");
				$this->insertData($data);
				$check = true;
			}
		}

		return $check;
	}

	// @countvideosNotrack
	function countvideosNotrack(){

		$sql = "SELECT COUNT(*) FROM videos WHERE code_id ='0' ";
		$result = $this->mysqli->query($sql);
		$count = $result->fetch_row();

		if(isset($count[0]) && $count[0]>=1)
			return $count[0];
		else 
			return 0;
	}

	// @updatevideosresults
	function updatevideosresults(){

		$count = (int) $this->countvideosNotrack();
		if (file_exists($this->file_check)) {
			return array('loadding'=>1,'datacount'=>$count, 'html'=>$this->renVideosHtml());
		}
		else{
			return array('loadding'=>0,'datacount'=>$count, 'html'=>$this->renVideosHtml());
		}
		
	}

	// @exportTocsv
	function exportTocsv(){

		$results = array();
		$results[]= array('Title','Link','Host','Source','Domain','Language','Size','Quality','Date');
		$sql = "SELECT * FROM videos ORDER BY id";
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

	// @deleteAllVideosNotrack
	function deleteAllVideosNotrack(){

		$sql = "DELETE FROM videos WHERE code_id='0'";
		$this->mysqli->query($sql);

		return array('status'=>1, 'html'=>$this->renVideosHtml());
	}

	// @renHtml
	function renVideosHtml(){

		$html = '';
		$sql = "SELECT * FROM videos WHERE code_id='0' ORDER BY id";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {
			$num++;
			$html .= '<tr>';
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

		//sql
		$sql = "INSERT INTO  `videos`(`code_id`, `title` , `link` , `host` , `source`, `domain`, `language`, `size`, `quality`, `date` , `createdAt`) 
				VALUES ( 
				'". $data['code_id']."',
				'". $this->mysqli->real_escape_string($data['title'])."',
				'". $this->mysqli->real_escape_string($data['link'])."',
				'". $this->mysqli->real_escape_string($data['host'])."',
				'". $this->mysqli->real_escape_string($data['source'])."',
				'". $this->mysqli->real_escape_string($data['domain'])."',
				'". $this->mysqli->real_escape_string($data['language'])."',
				'". $this->mysqli->real_escape_string($data['size'])."',
				'". $this->mysqli->real_escape_string($data['quality'])."',
				'". $this->mysqli->real_escape_string($data['date'])."',
				 '". date('Y-m-d H:i:s')."');";
		// mysqli_query
		$this->mysqli->query($sql);
	}


	// ----------------------------------TRACK---------------------------------

	// @startCronTrackcode
	function startCronTrackcode(){

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
		$sql = "SELECT `link` FROM videos WHERE`code_id` = '{$code_id}' AND `link` <> '' ";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {
			$html .= '<a href="'.$row['value'].'" target="_blank" class="list-group-item">'.$row['link'].'</a>';
		}

		return array('status'=>1,'html'=>$html);
	}

	// @showSourceDetails
	function showSourceDetails($code_id){

		$html = '';
		$sql = "SELECT `source` FROM videos WHERE`code_id` = '{$code_id}' AND `source` <> '' ";
		$result = $this->mysqli->query($sql);
		$num=0;
		while ($row = $result->fetch_assoc()) {
			$html .= '<a href="'.$row['value'].'" target="_blank" class="list-group-item">'.$row['source'].'</a>';
		}

		return array('status'=>1,'html'=>$html);

	}

	// @getSiteNum
	function getSiteNum($code_id){

		$sql = "SELECT COUNT(DISTINCT(`domain`)) FROM videos WHERE `code_id` = '{$code_id}' ";
		$result = $this->mysqli->query($sql);
		$count = $result->fetch_row();

		if(isset($count[0]) && $count[0]>=1)
			return $count[0];
		else 
			return 0;
	}

	// @getSourceNum
	function getSourceNum($code_id){

		$sql = "SELECT COUNT(DISTINCT(`host`)) FROM videos WHERE `code_id` = '{$code_id}' ";
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
		return array('status'=>1, 'html'=>$this->renCodeHtml());
		
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
		$f_contents = file("proxies.txt");
		$line = trim($f_contents[rand(0, count($f_contents) - 1)]);
		return $line;
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