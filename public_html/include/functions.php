<?php
require_once 'simple_html_dom.php';
set_time_limit(0);
ini_set ( 'max_execution_time', 1200);
date_default_timezone_set('Asia/Singapore');

include_once 'find.php';
// javfind
class javfind extends Find{
	
	/* config db */
	public $proxyAuth 	= 'galvin24x7:egor99';
	public $apikey = 'a56c1dc1143c8a71ae671bd8726cb0cf';// '43cff1c7c5e5c8cc217774b590aeb60c' '149beff6acf922dd37079795cfeee8c1';
	public $via_proxy 	= true;
	public $file_check 	= __DIR__.'/lock.txt';
	public $cron_file_check = __DIR__.'/cron_lock.txt';
        public $number_result=0;
        public $increase=0;

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

	
	// ----------------------------------TRACK---------------------------------

        function startCronTrackcode($number_result, $database_search, $instant_search, $dvdCodeId = null, $dvdCodeValue = null) {

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
            
	    if(ctype_digit($dvdCodeId)&&is_string($dvdCodeValue)&&trim($dvdCodeValue)!=""){
                if($database_search=='1'){
                    $this->findVideos1(trim($dvdCodeValue),$number_result, $dvdCodeId);
                }
                if($instant_search=='1'){
                    foreach ($sites as $row){
                        $i=1;
                        $video = get_video(trim($dvdCodeValue), $row['url'], $row['search_parameter'], $row['search_result_parameter'], $row['product_parameter'], $row['video_parameter']);
                        if ($video !== FALSE) {
                            foreach ($video as $temp) {
                                if(!is_numeric($number_result)||$i<=$number_result){
                                    $row['real_url'] = $temp['url'];
                                    $row['real_title'] = $temp['title'];
                                    $row['real_host'] = $temp['embed'];
                                    $row['status'] = 1;
                                    $row['code_id'] = $dvdCodeId;
                                    $row['code_value'] = trim($dvdCodeValue);
                                    $row['host'] = '';
                                    $row['source'] = '';
                                    $row['domain'] = '';
                                    $row['language'] = '';
                                    $row['size'] = '';
                                    $row['quality'] = '';
                                    $row['date']=date('Y-m-d');
                                    $search_data[] = $row;
                                    $i++;
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
                                            NULL,
                                             '". $createdAt."');";
                            // mysqli_query
                            $this->mysqli->query($sql);

                    }
                }


                return $this->renCodeHtml($dvdCodeId);
            }
            else{
                $codes = $this->getAllTrackCode();
            
                foreach ($codes as $code) {
                    if($database_search=='1'){
                        $this->findVideos1($code['value'],$number_result, $code['id']);
                    }
                    if($instant_search=='1'){
                        foreach ($sites as $row){
                            $i=1;
                            $video = get_video($code['value'], $row['url'], $row['search_parameter'], $row['search_result_parameter'], $row['product_parameter'], $row['video_parameter']);
                            if ($video !== FALSE) {
                                foreach ($video as $temp) {
                                    if(!is_numeric($number_result)||$i<=$number_result){
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
                                        $i++;
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
                                            NULL,
                                             '". $createdAt."');";
                            // mysqli_query
                            $this->mysqli->query($sql);

                    }
                }


                $result = array('status'=>1, 'html'=>$this->renCodeHtml());

                return $result;
            }
            
	}
        
        function startCronTrackCodeForDatabaseSearch($number_result, $dvdCodeValue) {

            if (is_string($dvdCodeValue) && trim($dvdCodeValue) != "") {
                $this->findVideos1(trim($dvdCodeValue), $number_result);
                return $this->renVideosHtml(trim($dvdCodeValue), $number_result);
            }
            return '';
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