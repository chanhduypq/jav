<?php 
include_once 'config.php';
include_once 'ajax.php';

@set_time_limit(0);
@ini_set('max_execution_time', 0);
@error_reporting(E_ALL);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL);
@ini_set("auto_detect_line_endings", true);

require_once 'simple_html_dom.php';
//$data = easy_add('myjavlibrary.net');
//    exit(json_encode($data));
    
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
            header('Location: ../index.php');
            exit;
        }
    }
}
//var_dump(easy_add('https://anon-v.com'));
//exit;
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
                height: 43px;
                padding: 0 20px;
                line-height: 43px
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
            div.my_loading {
                z-index: 99;
                position: fixed;
                width: 100%;
                height: 100vh;
                background: lightgrey;
                opacity: 0.3;
                display: none;
            }
            img.my_loading {
                display: none;
                position: fixed;
                z-index: 100;
                width: 270px;
                height: 200px;
                top: calc(50% - 100px);
                left: calc(50% - 135px);
            }
            
            input.readonly{
                cursor: not-allowed;
                background-color:#dddddd;
                display: none;
            }
            label.readonly{
                display: none;
            }
        </style>
	</head>
	<body id="track_page">
        <div class="my_loading">
        </div>
        <img src="loading.gif" class="my_loading">
		<div class="container">
			<?php include_once '../../menu.php';?>
		</div>
				   
		<div class="container" style="min-height: 400px;">
            <div class="clear10"></div>
            <h3 class="add_form edit_form cur-poi" onclick="home()">HOME</h3>
            <h3 class="add_form edit_form brak"> > </h3>
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

            <div class="content add_form edit_form">
                <form method="post">
                    <input type="hidden" id="id" name="id" <?php if(!empty($_POST['id'])) { echo 'value="'.$_POST['id'].'"'; }?>>
                    <label class="readonly">Site name</label>
                    <input class="readonly" readonly="readonly" type="text" id="name" name="name" placeholder="Site Name" <?php if(!empty($_POST['name'])) { echo 'value="'.$_POST['name'].'"'; }?> required>
                    <label>Url</label>
                    <input type="text" id="url" name="url" placeholder="Please enter url" <?php if(!empty($_POST['url'])) { echo 'value="'.$_POST['url'].'"'; }?> required>
                    <label></label>
                    <input onclick="easy_add()" type="button" class="btn" value="Easy Add" style="padding: 10px;">
                    <div style="clear:both;height:0"></div>
                    <label class="readonly">Search parameter</label>
                    <input class="readonly" readonly="readonly" type="text" id="search_parameter" name="search_parameter" placeholder="Search parameter" <?php if(!empty($_POST['search_parameter'])) { echo 'value="'.$_POST['search_parameter'].'"'; }?> required>
                    <label class="readonly">Search result parameter</label>
                    <input class="readonly" readonly="readonly" type="text" id="search_result_parameter" name="search_result_parameter" placeholder="Search result parameter" <?php if(!empty($_POST['search_result_parameter'])) { echo 'value="'.$_POST['search_result_parameter'].'"'; }?> required>
                    <label class="readonly">Detail page parameter</label>
                    <input class="readonly" readonly="readonly" type="text" id="detail_parameter" name="detail_parameter" placeholder="Detail page parameter" <?php if(!empty($_POST['detail_parameter'])) { echo 'value="'.$_POST['detail_parameter'].'"'; }?> required>
                    <label class="readonly">Video parameter</label>
                    <input class="readonly" readonly="readonly" type="text" id="video_parameter" name="video_parameter" placeholder="Video parameter" <?php if(!empty($_POST['video_parameter'])) { echo 'value="'.$_POST['video_parameter'].'"'; }?> required>
                    <div class="clear10"></div>
                    <input disabled="disabled" class="readonly" readonly="readonly" style="margin-left: 175px" type="radio" id="video_host_openload" name="video_host1" value="openload"><label class="readonly" style="margin-left: 10px;">Openload</label>
                    <div class="clear10"></div>
                    <input disabled="disabled" class="readonly" style="margin-left: 175px" type="radio" id="video_host_google_drive" name="video_host1" value="google drive"><label class="readonly" style="margin-left: 10px;">Google Drive</label>
                    <div class="clear10"></div>
                    <label class="readonly">Product name parameter</label>
                    <input class="readonly" readonly="readonly" type="text" id="product_parameter" name="product_parameter" placeholder="Product name parameter" <?php if(!empty($_POST['product_parameter'])) { echo 'value="'.$_POST['product_parameter'].'"'; }?> required>

                    <input style="float:left;height:43px;width: 300px;margin-right: 20px;margin-left: 175px;display: none;" type="text" id="test_dvdcode" placeholder="dvd code" value="rctd-034"><input class="btn" style="width: auto;height:43px;display: none;" onclick="test()" type="button" value="Test">
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
                    <input id="button_save" style="height:43px;width: auto;margin-left: 175px;margin-right: 20px;padding:0 20px;display: none;" type="submit" value="Save">
                    <input onclick="delete_btn()" class="edit_form btn" style="padding:0 20px;height:43px;width: auto;margin-left: 0;background: firebrick" type="button" value="Delete">
                    <input type="hidden" name="video_host"/>
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
            var stopClicked=false;
            
            $( function() {
                add_site();
                

                $('.list').show();
                <?php if($page == 'add_form') { echo 'add_site();'; } ?>
                $('.message').delay(3000).fadeOut('slow');

            });

            function easy_add() {
                var url = $.trim($('#url').val());
                
                if(url == '') {
                    alert('Please enter url');
                    return false;
                }
                $('.my_loading').show();
                $.ajax({
                    type: 'post',
                    url: 'ajax.php',
                    data: {
                        action: 'easy_add',
                        url: url
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('.readonly').show();
                        $('.my_loading').hide();
                        url=result.url;
                        url=url.replace('https://','');
                        url=url.replace('http://','');
                        temp=url.split('.');
                        $('#name').val(temp[0]);
                        $('#url').val(result.url);
                        $('#search_parameter').val(result.search_parameter);
                        $('#search_result_parameter').val(result.search_result_parameter);
                        $('#detail_parameter').val(result.detail_parameter);
                        $('#video_parameter').val(result.video_parameter);
                        $('#product_parameter').val(result.product_parameter);
                        $('#video_host_'+result.video_host).prop('checked',true);
                        $('#button_save').show();
                        $('input[name="video_host"]').val(result.video_host);
                        if(result.message != '') {
                            alert(result.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(status);
                        console.log(error);
                        console.log(xhr.responseText);
                    }
                });
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
                window.location.href = '../index.php';
            }
            function test() {
                $('.test_area').hide();
                $('#test_video').hide();
                $('.my_loading').show();
                $.ajax({
                    url: "ajax.php",
                    method: 'post',
                    data: {
                        action: 'test_site',
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
                        $('.my_loading').hide();
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