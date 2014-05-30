<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>JB Clock</title>
        <link rel="stylesheet" href="jbclock.css" type="text/css" media="all" />
        <script type="text/javascript" src="jquery-1.8.0.min.js"></script>
        <script type="text/javascript">
            function deg(deg){
                return (Math.PI/180)*deg - (Math.PI/180)*90;
            }

            /*
            * trafficLight = {
            *                   timer: [24, 76],
            *                   period: int,
            *                   offset: int,
            *                   tl_start: int}
             */
            var countDown = function (trafficLight, display) {
                var phase = (Math.round((new Date()).getTime() / 1000 ) - trafficLight.tl_start + trafficLight.offset) % trafficLight.period;
                var flag = null;
                var counter = null;
                if(phase > trafficLight.timer[0]){
                    counter = trafficLight.period - phase;
                    flag = 1;
                }
                else{
                    flag = 0;
                    counter = trafficLight.timer[0] - phase;
                }
                $(".clock_seconds .val").text(Math.floor(counter));
                var ctx = display.getContext('2d');
                ctx.clearRect(0, 0, display.width, display.height);
                ctx.beginPath();
                if(flag == 0)
                    ctx.strokeStyle = "#ff6565";
                else if(flag == 1)
                    ctx.strokeStyle = "#9cdb7d";
                ctx.shadowBlur = 10;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 0;
                ctx.shadowColor = "none";

                ctx.arc(94,94,85, deg(0), deg(Math.floor(360 - counter * 360 / trafficLight.timer[flag])));
                ctx.lineWidth = 17;
                ctx.stroke();
            }
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

        <div class="wrapper">
        <div class="clock">
            <div class="clock_seconds">
                <div class="bgLayer">
                    <div class="topLayer"></div>
                    <canvas id="canvas_seconds" width="188" height="188">
                    </canvas>
                    <div class="text">
                        <p class="val">0</p>
                        <p class="type_seconds">Seconds</p>
                    </div>
                </div>
            </div>
            <!-- Seconds -->
        </div>
<!--        <div class="divider">
            <label>紅燈秒數：</label>
            <input id='red' type='number'>
            <label>綠燈秒數：</label>
            <input id='green' type='number' onChange='CountDown()'>
        </div> -->
        </div><!--/wrapper-->
    </body>
</html>
