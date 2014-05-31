<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>黃金綠燈評估系統</title>
        <link rel="stylesheet" href="assets/css/jbclock.css" type="text/css" media="all" />
        <link rel="stylesheet" href="assets/css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="assets/css/bootstrap.css.map" type="text/css"/>
        <script type="text/javascript" src="assets/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="assets/js/trafficlight.js?v=<?= time()?>"></script>
        <script type="text/javascript" src="assets/js/bootstrap.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                var today = new Date();
                var tl_start = Math.round((new Date(today.getFullYear(),today.getMonth(),today.getDate() - 1,7)).getTime()/1000);
                var trafficLight = {
                    timer: [76, 24],
                    period: 100,
                    offset: 0,
                    tl_start: tl_start
                };
                var second = $('#canvas_seconds').get(0);
                var startCount = setInterval(function(){countDown(trafficLight, second)}, 1000);
            });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="clock" id="light1">
                <canvas id="canvas_seconds" width="188" height="188">
                </canvas>
                <div class="text">
                    <p class="val" id="light1val">0</p>
                    <p class="type_seconds">Seconds</p>
                </div>
            </div>
        </div><!--/wrapper-->
    </body>
</html>
