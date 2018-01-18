<!DOCTYPE html>
<!--[if lt IE 7]>
<html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]>
<html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]>
<html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<title>case place</title>
	<meta name="description" content=""/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0" />-->
	<link rel="shortcut icon" href="favicon.png"/>
	<link rel="stylesheet" href="/case_place/libs/bootstrap/bootstrap-grid-3.3.1.min.css"/>
	<link rel="stylesheet" href="/case_place/libs/font-awesome-4.2.0/css/font-awesome.min.css"/>
	<link rel="stylesheet" href="/case_place/libs/fancybox/jquery.fancybox.css"/>
	<link rel="stylesheet" href="/case_place/libs/owl-carousel/constr/owl.carousel.css"/>
	<link rel="stylesheet" href="/case_place/libs/countdown/jquery.countdown.css"/>
	<link rel="stylesheet" href="/case_place/css/fonts.css"/>
	<link rel="stylesheet" href="/case_place/css/main.css"/>
	<link rel="stylesheet" href="/case_place/css/media.css"/>
	<link rel="stylesheet" type="text/css" href="/case_place/css/popupcart_extended.css" media="screen"/>
	<link rel="stylesheet" href="/case_place/css/style.css"/>

</head>
<body>
<div class="bg2">
	<!--подсказки-->
	<div class="instruct text14 win1">
		<div class="x">
			<img src="/case_place/img/construktor/x.png">
		</div>
		<div class="row instruct_head"> Инструкция</div>
		<div class="body_foot">
			Модель:
			<div class="col1 col2">


				<select class="inputtext  phone">

					<option value="Чебурашка">Чебурашка</option>
					<option selected value="Крокодил Гена"> iPhone 6/6S</option>
					<option value="Шапокляк">Шапокляк</option>
					<option value="Крыса Лариса">Крыса Лариса</option>
				</select>
			</div>
			<div class="text_foot">1. Выберете модель</div>
		</div>

		<div class="row instruct_foot">
			<div class="line_foot"></div>
			<div class="col-xs-6 bottom_foot">
				Назад
			</div>

			<div class="col-xs-6 bottom_foot">
				Дальше
			</div>
		</div>
	</div>


	<!--Выберете модель-->
	<div class="instruct text14 win2">
		<div class="row instruct_head"> Выберете модель</div>
		<div class="body_foot2 ">
			<div class="col-xs-5">
				Марка:
				<div class="col1 col2 phone2wid">
					<select class="inputtext  phone2 wid">

						<option value="Чебурашка">Чебурашка</option>
						<option selected value="Крокодил Гена"> Apple</option>
						<option value="Шапокляк">Шапокляк</option>
						<option value="Крыса Лариса">Крыса Лариса</option>
					</select>
				</div>
			</div>
			<div class="col-xs-4">
				Модель:
				<div class="col1 col2 phone3wid">
					<select class="inputtext  phone3">

						<option value="Чебурашка">Чебурашка</option>
						<option selected value="Крокодил Гена"> iPhone 6/6S</option>
						<option value="Шапокляк">Шапокляк</option>
						<option value="Крыса Лариса">Крыса Лариса</option>
					</select>
				</div>
			</div>

		</div>

		<div class="bottom_select_model"><span class="bottom_select_model2"> Применить</span></div>


	</div>

	<!--popup-->


	<div class="popup_overlay" onClick="popup_out();"></div>

	<div class="popup" id="thx">
		<div class="popup_close noselect" onClick="popup_out();"><span>&nbsp;</span></div>
		<div class="popup_h1">Спасибо за оставленную заявку</div>
		<div class="popup_h2">Наш менеджер свяжется с вами в ближайшее время</div>
		<div class="btn" onClick="popup_out();">закрыть</div>
	</div>

	<div class="popup" id="carz">
		<div id="popupcart_extended">
			<div class="head">Корзина покупок&nbsp;&nbsp; <img onClick="popup_out();" class="close"
			                                                   src="/case_place/img/remove-small.png"/></div>
			<div class="empty">Ничего не куплено!</div>
		</div>
	</div>


	<input type="hidden" name="prefix" class="prefix">
	<input type="hidden" name="phone_format" class="phone_format">
	<input type="hidden" name="referer" value="">
	<input type="hidden" name="ref_url" value="">
	<input type="hidden" class="formname" name="formname" value="">
	<input type="hidden" class="sitename" name="sitename" value="">
	<input type="hidden" class="emailsarr" name="emailsarr" value="">


	<!--end popup-->


	<div class="row bggrey">
		<div class="container mar2">

			<nav class="nav2">
				<div class="col-xs-1 pa">
					<a class="navbar-brand link" href="#"><img src="/case_place/img/logo2.png"></a>
				</div>
				<div class="col-xs-5">
					<ul class="menu2">
						<li class=""><a href="#">Как это работает</a></li>
						<li><a href="#">Доставка</a></li>
						<li><a href="#">Контакты</a></li>


					</ul>
				</div>

				<div class="col-xs-3">
					<div class="tright menu2"><a href="tel:8 (800) 500 79 37"> 8 800 500 79 37</a><br><span
								class="bordred">Ежедневно с 11:00 до 20:00 <span></div>
				</div>
				<div class="col-xs-3">
					<div class="corzin carz">
						<div class="korzimg"><img src="/case_place/img/korzin.png"></div>
						<div class="korztext">Корзина</div>
						<div class="korznum"> 1</div>
					</div>
				</div>

			</nav>
		</div>

	</div>

	<div class="row centr ">
		<div class="line"><img src="/case_place/img/line.png"></div>
		<div class="container center3 ">
			<div class="col-xs-3 bggrey pad1020 text14 wi">
				<div class="col1">
					<div class="col-xs-6 fotoname ON sfoto center2">
						С фото
					</div>
					<div class="col-xs-6 fotoname sname center2">
						С именем
					</div>
				</div>

				Модель:
				<div class="col1 col2">
					<div class="col-xs-10 fotoname ">
						iPhone 6/6S
					</div>
					<div class="col-xs-2 fotoname img_to4ki">
						<img src="/case_place/img/construktor/to4ki.png">
					</div>
				</div>
				Материал
				<div class="col1 col2">
					<select class="inputtext text14 metall">

						<option value="Чебурашка">Чебурашка</option>
						<option selected value="Крокодил Гена"> Пластик матовый</option>
						<option value="Шапокляк">Шапокляк</option>
						<option value="Крыса Лариса">Крыса Лариса</option>
					</select>


				</div>
				Шаблон:
				<div class="colsablon ">
					<div class="shablon shablonON shablon1">
						<img src="/case_place/img/construktor/shablon1.png">
					</div>
					<div class="shablon shablon2">
						<img src="/case_place/img/construktor/shablon2.png">
					</div>
				</div>
				<div class="col1 col2 ">
					<div class=" colorcase fotoname center2 ">
						Цвет чехла
					</div>
					<div class=" colortext fotoname center2 ON">
						Цвет надписи
					</div>
				</div>
				<div class="">
					<div class="kub">
						<div class="kubcolor color1 "></div>

					</div>
					<div class="kub">
						<div class="kubcolor color2"></div>
					</div>
					<div class="kub kubcolorON">
						<div class="kubcolor color3 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color4 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color5 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color6 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color7 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color8 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color9 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color10 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color11 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color12 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color13 "></div>
					</div>
					<div class="kub ">
						<div class="kubcolor color14 "></div>
					</div>
				</div>
				<div class="palitrafon">
					<div class="lab lab1"></div>
					<div class="lab lab2"></div>

					<div class="palitra borl">
						<img src="/case_place/img/construktor/kvadrat.png">
						&nbsp;Палитра
					</div>
					<div class="fon palitra">
						<img src="/case_place/img/construktor/fon.png">
						&nbsp;Фон
					</div>
					<div class="palitra plus borr">
						<span class="textplus">+</span>
					</div>

				</div>
				<div class="slidfon  owl-carousel owl-theme" id="owl-constr">
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
					<div class="slidfon1 item"></div>
				</div>


				<div class="textgotov">Готовые решения:</div>
				<div class="pes">

					<div class="gotovie"></div>
					<div class="gotovie"></div>
					<div class="gotovie"></div>
					<div class="gotovie"></div>
					<div class="gotovie"></div>
					<div class="gotovie"></div>
					<div class="gotovie"></div>
					<div class="gotovie"></div>
				</div>
			</div>
			<div class="col-xs-6 wi6">


				<div>
					<svg id="svg" style="display: block;" width="300" height="500"></svg>
				</div>
				<div class="imgvastext">
					<div class="vastext"></div>
				</div>

				<div class="text14">Введите текст</div>
				<input type="text" class="text14 inputtext">
				<div class="text14">Выберете шрифт</div>
				<select class="inputtext selectfont">

					<option value="Чебурашка">Чебурашка</option>
					<option selected value="Крокодил Гена">BEBAS</option>
					<option value="Шапокляк">Шапокляк</option>
					<option value="Крыса Лариса">Крыса Лариса</option>
				</select>
				<div class="bottom5 text14">Применить</div>
			</div>
			<div class="col-xs-3 bggrey pad1020 wi text14 size2 pad40">
				<div>Инструменты:</div>

				<div class=" ladel3">
					<div class="col-xs-2 ">
						<div class="del"><img src="/case_place/img/construktor/del.png"></div>
					</div>
					<div class="o4istit col-xs-8">Отчистить</div>
				</div>
				<div class="ladel4">
					<div class="col-xs-2 ">
						<div class="del delbg"><img src="/case_place/img/construktor/ico.png"></div>
					</div>
					<div class=" col-xs-9  nosing">Ничего не получается,
						сделайте за меня!
					</div>
				</div>

				<div class="line1"></div>
				<div class="blok pad1020 pad40">
					Цена одного чехла:
					<div class="pricepoz"><span class="price">690p.</span></div>

					Цена при заказе от 2-х<br>любых чехлов:
					<div class="pricepoz"><span class="price">495p.</span></div>
					<div class="bottom6mar"><span class="bottom6 ">Добавить в корзину</span></div>
				</div>

			</div>
		</div>
	</div>


	<!--[if lt IE 9]>
	<script src="/case_place/libs/html5shiv/es5-shim.min.js"></script>
	<script src="/case_place/libs/html5shiv/html5shiv.min.js"></script>
	<script src="/case_place/libs/html5shiv/html5shiv-printshiv.min.js"></script>
	<script src="/case_place/libs/respond/respond.min.js"></script>
	<![endif]-->
	<script src="/case_place/libs/jquery/jquery-1.11.1.min.js"></script>
	<script src="/case_place/libs/jquery-mousewheel/jquery.mousewheel.min.js"></script>
	<script src="/case_place/libs/fancybox/jquery.fancybox.pack.js"></script>
	<script src="/case_place/libs/waypoints/waypoints-1.6.2.min.js"></script>
	<script src="/case_place/libs/scrollto/jquery.scrollTo.min.js"></script>
	<script src="/case_place/libs/owl-carousel/constr/owl.carousel.js"></script>
	<script src="/case_place/libs/countdown/jquery.plugin.js"></script>
	<script src="/case_place/libs/countdown/jquery.countdown.min.js"></script>
	<script src="/case_place/libs/countdown/jquery.countdown-ru.js"></script>
	<script src="/case_place/libs/landing-nav/navigation.js"></script>
	<script src="/case_place/js/common.js"></script>
	<!-- Yandex.Metrika counter --><!-- /Yandex.Metrika counter -->
	<!-- Google Analytics counter --><!-- /Google Analytics counter -->
	<script type="text/javascript" src="/case_place/js/plugins.js"></script>

	<!-- editor init -->
	<script src="/ed/libs/snap.svg-min.js" type="text/javascript"></script>
	<script src="/ed/app.js" type="text/javascript"></script>
	<!-- editor end -->

	<a href="#" class="scrollup link">Наверх</a>
</div>
</body>
</html>

