<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="">
	
	<link rel="stylesheet" href="/css/main.css">
	<link rel="stylesheet" href="/vendors/normalize-css/normalize.css">
	<link rel="stylesheet" href="/vendors/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/vendors/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/vendors/themes/gentelella/css/custom.min.css">
	<link rel="stylesheet" href="/vendors/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" href="/vendors/jquery-ui-1.12.1/jquery-ui.theme.min.css">

	<!-- iCheck -->
	<link rel="stylesheet" href="/vendors/iCheck/skins/flat/green.css">
	<!-- Switchery -->
	<link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">

	<link rel="stylesheet" href="/css/main.css">

	<link rel="stylesheet" href="/css/media.css">


	<!-- PNotify -->
	<link href="/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
	<link href="/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
	<link href="/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

	<!-- select2 -->
	<link href="/vendors/select2/dist/css/select2.min.css" rel="stylesheet">

	<title>Project</title>


	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="nav-md">
	<div id="preloader">
		<img src="/img/content/preloader.gif" alt="">
	</div>
	<div class="container body">
		<div class="main_container">

			<div class="col-md-3 left_col">
				<div class="left_col scroll-view">

					<div class="navbar nav_title" style="border: 0;">
						<a href="{{ url('/') }}" class="site_title">
							<i class="fa fa-paw"></i>
							<span>AGRO CRM!</span>
						</a>
					</div>

					<div class="clearfix"></div>

					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							@include('_common.admin_menu')
						</div>
					</div>
					<!-- /sidebar menu -->
				</div>

			</div>

			<!-- top navigation -->
			<div class="top_nav">
				<div class="nav_menu">
					<nav class="" role="navigation">

						<div class="nav toggle">
							<a id="menu_toggle">
								<i class="fa fa-bars"></i>
							</a>
						</div>

					</nav>
				</div>
			</div>
			<!-- /top navigation -->

			<!-- page content -->
			<div class="right_col" role="main" style="min-height: 945px;">
				<div class="page-title">
					<div class="title_left">
						<h3>@yield('pageTitle')</h3>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					@yield('content')
				</div>
			</div>
			<!-- /page content -->

			<!-- /page content -->

			<!-- footer content -->
			<footer>
				<div class="pull-right">
					Agro crm</a>
				</div>
				<div class="clearfix"></div>
			</footer>
			<!-- /footer content -->
		</div>
	</div>

	<!-- Scripts -->
	<script src="/vendors/jquery/dist/jquery.min.js"></script>
	<script src="/vendors/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="/vendors/themes/gentelella/js/custom.min.js"></script>
	<script src="/vendors/validator/validator.js"></script>
	<script src="/vendors/iCheck/icheck.min.js"></script>
	<script src="/vendors/switchery/dist/switchery.min.js"></script>

	<!-- PNotify -->
	<script src="/vendors/pnotify/dist/pnotify.js"></script>
	<script src="/vendors/pnotify/dist/pnotify.buttons.js"></script>
	<script src="/vendors/pnotify/dist/pnotify.nonblock.js"></script>

	<!-- select2 -->
	<script src="/vendors/select2/dist/js/select2.full.min.js"></script>
	
	<!-- PNotify -->
    <!-- <script>
      $(document).ready(function() {
        new PNotify({
          title: "PNotify Warning /PNotify + init required!!!",
          type: "info",
          text: "Welcome. Try hovering over me. You can click things behind me, because I'm non-blocking.",
          nonblock: {
              nonblock: true
          },
          addclass: 'dark',
          styling: 'bootstrap3',
          hide: false,
          before_close: function(PNotify) {
            PNotify.update({
              title: PNotify.options.title + " - Enjoy your Stay",
              before_close: null
            });

            PNotify.queueRemove();

            return false;
          }
        });

      });
    </script> -->
    <!-- /PNotify -->

    <!-- i-autocomplete init -->
	<script>
		$( function() {
			var availableTags = [
				"ActionScript",
				"AppleScript",
				"Asp",
				"BASIC",
				"C",
				"C++",
				"Clojure",
				"COBOL",
				"ColdFusion",
				"Erlang",
				"Fortran",
				"Groovy",
				"Haskell",
				"Java",
				"JavaScript",
				"Lisp",
				"Perl",
				"PHP",
				"Python",
				"Ruby",
				"Scala",
				"Scheme"
			];
			$( "#ui-autocomplete" ).autocomplete({
				source: availableTags
			});
		} );
	</script>
	<!-- i-autocomplete init end-->

	<!-- select2_single init end-->
	<script>
		$(document).ready(function() {
			$('.select2_group').each(function() {
				$(this).select2({
						placeholder: "Select a state",
						allowClear: true
					});
					$(".select2_group").select2({});
					$(".select2_multiple").select2({
						maximumSelectionLength: 4,
						placeholder: "With Max Selection limit 4",
						allowClear: true
				});
			});
		});
	</script>
	<!-- select2_single init-->

	<!-- filters toggle -->
	<script>
		$('#filtersbtn').on('click', function() {
			$('#members-filters').toggleClass('open');
			$(this).toggleClass('open');
		});
	</script>
	<!-- filters toggle end-->

	{{-- @TODO пока сюда потом на ту страницу куда надо --}}
	<script type="text/javascript" src="/vendors/jquery.mask.min.js"></script>
	{{--@TODO: Сделать уведомления о ошибках --}}
	<script src="/js/main.js"></script>

	@if ($errors->any())
		@include('_common.error_info', ['errorMessages' =>  $errors->all(':message')]);
	@endif


	@yield('bottomScript')

	<script type="text/javascript">
		//preloaderHide();
	</script>
</body>
</html>
