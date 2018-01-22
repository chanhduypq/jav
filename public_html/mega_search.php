<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Mega Search</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
		<link rel="stylesheet"  href="css/styles.css" >
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body id="track_page">
		<div class="loading" id="loading" style="display: none;">Loading&#8230;</div>
		<div class="container">
			<?php include_once 'menu.php';?>
		</div>
				   
		<div class="container" style="min-height: 400px;">

			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div id="alert" style="visibility: hidden;height: 52px;width: 100%;margin-bottom: 5px;"></div>
				</div>
			</div>

			<div class="row">

				<div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                        <label for="dvd_code" class="sr-only">DVD</label>
                                                        <input type="text" class="form-control" id="dvd_code" placeholder="Put a DVD code" style="width: 100%;">
                                                </div>
                                        </div>
                                        <select id="api_scraper">
                                            <option value="api">api</option>
                                            <option value="scraper">scraper</option>
                                        </select>
                                        <select id="number_result">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="All">All</option>
                                        </select>
                                        <label style="cursor: pointer;">
                                            <input type="checkbox" checked id="Database_Search"/>Database Search	
                                        </label>
                                        <label style="cursor: pointer;">
                                            <input type="checkbox" checked id="Instant_Search"/>Instant Search	
                                        </label>
                                        <label style="cursor: pointer;">
                                            <input type="checkbox" checked id="Torrent_Search"/>Torrent Search	
                                        </label>
                                        <label style="cursor: pointer;">
                                            <input type="checkbox" checked id="Engine_Search"/>Engine Search	
                                        </label>
                                        			
					<button type="button" id="cron-start" class="btn btn-success"><span class="glyphicon glyphicon-play"></span> Start</button>
                                        <div class="col-md-1" id="loaddingbar" style="float: right;display: none;">
                                            <button type="button" id="stop" class="btn btn-warning btn-block"><span class="glyphicon glyphicon-stop"></span> Stop</button>
                                        </div>
                                        
                                    </div>
				</div>

<!--				<div class="col-md-3" style="margin-bottom: 15px;">
					<div class="row" id="loaddingbar" style="display: none;">
						<div class="col-md-4" style="margin-bottom: 15px;">
							<button type="button" id="stop" class="btn btn-warning btn-block"><span class="glyphicon glyphicon-stop"></span> Stop</button>
						</div>
						<div class="col-md-8">
							<div id="progressbar" class="progress-label">Loading...</div>
						</div>
					</div>
				</div>-->

			</div>
                    
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="sitem-table" style="width: 100%;">
							<thead>
								<tr>
