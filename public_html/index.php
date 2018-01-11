<?php

require 'include/functions.php';
require 'include/functions1.php';

//renToHtml
$smap = new javfind;

//export to csv
if(isset($_POST['csv_export']) && $_POST['csv_export']=='ok'){

	$delimiter = ",";
	$filename = "data_" . date('Y-m-d') . ".csv";
	$f = fopen('php://memory', 'w');
	$datas = $smap->exportTocsv(trim($_POST['dvd_code']),$_POST['limit']);
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
		<title>Jav Tool | Home</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
		<link rel="stylesheet"  href="css/styles.css" >
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body id="home_page">
		<div class="loading" id="loading" style="display: none;">Loading&#8230;</div>
		<div class="container">
			<nav class="navbar navbar-default" style="margin-bottom: 5px;">
			  <div class="container-fluid">
				<div class="navbar-header">
				  <a class="navbar-brand" href="index.php">Jav Tool</a>
				</div>
				<ul class="nav navbar-nav">
				  <li class="active"><a href="index.php">Home</a></li>
				  <li><a href="track.php">Jav Track</a></li>
                                  <li><a href="statistic.php">Statistic</a></li>
                                  <li><a href="ultrasound.php">Copyright !</a></li>
                                  <li><a href="sites">Tab</a></li>
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

			<div class="row" style="margin-bottom: 25px;">
				<div class="col-lg-4 col-md-4">

					<div class="row" style="margin-bottom: 10px;">
						<div class="col-md-12">
							<form action="" id="search_form" method="post">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="dvd_code" class="sr-only">DVD</label>
											<input type="text" class="form-control" id="dvd_code" placeholder="Put a DVD code" style="width: 100%;">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
                                                                                <select id="api_scraper">
                                                                                    <option value="api">api</option>
                                                                                    <option value="scraper">scraper</option>
                                                                                </select>
									</div>
                                                                    <div class="col-md-1">&nbsp;</div>
                                                                    <div class="col-md-2">
                                                                                <select id="number_result">
                                                                                    <option value="10">10</option>
                                                                                    <option value="20">20</option>
                                                                                    <option value="50">50</option>
                                                                                    <option value="All">All</option>
                                                                                </select>
									</div>
                                                                    <div class="col-md-1">&nbsp;</div>
                                                                    <div class="col-md-6">
										<button style="float: left;" type="submit" id="btn-start" class="btn btn-success btn-block"><span class="glyphicon glyphicon-play"></span> Start</button>
									</div>
								</div> 
							</form>
						</div>
					</div>

				</div>

				<div class="col-lg-4 col-md-4 data-status">
					<div class="row" style="margin-bottom: 15px;">
						<div class="col-md-12">
							<button type="submit" class="btn btn-danger btn-block open-modal" id="erase_data" ><span class="glyphicon glyphicon-trash"></span> Clear Data</button>
						</div>
					</div>
					<div class="row" style="margin-bottom: 15px;">
						<div class="col-md-12">
							<form action="" id="csv_export" method="post">
								<input type="hidden" name="csv_export" id="csv_export1" value="ok">
                                                                <input type="hidden" name="dvd_code" id="dvdcode" value="">
                                                                <input type="hidden" name="limit" id="limit" value="10">
                                                                <button style="cursor: not-allowed;" disabled="disabled" type="submit" class="btn btn-info btn-block"><span class="glyphicon glyphicon-cloud-download"></span> Export Data</button>
							</form>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-4 progress-block"><!-- hidden -->
					<div class="row" style="margin-bottom: 18px;">
						<div class="col-md-12">
							<div class="count-process">Result: <strong id="datacount">0</strong></div>
						</div>
					</div>
					<div class="row" id="loaddingbar" style="display: none;">
<!--						<div class="col-md-4" style="margin-bottom: 15px;">
							<button type="button" id="stop" class="btn btn-warning btn-block"><span class="glyphicon glyphicon-stop"></span> Stop</button>
						</div>-->
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
									<th>Title</th>
									<th>Host</th>
									<th>Domain</th>
									<th>Language</th>
									<th style="width: 100px;">Size</th>
									<th>Quality</th>
									<th style="width: 175px">Date</th>
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
		<!-- Erase all data Modal -->
		<div id="erase_all_data" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Clear</h4>
					</div>
					<div class="modal-body">
						<p>Do you really want to Clear data?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
						<button type="submit" id="delete" name="erase" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Clear!</button>
					</div>
				</div>
			</div>
		</div>
		<!-- end Erase all data -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
		<script src="js/run.js"></script>
                <script>
                    jQuery(function ($){
                       $("#number_result").change(function (){
                          $("#limit").val($(this).val()); 
                       }); 
                       $('#dvd_code').on('input', function(){ 
                           $("#dvdcode").val($(this).val());
                       });
                    });
                </script>
	</body>
</html>