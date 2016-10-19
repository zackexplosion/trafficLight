<!DOCTYPE html >
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>黃金綠燈評估系統</title>
        <link rel="stylesheet" href="assets/css/jbclock.css" type="text/css" media="all" />
        <link rel="stylesheet" href="assets/css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="assets/css/bootstrap.css.map" type="text/css"/>
        <script type="text/javascript" src="assets/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="assets/js/js.cookie-2.1.3.min.js"></script>
        <script type="text/javascript" src="assets/js/trafficlight.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
<!--
            <div id="step0" class="col-md-6">
                <h3 class="text-muted" id="search">來尋找威利</h3>
                <form role="form">
                    <div class="input-group">
                        <span class="input-group-addon">緯度</span>
                        <input type="number" class="form-control" id="latitude" placeholder="緯度">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">經度</span>
                        <input type="number" class="form-control" id="longitude" placeholder="經度">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">準確度(m)</span>
                        <input type="number" class="form-control" id="accuracy" placeholder="誤差">
                    </div>
                </form>
                <hr>
                <div class="btn-group">
                    <a class="btn btn-danger" id="no-position">我不知道這是在哪裡</a>
                    <a class="btn btn-success" id="yes-position">這是我的當前位置</a>
                </div>
            </div>
            <div id="step1" style='display:none;' class="col-md-5">
                <h3 class="text-muted"><label>紅燈時間<label>：<span id="red-measure-second">0</span></h3>
                <h3 class="text-muted"><label>綠燈時間<label>：<span id="green-measure-second">0</span></h3>
                <button id="red-measure-start" class="btn btn-primary btn-block">紅燈計時開始</button>
                <button id="green-measure-start" class="btn btn-primary btn-block" style="display:none">綠燈計時開始</button>
                <button id="measure-stop" class="btn btn-primary btn-block" style="display:none">計時結束</button>
                <button id="skip" class="btn btn-warning btn-block">跳過此步驟直接看 Demo</button>
            </div>
-->
            <div id="step2" style="">
                <div class="clock col-sm-3" id="light1">
                    <canvas id="canvas_seconds" width="188" height="188"></canvas>
                    <div class="text">
                        <p class="val" id="light1val">0</p>
                        <p class="type_seconds">剩餘秒數</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h3 class="text-muted">實地測試<a class="btn btn-primary" id="test">執行</a></h3>
                    <form class="form-horizontal" id="add-form">
                      <div class="form-group">
                        <label class="col-sm-2" for="timer1">路口</label>
                        <div class="col-sm-4">
                        <input type="text" class="form-control" name="location" id="location" placeholder="交叉路口">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2" for="timer1">經度</label>
                        <div class="col-sm-4">
                        <input type="text" class="form-control" id="location-longitude" placeholder="沒有資料">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2" for="timer1">緯度</label>
                        <div class="col-sm-4">
                        <input type="text" class="form-control" id="location-latitude" placeholder="沒有資料">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2" for="timer1">準確度</label>
                        <div class="col-sm-4">
                        <input type="text" class="form-control" id="location-accuracy" placeholder="沒有資料">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2" for="timer1">綠燈加黃燈長度</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="green_phase" id="green_phase" placeholder="綠燈時間">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2" for="timer2">紅燈長度</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="red_phase" id="red_phase" placeholder="紅燈時間">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2" for="delay">延遲</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="offset" id="offset" placeholder="延遲時間">
                        </div>
                      </div>
                      <div class="btn-group col-md-offset-1">
                          <a class="btn btn-danger" id="back">回計時頁面</a>
                          <a class="btn btn-success disabled" id="confirm">送出資料</a>
                      </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
        <script type="text/javascript">
            var lightInterval = null;
            var redMeasure = null;
            var greenMeasure = null;
            var period_start = null;
            $('#test').click(function(){
                if (null !== lightInterval) {
                    clearInterval(lightInterval);
                    lightInterval = null;
                }
                var red_phase = parseInt($('#red_phase').val());
                var green_phase = parseInt($('#green_phase').val());
                var offset = parseInt($('#offset').val());
                var trafficLight = new Object();
                trafficLight.timer = [red_phase, green_phase];
                trafficLight.offset = offset;
                trafficLight.period = red_phase + green_phase;
                today = new Date();
                trafficLight.tl_start = Math.round((new Date(today.getFullYear(),today.getMonth(),today.getDate() - 1,7)).getTime()/1000);
                console.log(trafficLight);
                var display = $('#canvas_seconds').get(0);
                lightInterval = setInterval(function(){countDown(trafficLight, display)}, 1000);
            });
            $('#stop1').click(function(){
                clearInterval(lightInterval);
                lightInterval = null;
            });
            $('#red-measure-start').click(function(){
                $('#red-measure-start').hide();
                $('#green-measure-start').show();
                var now = new Date();
                period_start = Math.round(now.getTime() / 1000);
                var begin = now.getTime()/1000;
                redMeasure = setInterval(function(){$('#red-measure-second').text(Math.round((new Date()).getTime()/1000 - begin))},1000);
            });
            $('#green-measure-start').click(function(){
                clearInterval(redMeasure);
                redMeasure = null;
                $('#green-measure-start').hide();
                $('#measure-stop').show();
                var now = new Date();
                var begin = now.getTime()/1000;
                greenMeasure = setInterval(function(){$('#green-measure-second').text(Math.round((new Date()).getTime()/1000 - begin))},1000);
            });
            $('#measure-stop').click(function(){
                clearInterval(greenMeasure);
                greenMeasure = null;
                var red_time = parseInt($('#red-measure-second').text());
                var green_time = parseInt($('#green-measure-second').text());
                today = new Date();
                var tl_start = Math.round((new Date(today.getFullYear(),today.getMonth(),today.getDate() - 1,0)).getTime()/1000);
                var period = red_time + green_time;
                var offset = (period_start - tl_start) % period;
                $('#red_phase').val(red_time);
                $('#green_phase').val(green_time);
                $('#offset').val(offset);
                $('#step1').fadeOut(200);
                $('#step2').fadeIn(200);
            });
            $('#skip').click(function(){
                $('#step1').fadeOut(200);
                $('#step2').fadeIn(200);
            });
            $('#back').click(function(){
                if (null !== lightInterval) {
                    clearInterval(lightInterval);
                    lightInterval = null;
                }
                $('#step2').fadeOut(200);
                $('#step1').fadeIn(200);
            });
            if (navigator.geolocation) {
                var welly = setInterval(function(){
                    var text = $('#search').text();
                    $('#search').text(text + '.');
                }, 1000);
                navigator.geolocation.getCurrentPosition(function(position){
                    clearInterval(welly);
                    welly = null;
                    $('#latitude').val(position.coords.latitude);
                    $('#longitude').val(position.coords.longitude);
                    $('#accuracy').val(position.coords.accuracy);
                    success(position);
                }, error);
            } else {
                clearInterval(welly);
                welly = null;
                $('#latitude').val(0);
                $('#longitude').val(0);
                $('#accuracy').val(0);
                error('not supported');
            }
            $('#yes-position').click(function(){
                $('#location-latitude').val($('#latitude').val());
                $('#location-longitude').val($('#longitude').val());
                $('#location-accuracy').val($('#accuracy').val());
                $('#step0').fadeOut(200);
                $('#step1').fadeIn(200);
            });
            $('#no-position').click(function(){
                $('#step0').fadeOut(200);
                $('#step1').fadeIn(200);
            });
        </script>
    </body>
</html>