<!--									<th style="width: 20px;">No</th>
									<th>Title</th>
									<th>Host</th>
									<th>Domain</th>
									<th>Language</th>
									<th style="width: 100px;">Size</th>
									<th>Quality</th>
									<th style="width: 175px">Date</th>-->
                                                                    <th style="width: 40%;">Title</th>
                                                                    <th style="width: 10%;">Link</th>
                                                                    <th style="width: 10%;">Language</th>
                                                                    <th style="width: 10%;">Size</th>
                                                                    <th style="width: 10%;">Quality</th>
                                                                    <th style="width: 10%;">Date</th>
                                                                    <th style="width: 10%;">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>

		<footer class="m-t">
			<div class="container">
				<div class="panel panel-default">
					<div class="panel-body text-center">
						<p>Copyright &copy; <a href="https://24x7studios.com" target="_blank">24x7studios.com</a> 2017</p>
					</div>
				</div>
			</div>
		</footer>
		<!-- Site Modal -->
		<div id="site_detail" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Site Details</h4>
					</div>
					<div class="modal-body">
                                            <div class="list-group">
                                            </div>
					</div>
				</div>
			</div>
		</div>
		<!-- end Site Modal -->
		<!-- Source Modal -->
		<div id="source_detail" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Source Details</h4>
					</div>
					<div class="modal-body">
                                            <div class="list-group">
                                            </div>
					</div>
				</div>
			</div>
		</div>
		<!-- end Source Modal -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
                <script type="text/javascript">
                    stopClicked=false;
                    jQuery(function ($){
                       
                       $("#stop").click(function (){
                           stopClicked=true;
                          $('#loaddingbar').hide();
                          $('#cron-start').removeAttr('disabled').css('cursor','pointer');
                          $("label.progress-label").removeClass('progress-label');
                          $("input.progress-label").removeAttr('disabled').css('cursor','pointer');
                       });
                       
                       $('#cron-start').click(function(e){
                            stopClicked=false;
                            var alert = $("#alert");
                            var dvd_code = $("#dvd_code");
                            $(alert).html('');
                            $(alert).css('visibility', 'hidden');
                            if(dvd_code.val().trim()==''){
                                    $(alert).html('<p class="alert alert-danger">Please put a DVD code</p>');
                                    $(alert).css('visibility', 'visible');
                                    return;
                            }
                            if($("#Database_Search").is(":checked")==false&&$("#Instant_Search").is(":checked")==false&&$("#Engine_Search").is(":checked")==false&&$("#Torrent_Search").is(":checked")==false){
                                window.alert("Please select at least one type of search");
                                return;
                            }

                            $(this).attr('disabled','disabled').css('cursor','not-allowed');
                            $('#track_page #loaddingbar').show();
                            $("#sitem-table tbody").html('');
                            
                            if($("#Database_Search").is(":checked")){
                                runAjaxForDatabaseSearch(dvd_code.val().trim(),$('#api_scraper').val(),$('#number_result').val());
                            }
                            
                            if($("#Instant_Search").is(":checked")){
                                runAjaxForInstantSearch(dvd_code.val().trim(),$('#api_scraper').val(),$('#number_result').val());
                            }
                            
                            if($("#Engine_Search").is(":checked")){
                                runAjaxForEngineSearch(dvd_code.val().trim(),$('#api_scraper').val(),$('#number_result').val());
                            }
                            
                            
                            if($("#Torrent_Search").is(":checked")){
                                runAjaxForTorrentSearch(dvd_code.val().trim(),$('#api_scraper').val(),$('#number_result').val());
                            }
                                
                        });
                       
                       
                    });
                    
                    
                    function runAjaxForDatabaseSearch(dvdCodeValue,api_scraper,number_result){
                        $("#Database_Search").parent().addClass('progress-label');
                        $("#Database_Search").attr('disabled','disabled').css('cursor','not-allowed');
                        $.ajax({
                                type: 'post',
                                url: '/ajax/ajax.php',
                                data: {action: 'startCronTrackCodeForDatabaseSearch',dvdCodeValue:dvdCodeValue , api_scraper: api_scraper,number_result:number_result},
                                async: true,
                                success: function (result) {

//                                    console.log(result);
                                    if(stopClicked==false){
                                        $("#Database_Search").parent().removeClass('progress-label');
                                        $("#Database_Search").removeAttr('disabled').css('cursor','pointer');

                                        
                                        $("#sitem-table tbody").append(result);

                                        if($("label.progress-label").length==0){

                                            $('#track_page #loaddingbar').hide();
                                            $('#cron-start').removeAttr('disabled').css('cursor','pointer');
                                        }
                                    }

                                }
                        });
                        
                    }
                    function runAjaxForInstantSearch(dvdCodeValue,api_scraper,number_result){
                        $("#Instant_Search").parent().addClass('progress-label');
                        $("#Instant_Search").attr('disabled','disabled').css('cursor','not-allowed');
                        $.ajax({
                                type: 'post',
                                url: '/ajax/ajax.php',
                                data: {action: 'startCronTrackCodeForInstantSearch',dvdCodeValue:dvdCodeValue , api_scraper: api_scraper,number_result:number_result},
                                async: true,
                                success: function (result) {

//                                    console.log(result);
                                    if(stopClicked==false){
                                        $("#Instant_Search").parent().removeClass('progress-label');
                                        $("#Instant_Search").removeAttr('disabled').css('cursor','pointer');

                                        
                                        $("#sitem-table tbody").append(result);

                                        if($("label.progress-label").length==0){

                                            $('#track_page #loaddingbar').hide();
                                            $('#cron-start').removeAttr('disabled').css('cursor','pointer');
                                        }
                                    }

                                }
                        });
                        
                    }
                    function runAjaxForEngineSearch(dvdCodeValue,api_scraper,number_result){
                        $("#Engine_Search").parent().addClass('progress-label');
                        $("#Engine_Search").attr('disabled','disabled').css('cursor','not-allowed');
                        $.ajax({
                                type: 'post',
                                url: '/ajax/ajax.php',
                                data: {action: 'startCronTrackCodeForEngineSearch',dvdCodeValue:dvdCodeValue , api_scraper: api_scraper,number_result:number_result},
                                async: true,
                                success: function (result) {

                                    console.log(result);
                                    if(stopClicked==false){
                                        $("#Engine_Search").parent().removeClass('progress-label');
                                        $("#Engine_Search").removeAttr('disabled').css('cursor','pointer');

                                        
                                        $("#sitem-table tbody").append(result);

                                        if($("label.progress-label").length==0){

                                            $('#track_page #loaddingbar').hide();
                                            $('#cron-start').removeAttr('disabled').css('cursor','pointer');
                                        }
                                    }

                                }
                        });
                        
                    }
                    function runAjaxForTorrentSearch(dvdCodeValue,api_scraper,number_result){
                        $("#Torrent_Search").parent().addClass('progress-label');
                        $("#Torrent_Search").attr('disabled','disabled').css('cursor','not-allowed');
                        $.ajax({
                                type: 'post',
                                url: '/ajax/ajax.php',
                                data: {action: 'startCronTrackCodeForTorrentSearch',dvdCodeValue:dvdCodeValue , api_scraper: api_scraper,number_result:number_result},
                                async: true,
                                success: function (result) {

                                    console.log(result);
                                    if(stopClicked==false){
                                        $("#Torrent_Search").parent().removeClass('progress-label');
                                        $("#Torrent_Search").removeAttr('disabled').css('cursor','pointer');

                                        
                                        $("#sitem-table tbody").append(result);

                                        if($("label.progress-label").length==0){

                                            $('#track_page #loaddingbar').hide();
                                            $('#cron-start').removeAttr('disabled').css('cursor','pointer');
                                        }
                                    }

                                }
                        });
                        
                    }
                </script>
	</body>
</html>