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
var redMeasure = null;
var greenMeasure = null;
var period_start = null;
var setTrafficLight = function () {
    var red_phase = parseInt($('#red_phase').val()) || parseInt(getParameterByName('red'));
    var green_phase = parseInt($('#green_phase').val()) || parseInt(getParameterByName('green'));
    var offset = parseInt($('#offset').val()) || parseInt(getParameterByName('offset'));
    var _location = $('#location').val() || getParameterByName('location');
    var trafficLight = new Object();
    $('#red_phase').val(red_phase);
    $('#green_phase').val(green_phase);
    $('#offset').val(offset);
    $('#location').val(_location);
    trafficLight.timer = [red_phase, green_phase];
    trafficLight.offset = offset;
    trafficLight.period = red_phase + green_phase;
    today = new Date();
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
$('#test').click(fire);
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
