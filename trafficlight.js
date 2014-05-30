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
