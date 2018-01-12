<?php 
include 'config.php';

@set_time_limit(0);
@ini_set('max_execution_time', 0);
@error_reporting(E_ALL);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL);
@ini_set("auto_detect_line_endings", true);

require_once 'simple_html_dom.php';

$message = '';
$page = 'list';
if (!empty($_POST)) {
    if (!empty($_POST['action']) && $_POST['action'] == 'delete') {
        $sql = 'DELETE FROM sites WHERE id="' . $_POST['id'] . '"';
        $mysqli->query($sql);
    } else {
        $data = array(
            'name' => '"' . $mysqli->real_escape_string($_POST['name']) . '"',
            'url' => '"' . $mysqli->real_escape_string($_POST['url']) . '"',
            'search_parameter' => '"' . $mysqli->real_escape_string($_POST['search_parameter']) . '"',
            'search_result_parameter' => '"' . $mysqli->real_escape_string($_POST['search_result_parameter']) . '"',
            'detail_parameter' => '"' . $mysqli->real_escape_string($_POST['detail_parameter']) . '"',
            'video_parameter' => '"' . $mysqli->real_escape_string($_POST['video_parameter']) . '"',
            'video_host' => '"' . $mysqli->real_escape_string($_POST['video_host']) . '"',
            'product_parameter' => '"' . $mysqli->real_escape_string($_POST['product_parameter']) . '"',
        );

        if (!empty($_POST['id'])) {
            $sql = 'UPDATE sites SET ';
            foreach ($data as $key => $value) {
                $sql .= $key . '=' . $value . ',';
            }
            $sql = rtrim($sql, ',') . ' WHERE id="' . $_POST['id'] . '"';
            $results = $mysqli->query($sql);
            if ($results) {
                $message = 'Success! Site was updated';
            }
            $id = $_POST['id'];
        } else {
            $page = 'add_form';
            $sql = 'INSERT INTO sites (';
            foreach ($data as $key => $value) {
                $sql .= $key . ',';
            }
            $sql = rtrim($sql, ',') . ') VALUES(';
            foreach ($data as $key => $value) {
                $sql .= $value . ',';
            }
            $sql = rtrim($sql, ',') . ')';
            $results = $mysqli->query($sql);
            if ($results) {
                $message = 'Success! New site was added';
            }
            $results = $mysqli->query("SELECT * FROM sites ORDER BY id desc LIMIT 1");
            if ($results->num_rows == 1) {
                while ($row = $results->fetch_array()) {
                    $id = $row['id'];
                    break;
                }
            }
        }
    }
}

