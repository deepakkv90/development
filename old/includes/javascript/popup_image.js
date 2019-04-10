<script type="text/javascript"><!--
var i=0;
var s=0;
function resize() {
  if (navigator.appName == 'Netscape') i=40;
   if (window.navigator.userAgent.indexOf("SV1") != -1) s=20; //This browser is Internet Explorer in SP2.
    if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+60-i+s);
    self.focus();
  if (document.images[0]) {
  imgHeight = document.images[0].height+65-i;
  imgWidth = document.images[0].width+30;
  var height = screen.height;
  var width = screen.width;
  var leftpos = width / 2 - imgWidth / 2;
  var toppos = height / 2 - imgHeight / 2; 
  window.moveTo(leftpos, toppos);  
  window.resizeTo(imgWidth, imgHeight);
  }
  self.focus();
}
//--></script>
