function getCurrentVideos() {
    var alert = $("#alert");
    $.ajax({
        type: 'post',
        url: '/ajax/ajax.php',
        data: {action: 'getcurrentvideos', dvdcode: $("#dvd_code").val().trim(), number_result: $('#number_result').val()},
        async: false,
        success: function (result) {

            console.log(result);
            console.log('result');
            var data = JSON.parse(result);
            if (data.status == '0') {
                $(alert).html('<p class="alert alert-danger">Not Found</p>');
            } else if (data.status == '2') {
                $(alert).html('<p class="alert alert-warning">The videos is exits in database.</p>');
            } else if (data.status == '1') {
                $(alert).html('<p class="alert alert-info">Found the Videos</p>');
                $("#home_page #sitem-table tbody").html(data.html);
                $("#csv_export button").removeAttr('disabled').css('cursor', 'pointer');
            } else {
                $(alert).html('<p class="alert alert-danger">Error</p>');
            }

            $(alert).css('visibility', 'visible');
            $("#datacount").html($("#home_page #sitem-table tbody tr").length)
        }
    });
}
$(function () {

	//delete callback
//	$(document).ajaxComplete(function(event, request, settings){
//		delete_dvd_code();
//		show_sites_details();
//		show_source_details();
//	});


	//function
	search_videos();
	delete_video_notrack();
	delete_dvd_code();
	add_dvd_code();
	cron_update_track_videos();
	show_sites_details();
	show_source_details();
	stops_cron_videos();
        if($("#sitem-table .site_detail").length>0||$("#sitem-table .source_detail").length>0){
            $("#csv_export button").removeAttr('disabled').css('cursor','pointer');
        }
	//setInterval
//        tuetc
//	if($("body#track_page").length>0){
//		setInterval(updatecronstatus, 4000);
//	}

	// --------------------------------MODAL--------------------------------

	//dvd_code
	$('.dvd_code').click(function(){

		var msg = $('.add-code-msg');
		msg.html('');
		msg.hide();
		$('#code_value').val('');

		$('#dvd_code').modal('show');
		return false;
	});

	//erase data
	$('#erase_data').click(function(){
		$('#erase_all_data').modal('show');
	});
	// --------------------------------END MODAL--------------------------------

	// --------------------------------HOME-------------------------------------
	//search_videos
	function search_videos(){

		$('form#search_form').on('submit', function (e) {
                    stopClicked=false;
                    $('#btn-start').attr('disabled','disabled').css('cursor','not-allowed');
                    $("#csv_export button").attr('disabled','disabled').css('cursor','not-allowed');
                    $("#datacount").html('0');
                    $("#home_page #sitem-table tbody").html('');
			//reset alert
			var alert = $("#alert");
			var dvd_code = $("#dvd_code");
			$(alert).html('');
			$(alert).css('visibility', 'hidden');
			if(dvd_code.val().trim()==''){
				$(alert).html('<p class="alert alert-danger">Please put a DVD code</p>');
				$(alert).css('visibility', 'visible');
                                $('#btn-start').removeAttr('disabled').css('cursor','pointer');
				return false;
			}
			else{

				e.preventDefault();
				$.ajax({
					type: 'post',
					url: '/ajax/ajax.php',
					data: {action: 'findvideos', dvdcode : $(dvd_code).val().trim(), api_scraper: $('#api_scraper').val(),number_result:$('#number_result').val()},
					async: true,
					beforeSend: function () {
						$('#home_page #loaddingbar').show();
					},
					complete: function () {
						$('#home_page #loaddingbar').hide();
					},
					success: function (result) {
                                            if(stopClicked){
                                                return;
                                            }
                                            $('#btn-start').removeAttr('disabled').css('cursor','pointer');
                                                console.log(result);
                                                console.log('result');
						var data = JSON.parse(result);
						if(data.status=='0'){
                                                    $(alert).html('<p class="alert alert-danger">Not Found</p>');                                                    
						}
						else if(data.status=='2'){
							$(alert).html('<p class="alert alert-warning">The videos is exits in database.</p>');
						}
						else if(data.status=='1'){
							$(alert).html('<p class="alert alert-info">Found the Videos</p>');
							$("#home_page #sitem-table tbody").html(data.html);
                                                        $("#csv_export button").removeAttr('disabled').css('cursor','pointer');
						}
						else{
							$(alert).html('<p class="alert alert-danger">Error</p>');
						}
                                                $('#home_page #loaddingbar').hide();
						$(alert).css('visibility', 'visible');
                                                
                                                $("#datacount").html($("#home_page #sitem-table tbody tr").length)
					}
				});
			}//end else

		});//end submit
	}
        
        

	

	//delete all videos no track
	function delete_video_notrack(){

		$('#delete').on('click', function (e) {
                    $('.close').click();
                    e.preventDefault();
                    $("#sitem-table tbody").html('');
                    $("#csv_export button").attr('disabled','disabled').css('cursor','not-allowed');

			return false;
		});
	}
	


	// --------------------TRACK-------------
	//add_dvd_code
	function add_dvd_code(){

		$('#code_submit').click(function(){

			var msg = $('.add-code-msg');
			msg.html('');
			msg.hide();

			var alert = $("#alert");
			$(alert).html('');
			$(alert).css('visibility', 'hidden');

			var code_val = $('#code_value').val().trim();
			if(code_val==''){
				msg.html('<p class="alert alert-danger">Please put a DVD code</p>');
				msg.show();
			}
			else{

				$.ajax({
				type: 'post',
				url: '/ajax/ajax.php',
				data: {action: 'addDVDcode','code': code_val },
				beforeSend: function () {
					$('#loading').show();
				},
				complete: function () {
					$('#loading').hide();
				},
				success: function (result) {
						var data = JSON.parse(result);
						$('#loading').hide();
						if(data.status=='0'){
							msg.html('<p class="alert alert-danger">The code is exits in database</p>');
						}
						else if(data.status=='1'){
							alert.html('<p class="alert alert-info">Added Successfully</p>');
							alert.css('visibility', 'visible');
							$("#track_page #sitem-table tbody").html(data.html);
							$('.close-btn').click();
						}
						msg.show();
					}
				});
				
			}
			return false;
		});
	}

	
	//delete_dvd_code
	function delete_dvd_code(){
		//delete
		$('.delete').on('click', function (e) {
			
			var alert = $("#alert");
			alert.html('');
			alert.css('visibility', 'hidden');

			e.preventDefault();
			$.ajax({

				type: 'post',
				url: '/ajax/ajax.php',
				data: {action: 'deleteDVDcode','id': $(this).attr('data-id') },
				beforeSend: function () {
					$('#loading').show();
				},
				complete: function () {
					$('#loading').hide();
				},
				success: function (result) {
					var data = JSON.parse(result);
					$('#loading').hide();
					if(data.status=='0'){
						alert.html('<p class="alert alert-danger"> can\'t delete</p>');
					}
					else if(data.status=='1'){
						alert.html('<p class="alert alert-info">Deleted success</p>');
						$("#track_page #sitem-table tbody").html(data.html);
					}
					alert.css('visibility', 'visible');
				}
			});
			
			return false;
		});
	}

	//cron_update_track_videos
	function cron_update_track_videos(){
		$('#cron-start').click(function(e){
                    
                    $(this).attr('disabled','disabled').css('cursor','not-allowed');

			var alert = $("#track_page #alert");
			alert.html('');
			alert.css('visibility', 'hidden');

			e.preventDefault();
                        $('#track_page #loaddingbar').show();
			$.ajax({

				type: 'post',
				url: '/ajax/ajax.php',
//                                async: false,
				data: {action: 'startCronTrackCode' , api_scraper: $('#api_scraper').val(),number_result:$('#number_result').val()},
				beforeSend: function () {
					$('#track_page #loaddingbar').show();
				},
				complete: function () {
					$('#track_page #loaddingbar').hide();
				},
				success: function (result) {
                                    $('#cron-start').removeAttr('disabled').css('cursor','pointer');
//					updatecodesresults();
                                        console.log(result);
					var data = JSON.parse(result);
					$('#track_page #loaddingbar').hide();
					if(data.status=='0'){
						$(alert).html('<p class="alert alert-warning">can\'t Update or database is latest.</p>');
					}
					else if(data.status=='1'){
						$(alert).html('<p class="alert alert-info">Database is Updated</p>');
						$("#track_page #sitem-table tbody").html(data.html);
                                                $("#csv_export button").removeAttr('disabled').css('cursor','pointer');
					}
					else{
						$(alert).html('<p class="alert alert-danger">Error</p>');
					}
					$(alert).css('visibility', 'visible');
				}
			});

			//return
			return false;
		});
	}

	//stops_cron_videos
	function stops_cron_videos(){
		$('#track_page #stop').on('click', function (e) {
			$.ajax({
				type: 'post',
				url: '/ajax/ajax.php',
				data: {action: 'stopcronvideos'},
				success: function (result) {
					updatecodesresults();
				}
			});
			return false;
		});
	}

	//updatecodesresults
	function updatecodesresults(){

		if($("body#track_page").length>0){

			$.ajax({
				type: 'post',
				url: '/ajax/ajax.php',
				data: {action: 'updatecodesresults'},
				success: function (result) {
					var data = JSON.parse(result);
					$("#track_page #sitem-table tbody").html(data.html);
                                        $("#csv_export button").removeAttr('disabled').css('cursor','pointer');
					if(data.loadding=='1'){
						$('#track_page #loaddingbar').show();
					}
					else{
						$('#track_page #loaddingbar').hide();
					}
				}
			});
		}
		
	}

	//updatecronstatus
	function updatecronstatus(){

		if($("body#track_page").length>0){

			$.ajax({
				type: 'post',
				url: '/ajax/ajax.php',
				data: {action: 'updatecronstatus'},
				success: function (result) {
					var data = JSON.parse(result);
					if(data.loadding=='1'){
						$('#track_page #loaddingbar').show();
					}
					else{
						$('#track_page #loaddingbar').hide();
					}
				}
			});
		}
		
	}


	//show_sites_details
	function show_sites_details(){
		//site_detail
		$('.site_detail').unbind("click").on( "click", function() {

			$.ajax({
				type: 'post',
				url: '/ajax/ajax.php',
				data: {action: 'showsitesdetails','id': $(this).attr('data-id')},
				beforeSend: function () {
					$('#loading').show();
				},
				complete: function () {
					$('#loading').hide();
				},
				success: function (result) {
					var data = JSON.parse(result);
					$("#track_page #site_detail .list-group").html(data.html);
					$('#loading').hide();
					$('#site_detail').modal('show');
				}
			});

			return false;
		});
	}

	//show_source_details
	function show_source_details(){
		//source_detail
		$('.source_detail').unbind("click").on( "click", function() {

			$.ajax({
				type: 'post',
				url: '/ajax/ajax.php',
				data: {action: 'showsourcedetails','id': $(this).attr('data-id')},
				beforeSend: function () {
					$('#loading').show();
				},
				complete: function () {
					$('#loading').hide();
				},
				success: function (result) {
					var data = JSON.parse(result);
					$("#track_page #source_detail .list-group").html(data.html);
					$('#loading').hide();
					$('#source_detail').modal('show');
				}
			});

			return false;
		});
	}


	// --------------------END TRACK-------------
	
});