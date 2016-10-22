function deg(deg){
  return (Math.PI/180)*deg - (Math.PI/180)*90;
}

/*
 * trafficLight = {
 *                   timer: [24, 76],
 *                   period: int,
 *                   offset: int,
 *                   tl_start: int
 *                }
 */
var countDown = function (trafficLight, display) {
  var phase = (Math.round((new Date()).getTime() / 1000 ) - trafficLight.tl_start + trafficLight.offset) % trafficLight.period;
  var flag = null;
  var counter = null;
  if(phase > trafficLight.timer[0]){
    flag = 1;
    counter = trafficLight.period - phase;
  } else{
    flag = 0;
    counter = trafficLight.timer[0] - phase;
  }
  $("#light1val").text(Math.floor(counter));
  var ctx = display.getContext('2d');

  ctx.clearRect(0, 0, display.width, display.height);
  ctx.beginPath();

  if (flag == 0) {
    ctx.strokeStyle = "#ff6565";
  } else {
    ctx.strokeStyle = "#9cdb7d";
  }
  ctx.shadowBlur = 10;
  ctx.shadowOffsetX = 0;
  ctx.shadowOffsetY = 0;
  ctx.shadowColor = "none";

  ctx.arc(94,94,85, deg(0), deg(Math.floor(360 - counter * 360 / trafficLight.timer[flag])));
  ctx.lineWidth = 17;
  ctx.stroke();
}
var getParameterByName = function (name) {
    var url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"), results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return (decodeURIComponent(results[2].replace(/\+/g, " ")));
}
var lightInterval = null;
var period_start = null;
var setTrafficLight = function () {
    var red_phase = parseInt($('#red_phase').val()) || parseInt(getParameterByName('red')) || null;
    var green_phase = parseInt($('#green_phase').val()) || parseInt(getParameterByName('green')) || null;
    var offset = parseInt($('#offset').val()) || parseInt(getParameterByName('offset')) || null;
    var trafficLight = new Object();
    trafficLight.ready = false;
    $('#red_phase').val(red_phase);
    $('#green_phase').val(green_phase);
    $('#offset').val(offset);
    trafficLight.timer = [red_phase, green_phase];
    trafficLight.offset = offset;
    trafficLight.period = red_phase + green_phase;
    today = new Date();
    if (red_phase && green_phase && offset) {
        trafficLight.ready = true;
    }
    trafficLight.tl_start = Math.round((new Date(today.getFullYear(),today.getMonth(),today.getDate() - 1,7)).getTime()/1000);
    return trafficLight;
}
var fire = function () {
    if (null !== lightInterval) {
        clearInterval(lightInterval);
        lightInterval = null;
    }
    var trafficLight = setTrafficLight();
    today = new Date();
    var display = $('#canvas_seconds').get(0);
    lightInterval = setInterval(function(){countDown(trafficLight, display)}, 1000);
}
$(document).on("ready", function () {
    var trafficLight = setTrafficLight();
    if (trafficLight.ready) {
        fire();
    }
    $('#test').click(fire);
});
