<?php

require 'include/functions.php';
//renToHtml
$smap = new javfind;
$html = $smap->renCodeHtml();

if(isset($_POST['csv_export']) && $_POST['csv_export']=='ok'){

	$delimiter = ",";
	$filename = "data_" . date('Y-m-d') . ".csv";
	$f = fopen('php://memory', 'w');
	$datas = $smap->exportAllTocsv();
	foreach ($datas as $data) {
		fputcsv($f, $data, $delimiter);
	}
	fseek($f, 0);
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
	fpassthru($f);
	exit;
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Jav Tool | Tracker</title>
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
			<nav class="navbar navbar-default" style="margin-bottom: 5px;">
			  <div class="container-fluid">
				<div class="navbar-header">
				  <a class="navbar-brand" href="index.php">Jav Tool</a>
				</div>
				<ul class="nav navbar-nav">
				  <li><a href="index.php">Database Search</a></li>
				  <li class="active"><a href="track.php">Jav Track</a></li>
                                  <li><a href="statistic.php">Statistic</a></li>
                                  <li><a href="ultrasound.php">Copyright !</a></li>
                                  <li><a href="sites">Instant Search</a></li>
                                  <li><a href="torrent/public/">Torrent Search</a></li>
                                  <li><a href="google/public/">Engine Search</a></li>
				</ul>
			  </div>
			</nav>
		</div>
				   
		<div class="container" style="min-height: 400px;">

			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div id="alert" style="visibility: hidden;height: 52px;width: 100%;margin-bottom: 5px;"></div>
				</div>
			</div>

			<div class="row">

				<div class="col-md-9" style="margin-bottom: 15px;">
                                    <div class="col-md-10">
					<button type="button" class="btn btn-info dvd_code" style="margin-right: 10px;"><span class="glyphicon glyphicon-plus"></span> Add a DVD code</button>
                                        
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
                                        			
					<button type="button" id="cron-start" class="btn btn-success"><span class="glyphicon glyphicon-play"></span> Check now</button>
                                    </div>
                                    <div class="col-md-2">
                                        <form action="" id="csv_export" method="post">
                                                <input type="hidden" name="csv_export" id="csv_export1" value="ok">
                                                <button style="width: 100%;cursor: not-allowed;" disabled="disabled" type="submit" class="btn btn-info btn-block"><span class="glyphicon glyphicon-cloud-download"></span> Export Data</button>
                                        </form>
                                    </div>
				</div>

				<div class="col-md-3" style="margin-bottom: 15px;">
					<div class="row" id="loaddingbar" style="display: none;">
						<div class="col-md-4" style="margin-bottom: 15px;">
							<button type="button" id="stop" class="btn btn-warning btn-block"><span class="glyphicon glyphicon-stop"></span> Stop</button>
						</div>
						<div class="col-md-8">
							<div id="progressbar" class="progress-label">Loading...</div>
						</div>
					</div>
				</div>

			</div>
                    
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="sitem-table">
							<thead>
								<tr>
									<th style="width: 20px;">No</th>
									<th>DVD Code</th>
									<th>Site</th>
									<th>Source</th>
									<th>First Date</th>
									<th>Latest Date</th>
									<th style="width: 70px" class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if($html!=''){
										echo $html;
									}
								?>
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
						<div class="list-group"></div>
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
						<div class="list-group"></div>
					</div>
				</div>
			</div>
		</div>
		<!-- end Source Modal -->
		<!-- DVD code Modal -->
		<div id="dvd_code" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add a DVD code</h4>
					</div>
					<div class="modal-body">
						<div class="add-code-msg" style="display: none;"></div>
						<div>
							<input type="text" id="code_value" class="form-control" placeholder="Put a DVD code" style="width: 100%;">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default close-btn" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
						<button type="button" id="code_submit" name="save" class="btn btn-info"><span class="glyphicon glyphicon-save"></span> Save</button>
					</div>
				</div>
			</div>
		</div>
		<!-- end DVD code Modal -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
		<script src="js/run.js?<?php echo substr(md5(mt_rand()), 0, 7);?>"></script>
                <script type="text/javascript">
                    stopClicked=false;
                    jQuery(function ($){
                       
                       $("#stop").click(function (){
                           var alert = $("#alert");
                           stopClicked=true;
                          $('#loaddingbar').hide();
                          $('#cron-start').removeAttr('disabled').css('cursor','pointer');
                          if($("#sitem-table .site_detail").length>0||$("#sitem-table .source_detail").length>0){
                                $("#csv_export button").removeAttr('disabled').css('cursor','pointer');
                            }
                          updatecodesresults();                
                       });
                       
                       
                    });
                </script>
	</body>
</html>