<?php


// javfind
class javfindscraper{
	
	/* config db */
	public $dbhost = 'localhost';
	public $dbname = 'admin_prontv';
//        public $dbname = 'admin_prontv_test';
	public $dbuser = 'root';
//	public $dbpasswd 	= 'JZ5hNjM$@7zh';
        public $dbpasswd 	= '';
	public $proxyAuth 	= 'galvin24x7:egor99';
	public $via_proxy 	= false;
	public $file_check 	= __DIR__.'/lock.txt';
	public $cron_file_check = __DIR__.'/cron_lock.txt';
        public $number_result=0;
        public $increase=0;
        public $createdAt;
                function __construct(){
		// DB
		$this->mysqli = new mysqli($this->dbhost, $this->dbuser, $this->dbpasswd, $this->dbname);
		$this->mysqli->query('SET NAMES utf8;');
                
                $this->createdAt=date('Y-m-d H:i:s');
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
        
        function deleteVideo($code_value){

            $code_value=trim($code_value);
		$sql = "DELETE FROM videos WHERE code_id='0' and code_value like '$code_value'";
		$this->mysqli->query($sql);
		
	}

	// @findVideos
	function findVideos($code,$number_result,$code_id=0){
            $this->increase=0;
            $this->number_result=$number_result;
		file_put_contents($this->file_check, '1');
		$url = 'http://pron.tv/stream/'.trim($code);

		$check = false;
		$result = array('status'=>0, 'html'=>'');
		$html = $this->curl_execute($url);
		if($this->getDetails($html,$code_id,$code)){
			$check = true;
		}
		//get sub page
		$numpage = $this->getPager($html);
		if($numpage>1){
			for ($i=2; $i <= $numpage ; $i++) { 
				
				$sub_url = $url.'?page='.$i;
				$sub_html = $this->curl_execute($sub_url);
				if($this->getDetails($sub_html,$code_id,$code)){
					$check = true;
				}
				// break;//debug
			}
		}
		//check
		if($check == true){
			$result = array('status'=>1, 'html'=>$this->renVideosHtml($code, $number_result));
		}
                

		return $result;
	}
        
        function findVideos1($code,$number_result,$code_id=0){
            $this->increase=0;
            $this->number_result=$number_result;
		file_put_contents($this->file_check, '1');
		$url = 'http://pron.tv/stream/'.trim($code);

		$check = false;
		$result = array('status'=>0, 'html'=>'');
		$html = $this->curl_execute($url);
		if($this->getDetails($html,$code_id,$code)){
			$check = true;
		}
		//get sub page
		$numpage = $this->getPager($html);
		if($numpage>1){
			for ($i=2; $i <= $numpage ; $i++) { 

				$sub_url = $url.'?page='.$i;
				$sub_html = $this->curl_execute($sub_url);
				if($this->getDetails($sub_html,$code_id,$code)){
					$check = true;
				}
			}
		}
		
	}

	// @getDetails
	function getDetails($html,$code_id,$code_value){

		$result = false;
		$html_base = new simple_html_dom();
		$html_base->load($html);
		
		$nodes = $html_base->find("#resultitems .resblock");
		foreach ($nodes as $node) {

			$tmp = $node->find(".search-result-thumbnail a");
			if(isset($tmp[0])){

				//check run
//				if($code_id==0){
//					if (!file_exists($this->file_check)) break;
//				}
//				else{
//					if (!file_exists($this->cron_file_check)) break;
//				}

				//check redirect
				$tmp = str_replace('&amp;', '&', $tmp[0]->href);
				if(strrpos($tmp, 'http://pron.tv/source/mindgeekredirect.php?url=')!==false){
					$tmp = str_replace('http://pron.tv/source/mindgeekredirect.php?url=', '', $tmp);
					$tmp = urldecode($tmp);
				}

				if($tmp!==false && trim($tmp)!='' && strrpos($tmp, 'http://')===false && strrpos($tmp, 'https://')===false ){
					$tmp = explode('?', $tmp);
					$tmp = 'http://pron.tv'.trim($tmp[0]);
					if($this->getMoreDetails($tmp,$code_id,$code_value)){
						$result = true;
					}
				}
				else{
					//jack code for current
					$data = array(
							'title'=>'',
							'code_id'=>$code_id,
                                                        'code_value'=>$code_value,
							'link'=>trim($tmp),
							'host'=>'',
							'source'=>trim($tmp),
							'domain'=>'',
							'language'=>'',
							'size'=>'',
							'quality'=>'',
							'date'=>''
							);
					//title
					$tmp = $node->find(".sourcetitle");
					$data['title'] = (isset($tmp[0]))?trim($tmp[0]->plaintext):'';
					//host
					$tmp = $node->find(".hoster a");
					$data['host'] = (isset($tmp[0]))?trim($tmp[0]->plaintext):'';
					//date & size
					$tmp = $node->find(".hoster");
					if(isset($tmp[0]) && strrpos($tmp[0], '</a>')!==false ){
						$tmp = explode('</a>', $tmp[0]->innertext);
						$tmp = trim(strip_tags($tmp[1]));
						$tmps = explode('-', $tmp);
						foreach ($tmps as $tmp) {
							if(substr_count($tmp, '.')==2){
								$data['date'] = trim($tmp);
							}
							elseif(strrpos($tmp, ' MB')!==false){
								$data['size'] = trim($tmp);
							}
						}
					}
					//quality
					$tmp = $node->find(".tagged");
					$data['quality'] = (isset($tmp[0]))?trim($tmp[0]->plaintext):'';
					//language
					$tmp = $node->find(".source img");
					$data['language'] = (isset($tmp[0]))?trim($tmp[0]->title):'';
					//domain
					$tmp = $node->find(".source");
					$data['domain'] = (isset($tmp[0]))?trim($tmp[0]->plaintext):'';
                                        $data['source'] = rtrim($data['source'],'/');
                                        $data['link'] = rtrim($data['link'],'/');

					//add to databse
					if($data['title']!='' && $this->checkData($data['link'],$code_id) ){
                                            if(!is_numeric($this->number_result)||$this->increase< $this->number_result){
                                                $this->insertData($data);
						$result = true;
                                                $this->increase++;
                                            }
						
					}
				}
			}
		}
		// clear html_base
		$html_base->clear();
		unset($html_base);

		return $result;
	}

	// @getMoreDetails
	function getMoreDetails($url,$code_id,$code_value){

		$result = false;

		$data = array(
				'title'=>'',
				'code_id'=>$code_id,
                                'code_value'=>$code_value,
				'link'=>'',
				'host'=>'',
				'source'=>'',
				'domain'=>'',
				'language'=>'',
				'size'=>'',
				'quality'=>'',
				'date'=>''
				);

		$html = $this->curl_execute($url);
		$html_base = new simple_html_dom();
		$html_base->load($html);

		$tmp_language = '<td style="width:100px;"><b>Language</b></td>';
		$tmp_domain   = '<td><b>Domain</b></td>';
		$tmp_link 	  = '<td><b>Source Link</b></td>';
		$tmp_title 	  = '<td><b>Title</b></td>';
		//loop
		$nodes = $html_base->find("#div_info_source table tr");
		foreach ($nodes as $node) {
			
			$tmp = $node->innertext;
			if(strrpos($tmp, $tmp_language)!==false ){
				$tmp = str_replace($tmp_language, '', $tmp);
				$data['language'] = trim(strip_tags($tmp));
			}
			elseif(strrpos($tmp, $tmp_domain)!==false ){
				$tmp = str_replace($tmp_domain, '', $tmp);
				$data['domain'] = trim(strip_tags($tmp));
			}
			elseif(strrpos($tmp, $tmp_link)!==false ){
				$tmp = str_replace($tmp_link, '', $tmp);
				$data['link'] = rtrim(trim(strip_tags($tmp)),'/');
			}
			elseif(strrpos($tmp, $tmp_title)!==false ){
				$tmp = str_replace($tmp_title, '', $tmp);
				$data['title'] = trim(strip_tags($tmp));
			}
		}

		$tmp_date 	  = '<span><b>Found</b></span>';
		$tmp_host 	  = '<span><b>Host</b></span>';
		$tmp_quality  = '<span><b>Quality</b></span>';
		$tmp_size 	  = '<span><b>Size</b></span>';
		$nodes 		  = $html_base->find(".linkdetails  .blockx div");
		foreach ($nodes as $node) {
			
			$tmp = $node->innertext;
			if(strrpos($tmp, $tmp_date)!==false ){
				$tmp = str_replace($tmp_date, '', $tmp);
				$data['date'] = trim(strip_tags($tmp));
			}
			elseif(strrpos($tmp, $tmp_host)!==false ){
				$tmp = str_replace($tmp_host, '', $tmp);
				$data['host'] = trim(strip_tags($tmp));
			}
			elseif(strrpos($tmp, $tmp_quality)!==false ){
				$tmp = str_replace($tmp_quality, '', $tmp);
				$data['quality'] = trim(strip_tags($tmp));
			}
			elseif(strrpos($tmp, $tmp_size)!==false ){
				$tmp = str_replace($tmp_size, '', $tmp);
				$data['size'] = trim(strip_tags($tmp));
			}			
		}
		//update size
		if($data['size']=='n/a'){
			$data['size'] = '';
		}

		//source
		$tmp = $html_base->find("#rawURLStextbox");
		$tmp_code = (isset($tmp[0]))?trim($tmp[0]->plaintext):'';
		$tmp_aaa = $this->getBetweenXandY($html,'var aaa =',';');
		$tmp_bbb = $this->getBetweenXandY($html,'var bbb =',';');
		$tmp_ccc = $this->getBetweenXandY($html,'var ccc =',';');
		$tmp_ddd = $this->getBetweenXandY($html,'var ddd =',';');
		$tmp_char = $this->getBetweenXandY($html,"actualURLs+decrypt( item, '","'");

		if($tmp_code!='' && $tmp_aaa!==false && $tmp_bbb!==false && $tmp_ccc!==false && $tmp_ddd!==false && $tmp_char!==false ){
			
			$data['source'] = $this->decryptPronSource($tmp_code, $tmp_aaa, $tmp_bbb, $tmp_ccc, $tmp_ddd,$tmp_char);
			if(strrpos($data['source'], 'http://pron.tv/source/mindgeekredirect.php?url=')!==false){
				$data['source'] = str_replace('http://pron.tv/source/mindgeekredirect.php?url=', '', $data['source']);
				$data['source'] = urldecode($data['source']);
			}
		}
		// else{
		// 	$data['source'] = $this->getPorSource($data['link']);
		// }


		// clear html_base
		$html_base->clear();
		unset($html_base);
                $data['source'] = rtrim($data['source'],'/');
		//merge data
		if($data['title']!=''){// && $this->checkData($data['link'], $code_id) ){
                    if(!is_numeric($this->number_result)||$this->increase< $this->number_result){
                        $this->insertData($data);
                        $result = true;
                        $this->increase++;
                    }
		}

		return $result;
	}

	function getPorSource($url){

		$result = '';

		if($url!=''){
			$html = $this->curl_execute($url);
			$html_base = new simple_html_dom();
			$html_base->load($html);

			$tmp = $html_base->find('iframe[allowfullscreen="true"]');
			if(isset($tmp[0]) && trim($tmp[0]->src)!=''){
				$result = trim($tmp[0]->src);
			}
			// clear html_base
			$html_base->clear();
			unset($html_base);
		}

		return $result;
	}

	// @decryptPronSource
	function decryptPronSource($r,$aaa,$bbb,$ccc,$ddd,$t){

		$aaa = trim(str_replace("'", '', $aaa));
		$bbb = trim(str_replace("'", '', $bbb));
		$ccc = trim(str_replace("'", '', $ccc));
		$ddd = trim(str_replace("'", '', $ddd));

		$e = "";
		$o = substr($r,0,3);
		$r = substr($r,3);

		if ("3".$aaa."f" == $o) {
			$r = strrev(base64_decode($r));
		} else {
			if ("f".$bbb."0" == $o) {
				$r = $this->hta(strrev($r));
			} else {
				if ("6".$ccc."3" == $o) {
					$r = base64_decode(strrev($r));
				} else {
					if ("5".$ddd."a" == $o) {
						$r = base64_decode($this->strswpcs($r));
					}
				}
			}
		}
		$s = 0;
		for ($s = 0; $s < strlen($r); $s++) {
			$n = substr($r,$s, 1);
			$a = substr($t,$s % strlen($t) - 1, 1);
			$n = floor(ord($n) - ord($a));
			$e .= $n = chr($n);
		}

		return $e;

	}

	// @hta
	function hta($r) {
		$e = "";
		for ( $o = 0; $o <strlen($r); $o = $o+2){
			$tmp = intval(substr($r,$o, 2), 16);
		 	$e .= chr($tmp);
		}
		return $e;
	}

	// @strswpcs
	function strswpcs($str) {

		$newStr = '';
		$length = strlen($str);
		for ($i=0 ; $i<$length ; $i++) {
			if (strtoupper($str[$i]) == $str[$i]) {
				$newStr .= strtolower($str[$i]);
			} else {
				$newStr .= strtoupper($str[$i]);
			}
		}
		return $newStr;

	}

	// @getPager
	function getPager($html){

		$html_base = new simple_html_dom();
		$html_base->load($html);

		$pagers = $html_base->find(".pagination li");
                
                $arr[] = 1;
                foreach ($pagers as $pager) {
                    $arr[] = (int) trim($pager->plaintext);
                }
                $numpage = max($arr);

//		foreach ($pagers as $pager) {
//			if(strrpos($pager->class, 'plast')!==false){
//				$numpage = (int) trim($pager->plaintext);
//				break;
//			}
//		}

		// clear html_base
		$html_base->clear();
		unset($html_base);

		return $numpage;
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
	function renVideosHtml($code_value,$limit){
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

	// cronTrackcode
	function cronTrackcode($code_id,$code){

		$check = false;
		$url = 'http://pron.tv/stream/'.trim($code);
		$html = $this->curl_execute($url);
		if($this->getDetails($html,$code_id)){
			$check = true;
		}
		//get sub page
		$numpage = $this->getPager($html);
		if($numpage>1){
			for ($i=2; $i <= $numpage ; $i++) { 
				//check run
				if (!file_exists($this->cron_file_check)) break;

				$sub_url = $url.'?page='.$i;
				$sub_html = $this->curl_execute($sub_url);
				if($this->getDetails($sub_html,$code_id)){
					$check = true;
				}
				// break;//debug
			}
		}
		
		return $check;
	}

	// @startCronTrackcode
	function startCronTrackcode_bk(){

		file_put_contents($this->cron_file_check, '1');
		$check = false;
		$result = array('status'=>0, 'html'=>'');
		//loop all dvd code
		$codes = $this->getAllTrackCode();
		foreach ($codes as $code) {
			if($this->cronTrackcode($code['id'], $code['value'])){
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
			$html .= '<a href="'.$row['link'].'" target="_blank" class="list-group-item">'.$row['link'].'</a>';
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
			$html .= '<a href="'.$row['source'].'" target="_blank" class="list-group-item">'.$row['source'].'</a>';
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
                $sql = "DELETE FROM videos WHERE code_id = '{$id}' ";
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
                
                $this->mysqli->query("update videos set code_id='".$this->mysqli->insert_id."' where code_value like '".$this->mysqli->real_escape_string($code)."'");
	}

	// @getProxy
	public function getProxy()
	{
            return '199.115.116.233:1040';
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
//                curl_setopt($ch, CURLOPT_CAINFO, 'crawlera-ca.crt'); //required for HTTPS
		$content = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		// file_put_contents(__DIR__."/status.html", $status."\r\n", FILE_APPEND);
		// file_put_contents(__DIR__."/urls.html", $url."\r\n", FILE_APPEND);

		if($status!=200 && $status!=303 && $status!=307 && $status!=404 && $time_call<4 ){
			if(file_exists($this->file_check) || file_exists($this->cron_file_check)){
				sleep(1);
				return $this->curl_execute($url,$time_call);
			}
		}

		return $content;

	}



}






?>