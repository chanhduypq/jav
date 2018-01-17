<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Ultra Sound Experiment</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
		<link rel="stylesheet"  href="css/styles.css" >
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body id="track_page">
		<div class="container">
			<?php include_once 'menu.php';?>
		</div>
				   
		<div class="container" style="min-height: 400px;">


                    <div class="row">
                        <div class="col-md-12">
                            <div id="tabs" style="min-height: 400px;margin-bottom: 50px;">
                              <ul>
                                <li><a href="#tabs-1">Demo Finger Print</a></li>
                                <li><a href="#tabs-2">Listening</a></li>
                              </ul>
                              <div id="tabs-1" style="padding-left: 50px;">
                                <div style="margin-bottom: 100px;">
                                    <strong>Type text in</strong><br><br>
                                    <textarea id="textbox" cols="100" rows="10" style="width: 50%;"></textarea>
                                    <div style="width: 50%;margin-top: 20px;">
                                        <label style="float: left;">
                                        <input type="number" id="second" style="width: 30px;" value="5"/>&nbsp;&nbsp;&nbsp;Secs Delay before next loop
                                        </label>
                                        <button class="render" type="submit" style="float: right;">Play ultra sound loop</button>
                                    </div>
                                </div>
                              </div>
                              <div id="tabs-2" style="padding-left: 50px;">
                                <p>pending</p>
                              </div>
                            </div>
                            <div style="float: left;margin-left: 10%;">
                                <iframe src="http://www.youtube.com/embed/1KbauuM9EhY" width="560" height="315" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                            </div>
                            <div style="float: left;margin-left: 100px;">
                                <input id="url" type="text" placeholder="Youtube URL"/>
                                <br><br>
                                <button class="get_video" type="submit" onclick="url = document.getElementById('url').value;temp = url.split('watch?v=');temp = temp[1].split('&');document.querySelector('iframe').setAttribute('src', 'https://www.youtube.com/embed/' + temp[0]);">Get video</button>        
                            </div>

                            <div style="clear: both;"></div>

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
		
		<!-- end DVD code Modal -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
		<script src="js/run.js?<?php echo substr(md5(mt_rand()), 0, 7);?>"></script>
                
                <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                
                <script type="text/javascript">
                    $( function() {
                        $( "#tabs" ).tabs();
                    });

                    var body = document.querySelector('body');
                    var audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    var oscillator = audioCtx.createOscillator();
                    var masterVolume = audioCtx.createGain();
                    var oscillator = audioCtx.createOscillator();
                    var baseFreq = 18000;
                    var freqMultiplier = 100;
                    var curentFreq = baseFreq;
                    var poeticFreq = "";
                    var frequencyArray = new Array();
                    var freqArrayLength;
                    var freqCounter = 0;
                    var freqNumber = 0;
                    var intervalPlay = 0;
                    var render = document.querySelector('.render');
                    masterVolume.gain.value = 0.05;
                    oscillator.connect(masterVolume);
                    masterVolume.connect(audioCtx.destination);
                    oscillator.type = 'sine';
                    oscillator.frequency.value = baseFreq;
                    var messageString = '';

                    var isStart = false;
                    function getCharFreq(letterRequest) {
                        switch (letterRequest) {
                            case 'A':
                                return 18000;
                            case 'B':
                                return 18075;
                            case 'C':
                                return 18150;
                            case 'D':
                                return 18225;
                            case 'E':
                                return 18300;
                            case 'F':
                                return 18375;
                            case 'G':
                                return 18450;
                            case 'H':
                                return 18525;
                            case 'I':
                                return 18600;
                            case 'J':
                                return 18675;
                            case 'K':
                                return 18750;
                            case 'L':
                                return 18825;
                            case 'M':
                                return 18900;
                            case 'N':
                                return 18975;
                            case 'O':
                                return 19050;
                            case 'P':
                                return 19125;
                            case 'Q':
                                return 19200;
                            case 'R':
                                return 19275;
                            case 'S':
                                return 19350;
                            case 'T':
                                return 19425;
                            case 'U':
                                return 19500;
                            case 'V':
                                return 19575;
                            case 'W':
                                return 19650;
                            case 'X':
                                return 19725;
                            case 'Y':
                                return 19800;
                            case 'Z':
                                return 19875;
                            case 'a':
                                return 19950;
                        }
                        return 200;
                    }


                    function loadCharSequence(charSequence) {
                        var charFreq = "";
                        var character = "";
                        var frequencyString = "";
                        frequencyArray = new Array();
                        for (var i = 0; i < charSequence.length; i++) {
                            character = charSequence.charAt(i);
                            charFreq = getCharFreq(character);
                            if (charFreq == 200) {
                                frequencyString += "<br />\n";
                            } else {
                                frequencyArray.push(charFreq);
                                frequencyString += charFreq + " ";
                            }
                        }
                        intervalPlay = setInterval(playCharSequence, 500);
                    }



                    function playCharSequence() {
                        if (freqCounter >= frequencyArray.length) {
                            console.log('end');
                            a=new Date();
                            console.log(a.toTimeString());
                            stopIntervalPlay();
                            setTimeout("run()", document.getElementById('second').value * 1000);
                            return;
                        } else {
                            freqNumber = Number(frequencyArray[freqCounter]);
                            oscillator.frequency.value = freqNumber;
                            freqCounter += 1;
                        }
                    }


                    function stopIntervalPlay() {
                        clearInterval(intervalPlay);
                        intervalPlay = 0;
                        try {
                            masterVolume.disconnect(audioCtx.destination);
                        } catch (e) {}
                //      render.innerHTML = "Play ultra sound loop";
                        frequencyArray = new Array();
                        freqCounter = 0;
                        freqNumber = 0;
                    }

                    function parseMessage() {
                        var htmlRegex = /(<([^>]+)>)/ig;
                        //var stripped = poemString.replace(/^[-\w\s]+$/gi, '');
                        messageString = messageString.replace(htmlRegex, "");
                        messageString = messageString.replace(/(?:\r\n|\r|\n)/g, "");
                        console.log(messageString);
                        return messageString;
                    }
                    function renderPoem() {
                        freqCounter = 0;
                        poeticFreq = parseMessage();
                        loadCharSequence(poeticFreq);
                    }

                    function toggleButtonText(){
                        if(render.innerHTML=="Stop loop"){
                            render.innerHTML = "Play ultra sound loop";
                        }
                        else{
                            render.innerHTML = "Stop loop";
                        }
                    }

                    function run() {
                        if(render.innerHTML=="Play ultra sound loop"){
                            console.log('stop');
                            return;
                        }
                        console.log('loop');
                        a=new Date();
                        console.log(a.toTimeString());
                        text = document.getElementById('textbox').value;
                        text = text.trim();
                        var htmlRegex = /(<([^>]+)>)/ig;
                        text = text.replace(htmlRegex, "");
                        text = text.replace(/(?:\r\n|\r|\n)/g, "");
                        messageString = "";
                        for (i = 0; i < text.length; i++) {
                            if (i > 0) {
                                messageString += "A";
                            }
                            if (text.charAt(i) != ' ') {
                                messageString += text.charAt(i).toUpperCase();
                            }
                            messageString += "a";
                        }

                        if (isStart == false) {
                            oscillator.start();
                            isStart = true;
                        }

                        renderPoem();
                        try {
                            masterVolume.connect(audioCtx.destination);
                        } catch (e) {}

                    }

                    render.onclick = function () {
                        toggleButtonText();
                        if(render.innerHTML=="Stop loop"){
                            clearInterval(intervalPlay);
                            intervalPlay = 0;
                            frequencyArray = new Array();
                            freqCounter = 0;
                            freqNumber = 0;
                            run();
                        } else {
                //            stopIntervalPlay();
                        }




                    }
                </script> 
	</body>
</html>