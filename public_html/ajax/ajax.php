<?php
require '../include/functions.php';
require '../include/functions1.php';
$path_lock_file = '../include/lock.txt';
$path_lock_file_cron = '../include/cron_lock.txt';


if(isset($_POST['action'])){


	//adddvdcode
	if( $_POST['action']=='startCronTrackCode'  ){

                $api_scraper = $_POST['api_scraper'];
                $number_result = $_POST['number_result'];
                if ($api_scraper == 'api') {
                    $trk = new javfind;
                } else {
                    $trk = new javfindscraper;
                }

                if(isset($_POST['dvdCodeId'])){
                    $dvdCodeId=trim($_POST['dvdCodeId']);
                }
                else{
                    $dvdCodeId=null;
                }
                if(isset($_POST['dvdCodeValue'])){
                    $dvdCodeValue=trim($_POST['dvdCodeValue']);
                }
                else{
                    $dvdCodeValue=null;
                }
                $status = $trk->startCronTrackcode($number_result,$_POST['database_search'],$_POST['instant_search'],$dvdCodeId,$dvdCodeValue);
                if(is_array($status)){
                    echo json_encode($status);
                }
                else{
                    echo $status;
                }
		
		exit;
	}
        else if( $_POST['action']=='startCronTrackCodeForDatabaseSearch'  ){

                $api_scraper = $_POST['api_scraper'];
                $number_result = $_POST['number_result'];
                if ($api_scraper == 'api') {
                    $trk = new javfind;
                } else {
                    $trk = new javfindscraper;
                }

                $status = $trk->startCronTrackCodeForDatabaseSearch($number_result,trim($_POST['dvdCodeValue']));
                echo $status;
		
		exit;
	}
        else if( $_POST['action']=='startCronTrackCodeForInstantSearch'  ){

                $api_scraper = $_POST['api_scraper'];
                $number_result = $_POST['number_result'];
                $trk = new javfindscraper;

                $status = $trk->startCronTrackCodeForInstantSearch($number_result,trim($_POST['dvdCodeValue']));
                echo $status;
		
		exit;
	}
        else if( $_POST['action']=='startCronTrackCodeForEngineSearch'  ){

                $api_scraper = $_POST['api_scraper'];
                $number_result = $_POST['number_result'];
                $trk = new Find();

                $status = $trk->startCronTrackCodeForEngineSearch($number_result,trim($_POST['dvdCodeValue']));
                echo $status;
		
		exit;
	}
        else if( $_POST['action']=='startCronTrackCodeForTorrentSearch'  ){

                $api_scraper = $_POST['api_scraper'];
                $number_result = $_POST['number_result'];
                $trk = new Find();

                $status = $trk->startCronTrackCodeForTorrentSearch($number_result,trim($_POST['dvdCodeValue']));
                echo $status;
		
		exit;
	}
	//adddvdcode
	elseif( $_POST['action']=='addDVDcode' && $_POST['code']!='' ){

		$code = $_POST['code'];
		$trk = new javfind;
		$status = $trk->addDVDCode($code);
		echo json_encode($status);
		exit;
	}
	//deletedvdcode
	elseif( $_POST['action']=='deleteDVDcode' && $_POST['id']!='' ){

		$id = (int) $_POST['id'];
		$trk = new javfind;
		$status = $trk->deleteDVDCode($id);
		echo json_encode($status);
		exit;
	}
	//findVideos
	elseif( $_POST['action']=='findvideos' && $_POST['dvdcode']!='' ){

		$url = $_POST['dvdcode'];
                $api_scraper = $_POST['api_scraper'];
                $number_result = $_POST['number_result'];
                
                if ($api_scraper == 'api') {
                    $smap = new javfind;
                    $smap->deleteVideo($url);
                    $status = $smap->findVideos(strtoupper($url),$number_result);
                    if($status['status']=='0'){
                        $status = $smap->findVideos($url,$number_result);
                    }
                    echo json_encode($status);
                    exit;
                } else {
                    $smap = new javfindscraper;
                    $smap->deleteVideo($url);
                    $status = $smap->findVideos($url,$number_result);
                    echo json_encode($status);
                    exit;
                }
	}
        elseif( $_POST['action']=='getcurrentvideos' && $_POST['dvdcode']!='' ){

		$url = $_POST['dvdcode'];
                $number_result = $_POST['number_result'];
                $smap = new javfind;
                $status = $smap->getCurrentVideos($url,$number_result);
                echo json_encode($status);
                exit;
	}
        elseif( $_POST['action']=='getCurrentTracker'){

		$trk = new javfind;
		$status = $trk->getCurrentTracker();
		echo json_encode($status);
		exit;
	}
	//stop search funtions
	elseif( $_POST['action']=='stopsearchvideos' ){
		
		if(unlink($path_lock_file)) {
		  echo "Deleted file "; 
		}
		else{
			echo 'not found';
		}

		sleep(2);
		exit;
	}
	//stop cron funtions
	elseif( $_POST['action']=='stopcronvideos' ){
		
		if(unlink($path_lock_file_cron)) {
		  echo "Deleted file "; 
		}
		else{
			echo 'not found';
		}

		sleep(2);
		exit;
	}
	//stop cron funtions
	elseif( $_POST['action']=='updatecronstatus' ){
		
		if (!file_exists($path_lock_file_cron)){
			echo json_encode(array('loadding'=>0));
		}
		else{
			echo json_encode(array('loadding'=>1));
		}
		exit;
	}
	//updatecodesresults
	elseif( $_POST['action']=='updatecodesresults' ){

		$smap = new javfind;
		$status = $smap->updatecodesresults();
		echo json_encode($status);
		exit;
	}
	//showsitesdetails
	elseif( $_POST['action']=='showsitesdetails' && $_POST['id']!='' ){

		$code_id = (int) $_POST['id'];
		$smap 	= new javfind;
		$status = $smap->showSitesDetails($code_id);
		echo json_encode($status);
		exit;
	}
	//showsourcedetails
	elseif( $_POST['action']=='showsourcedetails' && $_POST['id']!='' ){

		$code_id = (int) $_POST['id'];
		$smap 	= new javfind;
		$status = $smap->showSourceDetails($code_id);
		echo json_encode($status);
		exit;
	}

}

?>
