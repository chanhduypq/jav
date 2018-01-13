<?php

require 'include/functions.php';
//renToHtml
$smap = new javfind;
$html_host = $smap->renCodeHtmlForHost();
$html_domain = $smap->renCodeHtmlForDomain();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Jav Tool | Statistic</title>
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
				  <li><a href="track.php">Jav Track</a></li>
                                  <li class="active"><a href="statistic.php">Statistic</a></li>
                                  <li><a href="ultrasound.php">Copyright !</a></li>
                                  <li><a href="sites">Instant Search</a></li>
				</ul>
			  </div>
			</nav>
		</div>
				   
		<div class="container" style="min-height: 400px;">


                    <h3>Host</h3>
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="sitem-table">
							<thead>
								<tr>
									<th style="width: 20px;">No</th>
									<th>Host</th>
                                                                        <th>Count</th>
                                                                        <th>Unique Site</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if($html_host!=''){
										echo $html_host;
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
                    
                    <h3>Domain</h3>
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="sitem-table">
							<thead>
								<tr>
									<th style="width: 20px;">No</th>
									<th>Domain</th>
                                                                        <th>Host</th>
                                                                        <th>Count</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if($html_domain!=''){
										echo $html_domain;
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
	</body>
</html>