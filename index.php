<!DOCTYPE html >
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>黃金綠燈評估系統</title>
        <link rel="stylesheet" href="assets/css/jbclock.css?v=<?= time()?>" type="text/css" media="all" />
        <link rel="stylesheet" href="assets/css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="assets/css/bootstrap.css.map" type="text/css"/>
        <script type="text/javascript" src="assets/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="assets/js/trafficlight.js?v=<?= time()?>"></script>
        <script type="text/javascript" src="assets/js/bootstrap.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="clock" id="light1">
                        <canvas id="canvas_seconds" width="188" height="188"></canvas>
                        <div class="text">
                            <p class="val" id="light1val">0</p>
                            <p class="type_seconds">Seconds</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <form>
                      <div class="form-group">
                        <label for="timer1">路口</label>
                        <input type="text" class="form-control" name="location" id="location" placeholder="交叉路口" value="興仁路二段與榮民路路口">
                      </div>
                      <div class="form-group">
                        <label for="timer1">第一時相</label>
                        <input type="number" class="form-control" name="green_phase" id="green_phase" placeholder="綠燈時間" value="24">
                      </div>
                      <div class="form-group">
                        <label for="timer2">第二時相</label>
                        <input type="number" class="form-control" name="red_phase" id="red_phase" placeholder="紅燈時間" value="76">
                      </div>
                      <div class="form-group">
                        <label for="delay">延遲</label>
                        <input type="number" class="form-control" name="offset" id="offset" placeholder="延遲時間" value="0">
                      </div>
                      <div class="btn-group">
                          <a class="btn btn-primary" id="start1">開始倒數</a>
                          <a class="btn btn-warning" id="stop1">停止倒數</a>
                      </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var light = null;
            $('#start1').click(function(){
                if (null !== light) {
                    clearInterval(light);
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
                light = setInterval(function(){countDown(trafficLight, display)}, 1000);
            });
            $('#stop1').click(function(){
                clearInterval(light);
            });
        </script>
    </body>
</html>