$results = $mysqli->query("SELECT * FROM sites ORDER BY id desc");
$num_rows = $results->num_rows;

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Instant Search</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
		<link rel="stylesheet"  href="/css/styles.css" >
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
                <style>
                    input[type=text], select {
                        width: calc(100% - 180px);
                        padding: 12px 20px;
                        margin: 8px 0;
                        display: inline-block;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-sizing: border-box;
                    }

                    input[type=submit], .btn {
                        width: 100%;
                        background-color: #4CAF50;
                        color: white;
                        padding: 14px 20px;
                        margin: 8px 0;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                    }

                    input[type=submit]:hover {
                        background-color: #45a049;
                    }

                    .content {
                        border-radius: 5px;
                        background-color: #f2f2f2;
                        padding: 20px;
                    }
                    .clear10 {
                        clear: both;
                        height: 10px;
                        padding: 0;
                    }
                    .list, .add_form, .edit_form {
                        display: none;
                    }
                    #sites, #test_sites,#results {
                        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                        border-collapse: collapse;
                        width: 100%;
                    }

                    #sites td, #sites th, #test_sites td, #test_sites th {
                        border: 1px solid #ddd;
                        padding: 8px;
                    }

                    #sites tr:nth-child(even),#test_sites tr:nth-child(even){background-color: #f2f2f2;}

                    #sites tr:hover,#test_sites tr:hover {background-color: #ddd;}

                    #sites th,#test_sites th {
                        padding-top: 12px;
                        padding-bottom: 12px;
                        text-align: left;
                        background-color: #4CAF50;
                        color: white;
                    }
                    
                    #sites, #test_sites {
                        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                        border-collapse: collapse;
                        width: 100%;
                    }

                    #sites td, #sites th, #test_sites td, #test_sites th {
                        border: 1px solid #ddd;
                        padding: 8px;
                    }

                    #sites tr:nth-child(even),#test_sites tr:nth-child(even){background-color: #f2f2f2;}

                    #sites tr:hover,#test_sites tr:hover {background-color: #ddd;}

                    #sites th,#test_sites th {
                        padding-top: 12px;
                        padding-bottom: 12px;
                        text-align: left;
                        background-color: #4CAF50;
                        color: white;
                    }
                    
                    /*tuetc*/

                    #results {
                        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                        border-collapse: collapse;
                        width: 100%;
                    }

                    #results td, #results th{
                        border: 1px solid #ddd;
                        padding: 8px;
                    }

                    #results tr:nth-child(even){background-color: #f2f2f2;}

                    #results tr:hover{background-color: #ddd;}

                    #results th {
                        padding-top: 12px;
                        padding-bottom: 12px;
                        text-align: left;
                        background-color: #4CAF50;
                        color: white;
                    }
                    
                    #test_sites {
                        width: calc(100% - 175px);
                        margin-left: 175px;
                        margin-bottom: 10px;
                    }
                    .btn {
                        width: auto;
                        text-align: center;
                    }
                    h3.list, h3.add_form, h3.edit_form {
                        float: left;
                    }
                    h3.brak {
                        padding: 0 15px;
                    }
                    .cur-poi {
                        cursor: pointer;
                    }
                    .add-btn {
                        font-weight: normal;
                        float: right !important;
                        font-size: 16px;
                    }
                    .add_form label {
                        width : 170px;
                        display: inline-block;
                    }

                </style>
	</head>
	<body id="track_page">
		<div class="container">
			<nav class="navbar navbar-default" style="margin-bottom: 5px;">
			  <div class="container-fluid">
				<div class="navbar-header">
				  <a class="navbar-brand" href="index.php">Jav Tool</a>
				</div>
				<ul class="nav navbar-nav">
				  <li><a href="../index.php">Database Search</a></li>
				  <li><a href="../track.php">Jav Track</a></li>
                                  <li><a href="../statistic.php">Statistic</a></li>
                                  <li><a href="../ultrasound.php">Copyright !</a></li>
                                  <li class="active"><a href="/sites">Instant Search</a></li>
				</ul>
			  </div>
			</nav>
		</div>
				   
		<div class="container" style="min-height: 400px;">


                    <div class="clear10"></div>
                    <h3 class="add_form edit_form cur-poi" onclick="home()">HOME</h3>
                    <h3 class="add_form edit_form brak"> > </h3>
                    <h3 class="list">PORN SITES LIST</h3>
                    <h3 class="list btn add-btn" onclick="add_site()">ADD NEW</h3>
                    <h3 class="add_form">ADD NEW PORN SITE</h3>
                    <h3 class="edit_form">EDIT PORN SITE</h3>
                    <div class="clear10"></div>
                        <?php if ($message != '') {
                            $bg = 'blueviolet';
                            if(strpos('Error!',$message) === 0) {
                                $bg = 'coral';
                            }
                            echo '<div class="message btn" style="background: '.$bg.';">'.$message.'</div>';
                        } ?>
                    <div class="clear10"></div>
                    <div class="row">
                            <div class="col-lg-12 col-md-12">
                                    <div id="alert" style="visibility: hidden;height: 52px;width: 100%;margin-bottom: 5px;"></div>
                            </div>
                    </div>
                    <div class="content list row">
                        <form method="post" id="search_form1" onsubmit="return false;">
                            <div class="col-md-6">
                                <input style="float:left;height:43px;width: 300px;margin-right: 20px" type="text" name="dvdcode" placeholder="dvd code"><input style="width: auto" type="submit" value="Search">
                                    
                                </div>    
                        </form>
                        <div id="loaddingbar" class="col-md-4" style="visibility: hidden;">
                                <button type="button" id="stop" class="btn btn-warning btn-block"><span class="glyphicon glyphicon-stop"></span> Stop</button>
                        </div>
                        <div class="col-md-2">
                                <a id="clear" href="index.php" class="btn" style="float:right;text-decoration: none;display: none;">Clear</a>
                        </div>
                        <div class="no_data" id="no_data" style="display: none;">No site found.</div>
                        
                        
                        <table id="sites">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Url</th>
                                        <th>Search parameter</th>
                                        <th>Detail page parameter</th>
                                        <th>Product title parameter</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $results->fetch_array()) { ?>
                                    <tr id="site_<?php echo $row['id']; ?>" data-video_host="<?php echo $row['video_host']; ?>" data-name="<?php echo $row['name']; ?>" data-url="<?php echo $row['url']; ?>"
                                        data-search_parameter="<?php echo $row['search_parameter']; ?>" data-detail_parameter="<?php echo $row['detail_parameter']; ?>" data-product_parameter="<?php echo $row['product_parameter']; ?>"
                                        data-video_parameter="<?php echo $row['video_parameter']; ?>" data-search_result_parameter="<?php echo $row['search_result_parameter']; ?>">
                                        <td id="site_<?php echo $row['id']; ?>_name" style="word-break: break-word;"><?php echo $row['name']; ?></td>
                                        <td id="site_<?php echo $row['id']; ?>_url" style="word-break: break-word;"><?php echo $row['url']; ?></td>
                                        <td id="site_<?php echo $row['id']; ?>_search_parameter" style="word-break: break-word;"><?php echo $row['search_parameter']; ?></td>
                                        <td id="site_<?php echo $row['id']; ?>_detail_parameter" style="word-break: break-word;"><?php echo $row['detail_parameter']; ?></td>
                                        <td id="site_<?php echo $row['id']; ?>_product_parameter" style="word-break: break-word;"><?php echo $row['product_parameter']; ?></td>
                                        <td>
                                            <div style="display: inline-block;" onclick="edit_site(<?php echo $row['id']; ?>)" class="btn">Edit</div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                
                            </table>
                            
                    </div>
                    
                    

                    <div class="content add_form edit_form">
                        <form method="post">
                            <input type="hidden" id="id" name="id" <?php if(!empty($_POST['id'])) { echo 'value="'.$_POST['id'].'"'; }?>>
                            <label>Site name</label>
                            <input type="text" id="name" name="name" placeholder="Site Name" <?php if(!empty($_POST['name'])) { echo 'value="'.$_POST['name'].'"'; }?> required>
                            <label>Site url</label>
                            <input type="text" id="url" name="url" placeholder="Site Url" <?php if(!empty($_POST['url'])) { echo 'value="'.$_POST['url'].'"'; }?> required>
                            <label>Search parameter</label>
                            <input type="text" id="search_parameter" name="search_parameter" placeholder="Search parameter" <?php if(!empty($_POST['search_parameter'])) { echo 'value="'.$_POST['search_parameter'].'"'; }?> required>
                            <label>Search result parameter</label>
                            <input type="text" id="search_result_parameter" name="search_result_parameter" placeholder="Search result parameter" <?php if(!empty($_POST['search_result_parameter'])) { echo 'value="'.$_POST['search_result_parameter'].'"'; }?> required>
                            <label>Detail page parameter</label>
                            <input type="text" id="detail_parameter" name="detail_parameter" placeholder="Detail page parameter" <?php if(!empty($_POST['detail_parameter'])) { echo 'value="'.$_POST['detail_parameter'].'"'; }?> required>
                            <label>Video parameter</label>
                            <input type="text" id="video_parameter" name="video_parameter" placeholder="Video parameter" <?php if(!empty($_POST['video_parameter'])) { echo 'value="'.$_POST['video_parameter'].'"'; }?> required>
                            <div class="clear10"></div>
                            <input style="margin-left: 175px" type="radio" id="video_host_openload" name="video_host" value="openload" <?php if(empty($_POST['video_host']) || $_POST['video_host'] == 'openload') { echo 'checked'; }?>> Openload
                            <div class="clear10"></div>
                            <input style="margin-left: 175px" type="radio" id="video_host_google_drive" name="video_host" value="google drive" <?php if(!empty($_POST['video_host']) || $_POST['video_host'] == 'google drive') { echo 'checked'; }?>> Google Drive
                            <div class="clear10"></div>
                            <label>Product name parameter</label>
                            <input type="text" id="product_parameter" name="product_parameter" placeholder="Product name parameter" <?php if(!empty($_POST['product_parameter'])) { echo 'value="'.$_POST['product_parameter'].'"'; }?> required>

                            <input style="float:left;height:43px;width: 300px;margin-right: 20px;margin-left: 175px" type="text" id="test_dvdcode" placeholder="dvd code" value="rctd-034"><input class="btn" style="width: auto" onclick="test()" type="button" value="Test">
                            <div class="clear10 test_area"></div>
                            <table class="test_area" id="test_sites" style="display:none">
                                <tr>
                                    <th>Name</th>
                                    <th>Site detail url</th>
                                    <th>Name of product</th>
                                    <th>Host url</th>
                                </tr>
                                <tr>
                                    <td id="test_name" style="word-break: break-word;"></td>
                                    <td id="test_url" style="word-break: break-word;"></td>
                                    <td id="test_title" style="word-break: break-word;"></td>
                                    <td id="test_host" style="word-break: break-word;"></td>
                                </tr>
                            </table>
                            <iframe style="width:450px;height:250px;margin-left:175px;display:none" class="test_area" id="test_video" src="" frameborder="0" class="embed-player" scrolling="no" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" rel="nofollow"></iframe>
                            <div class="clear10"></div>
                            <img src="loading.gif" style="width: 100px; display:none;margin-left: 175px" id="loading">
                            <div class="clear10"></div>
                            <input style="height:43px;width: auto;margin-left: 175px;margin-right: 20px" type="submit" value="Save">
                            <input onclick="delete_btn()" class="edit_form btn" style="height:43px;width: auto;margin-left: 0;background: firebrick" type="button" value="Delete">
                        </form>
                    </div>
                        <form style="display:none" method="post">
                            <input type="hidden" id="delete_id" name="id">
                            <input type="hidden" name="action" value="delete">
                        </form>
                    
                    

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
		
		<!-- end DVD code Modal -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
		<script src="/js/run.js?<?php echo substr(md5(mt_rand()), 0, 7);?>"></script>
                
                <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
               
                <script type="text/javascript">
                    stopClicked=false;
                    addColumn=false;
                    siteIds=[];
                    var resultAjax='';
                    var count_ajax=0;
                    <?php 
                    $results1 = $mysqli->query("SELECT * FROM sites ORDER BY id desc");
                    while($row = $results1->fetch_array()) { ?>
                        <?php echo 'siteIds.push("'.$row['id'].'");'; ?>
                    <?php 
                    } 
                    $mysqli->close();
                    ?>
                    $( function() {
                        $("#stop").click(function (){
                           stopClicked=true;
                           for(i=0;i<siteIds.length;i++){
                                siteId=siteIds[i];
                                $("tr#site_"+siteId).removeClass('progress-label');
                            }
                          $('#loaddingbar').css('visibility', 'hidden');
                          $('form#search_form1 input[type="submit"]').removeAttr('disabled').css('cursor','pointer');
                          if(count_ajax>0){
                              $("#clear").show();
                          }
                       });
                       
                        $( "#tabs" ).tabs();
                        
                        $('.list').show();
                        <?php if($page == 'add_form') { echo 'add_site();'; } ?>
                        $('.message').delay(3000).fadeOut('slow');
                        
                        $('form#search_form1 input[type="submit"]').on('click', function (e) {
                            stopClicked=false;
                            count_ajax=0;
                            resultAjax='';
                            $('form#search_form1 input[type="submit"]').attr('disabled','disabled').css('cursor','not-allowed');
                            $("#no_data").hide();
                            $("#clear").hide();
                            $('#alert').html('');
                            $('#alert').css('visibility', 'hidden');
                            $('#loaddingbar').css('visibility', 'visible');
                                //reset alert
                                if($('form#search_form1 input[type="text"]').val().trim()==''){
                                        $('#alert').html('<p class="alert alert-danger">Please put a DVD code</p>');
                                        $('#alert').css('visibility', 'visible');
                                        $('form#search_form1 input[type="submit"]').removeAttr('disabled').css('cursor','pointer');
                                        $('#loaddingbar').css('visibility', 'hidden');
                                        return;
                                }
                                else{
                                    runAjax();
                                    

                                }//end else

                        });//end submit
                    });
                    
                    function runAjaxForOneSite(siteId){
                        if(!$("tr#site_"+siteId).hasClass($('form#search_form1 input[type="text"]').val().trim())){
                            $("tr#site_"+siteId).addClass('progress-label');
                            $.ajax({
                                    type: 'post',
                                    url: '/sites/ajax1.php',
                                    data: {action: 'findvideos',site_id:siteId, dvdcode : $('form#search_form1 input[type="text"]').val().trim()},
                                    async: true,
                                    success: function (result) {

                                        console.log(result);
                                        count_ajax++;
                                        if(stopClicked==false){
                                            $("tr#site_"+siteId).removeClass('progress-label');

                                            $("#sites thead").html('<tr><th>Name</th><th>Url</th><th>Site detail url</th><th>Name of product</th><th>Host url</th><th></th><th></th></tr>');
                                            if(addColumn==false){
                                                for(j=0;j<$("#sites tbody tr").length;j++){
                                                    $("#sites tbody tr").eq(j).find('td:last').before("<td></td>");
                                                }
                                                addColumn=true;
                                            }
                                            $("tr#site_"+siteId).replaceWith(result);

                                            if(count_ajax==siteIds.length){

                                                $('#loaddingbar').css('visibility', 'hidden');
                                                $('form#search_form1 input[type="submit"]').removeAttr('disabled').css('cursor','pointer');
                                                $("#clear").show();
                                            }
                                        }
//                                                    count_ajax++;
//                                                    resultAjax+=result;
//                                                        
//                                                    if(count_ajax==siteIds.length&&stopClicked==false){
//                                                        for(i=0;i<siteIds.length;i++){
//                                                            siteId=siteIds[i];
//                                                            $("tr#site_"+siteId).removeClass('progress-label');
//                                                        }
//                                                        $('#loaddingbar').css('visibility', 'hidden');
//                                                        $('form#search_form1 input[type="submit"]').removeAttr('disabled').css('cursor','pointer');
//                                                        if($.trim(resultAjax)==''){
//                                                            $("#no_data").show();
//                                                        }
//                                                        else{
//                                                            $("#sites thead").html('<tr><th>Name</th><th>Url</th><th>Site detail url</th><th>Name of product</th><th>Host url</th><th></th><th></th></tr>');
//                                                            $("#sites tbody").html(resultAjax);
//                                                            $("#clear").show();
//                                                        }
//                                                    }


                                    }
                            });
                        }
                    }
                    
                    function runAjax(){
                        for(i=0;i<siteIds.length;i++){
                            siteId=siteIds[i];
                            console.log(siteId);
                            console.log($("tr#site_"+siteId).hasClass($('form#search_form1 input[type="text"]').val().trim()));
                            runAjaxForOneSite(siteId)

                        }
                    }

                    function delete_btn() {
                        var confirmi = confirm('Do you want to delete ?');
                        if(!confirmi) {
                            return true;
                        }
                        $('#delete_id').val($('#id').val());
                        $('#delete_id').parent().submit();
                    }
                    function edit_site(id) {
                        $('#id').val(id);
                        $('#name').val($.trim($('#site_'+id).data('name')));
                        $('#url').val($.trim($('#site_'+id).data('url')));
                        $('#search_parameter').val($.trim($('#site_'+id).data('search_parameter')));
                        $('#search_result_parameter').val($.trim($('#site_'+id).data('search_result_parameter')));
                        $('#detail_parameter').val($.trim($('#site_'+id).data('detail_parameter')));
                        $('#video_parameter').val($.trim($('#site_'+id).data('video_parameter')));
                        $('#product_parameter').val($.trim($('#site_'+id).data('product_parameter')));
                        $('#test_dvdcode').val('rctd-034');

                        var video_host = $('#site_'+id).data('video_host');
                        $('#video_host_'+video_host).prop('checked',true);
                        $('.list').hide();
                        $('.edit_form').show();
                        $('.test_area').hide();
                        $('#test_video').hide();
                    }
                    function add_site() {
                        $('#id').val();
                        $('#name').val('');
                        $('#url').val('');
                        $('#search_parameter').val('');
                        $('#detail_parameter').val('');
                        $('#product_parameter').val('');
                        $('#search_result_parameter').val('');
                        $('#video_parameter').val('');
                        $('#test_dvdcode').val('rctd-034');
                        var video_host = 'openload';
                        $('#video_host_'+video_host).prop('checked',true);
                        $('.list').hide();
                        $('.add_form').show();
                        $('.test_area').hide();
                        $('#test_video').hide();
                    }
                    function home() {
                        window.location.href = 'index.php';
                    }
                    function test() {
                        $('.test_area').hide();
                        $('#test_video').hide();
                        $('#loading').show();
                        $.ajax({
                            url: "ajax.php",
                            method: 'post',
                            data: {
                                dvdcode: $('#test_dvdcode').val(),
                                url: $('#url').val(),
                                search_parameter: $('#search_parameter').val(),
                                search_result_parameter: $('#search_result_parameter').val(),
                                detail_parameter: $('#detail_parameter').val(),
                                video_parameter: $('#video_parameter').val(),
                                product_parameter: $('#product_parameter').val()
                            },
                            dataType: 'json',
                            success: function(result){
                                $('#loading').hide();
                                if(result.url != '' || result.title != '' || result.embed != '') {
                                    $('#test_name').html($('#name').val());
                                    $('#test_url').html(result.url);
                                    $('#test_title').html(result.title);
                                    $('#test_host').html(result.embed);
                                    if(result.embed != '' && result.embed != 'embed not found') {
                                        $('#test_video').attr('src',result.embed);
                                        $('#test_video').show();
                                    }
                                    $('.test_area').show();
                                }
                            }
                        });
                    }
                </script> 
	</body>
</html>