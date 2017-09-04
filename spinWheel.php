<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <link href="https://fonts.googleapis.com/css?family=Mukta+Mahee" rel="stylesheet">
  <title>SPIN WHEEL</title>
  <style>
#wrapper {
  height: 500px;
  width: 500px;
  position: fixed;
  top: 50%;
  left: 50%;
  margin-left: -250px;
  margin-top: -250px;
}

.button {
  position: fixed;
  top: 50%;
  left: 50%;
  z-index: 1000;

  width: 150px;
  height:40px;
  background-color:red;
  color:black;
  font-family: Avenir;
  font-size: 30px;

  margin-left: -75px;
  margin-top: -20px;

  border-radius: 10%;
}
  </style>
</head>

<body>
<div id="wrapper">
  <input class="button" type="button" value="spin" id='spin' />
  <canvas id="canvas" width="500" height="500"></canvas>
</div>


  <script>
    var options = ["ABS", "ARMS", "LEGS", "CHEST", "SHOULDERS", "UPPERBODY", "LOWERBODY", "FULLBODY"];

    var doSomething = ["20 SITUPS", "10 PUSHUPS", "20 SQUATS", "10 BENCHPRESS", "10 SHOULDERPRESS", "20 DIPS", "10 SOMETHING", "20 SOMETHING"]

    var startAngle = 0;
    var arc = Math.PI / (options.length / 2);
    var spinTimeout = null;

    var spinArcStart = 10;
    var spinTime = 0;
    var spinTimeTotal = 0;

    var ctx;

    document.getElementById("spin").addEventListener("click", spin);

    function byte2Hex(n) {
      var nybHexString = "0123456789ABCDEF";
      return String(nybHexString.substr((n >> 4) & 0x0F, 1)) + nybHexString.substr(n & 0x0F, 1);
    }

    function RGB2Color(r, g, b) {
      return '#' + byte2Hex(r) + byte2Hex(g) + byte2Hex(b);
    }

    function getColor(item, maxitem) {
      var phase = 0;
      var center = 128;
      var width = 127;
      var frequency = Math.PI * 2 / maxitem;

      red = Math.sin(frequency * item + 2 + phase) * width + center;
      green = Math.sin(frequency * item + 0 + phase) * width + center;
      blue = Math.sin(frequency * item + 4 + phase) * width + center;

      return RGB2Color(red, green, blue);
    }

    function drawRouletteWheel() {
      var canvas = document.getElementById("canvas");
      if (canvas.getContext) {
        var outsideRadius = 200;
        var textRadius = 160;
        var insideRadius = 0;

        ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, 500, 500);


        ctx.font = '20px Avenir';

        for (var i = 0; i < options.length; i++) {
          var angle = startAngle + i * arc;
          //ctx.fillStyle = colors[i];
          ctx.fillStyle = getColor(i, options.length);

          ctx.beginPath();
          ctx.arc(250, 250, outsideRadius, angle, angle + arc, false);
          ctx.arc(250, 250, insideRadius, angle + arc, angle, true);
          ctx.stroke();
          ctx.fill();

          ctx.save();
          ctx.shadowOffsetX = -1;
          ctx.shadowOffsetY = -1;
          ctx.shadowBlur = 0;
          ctx.shadowColor = "rgb(220,220,220)";
          ctx.fillStyle = "black";
          ctx.fontFamily = "Avenir";
          ctx.translate(250 + Math.cos(angle + arc / 2) * textRadius,
            250 + Math.sin(angle + arc / 2) * textRadius);
          ctx.rotate(angle + arc / 2 + Math.PI / 2);
          var text = options[i];
          ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
          ctx.restore();
        }

        //Arrow
        ctx.fillStyle = "black";
        ctx.beginPath();
        ctx.moveTo(250 - 4, 250 - (outsideRadius + 5));
        ctx.lineTo(250 + 4, 250 - (outsideRadius + 5));
        ctx.lineTo(250 + 4, 250 - (outsideRadius - 5));
        ctx.lineTo(250 + 9, 250 - (outsideRadius - 5));
        ctx.lineTo(250 + 0, 250 - (outsideRadius - 13));
        ctx.lineTo(250 - 9, 250 - (outsideRadius - 5));
        ctx.lineTo(250 - 4, 250 - (outsideRadius - 5));
        ctx.lineTo(250 - 4, 250 - (outsideRadius + 5));
        ctx.fill();
      }
    }

    function spin() {
      spinAngleStart = Math.random() * 10 + 10;
      spinTime = 0;
      spinTimeTotal = Math.random() * 3 + 4 * 1000;
      rotateWheel();
    }

    function rotateWheel() {
      spinTime += 30;
      if (spinTime >= spinTimeTotal) {
        stopRotateWheel();
        return;
      }
      var spinAngle = spinAngleStart - easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
      startAngle += (spinAngle * Math.PI / 180);
      drawRouletteWheel();
      spinTimeout = setTimeout('rotateWheel()', 30);
    }

    function stopRotateWheel() {
      clearTimeout(spinTimeout);
      var degrees = startAngle * 180 / Math.PI + 90;
      var arcd = arc * 180 / Math.PI;
      var index = Math.floor((360 - degrees % 360) / arcd);
      ctx.save();
      ctx.font = '20px Avenir';
      var text = doSomething[index]
      ctx.fillText(text, 250 - ctx.measureText(text).width / 2, 500 - 20);
      ctx.restore();
    }

    function easeOut(t, b, c, d) {
      var ts = (t /= d) * t;
      var tc = ts * t;
      return b + c * (tc + -3 * ts + 3 * t);
    }

    drawRouletteWheel();
  </script>
</body>

</html>