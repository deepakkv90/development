// Title: Tigra Color Picker
// URL: http://www.softcomplex.com/products/tigra_color_picker/
// Version: 1.1
// Date: 06/26/2003 (mm/dd/yyyy)
// Note: Permission given to use this script in ANY kind of applications if
//    header lines are left unchanged.
// Note: Script consists of two files: picker.js and picker.html

var TCP_DEC = new TColorPicker();

function TCPpopup(field, palette) {
  this.field = field;
  this.initPalette = !palette || palette > 3 ? 0 : palette;
  var w = 360, h = 320,
  move = screen ? 
    ',left=' + ((screen.width - w) >> 1) + ',top=' + ((screen.height - h) >> 1) : '', 
  o_colWindow = window.open('includes/javascript/color_picker/picker-dec.html', null, "help=no,status=no,scrollbars=no,resizable=no" + move + ",width=" + w + ",height=" + h + ",dependent=yes", true);
  o_colWindow.opener = window;
  o_colWindow.focus();
}

function TCBuildCell (R, G, B, w, h) {
  return '<td width="' + w + '" height="' + h + '" border="0" bgcolor="#' + this.dec2hexhtml((R << 16) + (G << 8) + B) + '"><a href="javascript:P.S(\'' + this.dec2hex((R) + ',' + (G) + ',' + B) + '\')" onmouseover="P.P(\'' + this.dec2hex((R) + ',' + (G) + ',' + B) + '\')"><img src="img/pixel.gif" width="' + w + '" height="' + h + '" border="0"></a></td>';
}

function TCSelect(c) {
  this.field.value = c;
  this.win.close();
}

function TCPpaint(c, b_noPref) {
  c1 = 'rgb(' + c + ')';
  if (this.o_samp) 
    this.o_samp.innerHTML = '<font face=Tahoma size=2>' + c +' <font color=white>' + c + '</font></font>'
  if(this.doc.layers)
    this.sample.bgcolor = c1;
  else { 
    if (this.sample.backgroundColor != null) this.sample.backgroundColor = c1;
    else if (this.sample.background != null) this.sample.background = c1;
  }
}

function TCGenerateSafe() {
  var s = '';
  for (j = 0; j < 12; j ++) {
    s += "<tr>";
    for (k = 0; k < 3; k ++)
      for (i = 0; i <= 5; i ++)
        s += this.bldCell(k * 51 + (j % 2) * 51 * 3, Math.floor(j / 2) * 51, i * 51, 15, 20);
    s += "</tr>";
  }
  return s;
}

function TCGenerateWind() {
  var s = '';
  for (j = 0; j < 12; j ++) {
    s += "<tr>";
    for (k = 0; k < 3; k ++)
      for (i = 0; i <= 5; i++)
        s += this.bldCell(i * 51, k * 51 + (j % 2) * 51 * 3, Math.floor(j / 2) * 51, 15, 20);
    s += "</tr>";
  }
  return s  
}
function TCGenerateMac() {
  var s = '';
  var c = 0,n = 1;
  var r,g,b;
  for (j = 0; j < 15; j ++) {
    s += "<tr>";
    for (k = 0; k < 3; k ++)
      for (i = 0; i <= 5; i++){
        if(j<12){
        s += this.bldCell( 255-(Math.floor(j / 2) * 51), 255-(k * 51 + (j % 2) * 51 * 3),255-(i * 51), 15, 15);
        }else{
          if(n<=14){
            r = 255-(n * 17);
            g=b=0;
          }else if(n>14 && n<=28){
            g = 255-((n-14) * 17);
            r=b=0;
          }else if(n>28 && n<=42){
            b = 255-((n-28) * 17);
            r=g=0;
          }else{
            r=g=b=255-((n-42) * 17);
          }
          s += this.bldCell( r, g,b, 15, 15);
          n++;
        }
      }
    s += "</tr>";
  }
  return s;
}

function TCGenerateGray() {
  var s = '';
  for (j = 0; j <= 15; j ++) {
    s += "<tr>";
    for (k = 0; k <= 15; k ++) {
      g = Math.floor((k + j * 16) % 256);
      s += this.bldCell(g, g, g, 15, 15);
    }
    s += '</tr>';
  }
  return s
}

function TCDec2Hex(v) {
// convert color to decimal
  v = v.toString(10);
  for(; v.length < 6; v = '0' + v);
  return v;
}
function TCDec2HexHtml(v) {
  v = v.toString(16);
  for(; v.length < 6; v = '0' + v);
  return v;
}
function TCChgMode(v) {
  for (var k in this.divs) this.hide(k);
  this.show(v);
}

function TColorPicker(field) {
  this.build0 = TCGenerateSafe;
  this.build1 = TCGenerateWind;
  this.build2 = TCGenerateGray;
  this.build3 = TCGenerateMac;
  this.show = document.layers ? 
    function (div) { this.divs[div].visibility = 'show' } :
    function (div) { this.divs[div].visibility = 'visible' };
  this.hide = document.layers ? 
    function (div) { this.divs[div].visibility = 'hide' } :
    function (div) { this.divs[div].visibility = 'hidden' };
  // event handlers
  this.C       = TCChgMode;
  this.S       = TCSelect;
  this.P       = TCPpaint;
  this.popup   = TCPpopup;
  this.draw    = TCDraw;
  this.dec2hex = TCDec2Hex;
  this.bldCell = TCBuildCell;
  this.divs = [];
  this.dec2hexhtml = TCDec2HexHtml;
}

function TCDraw(o_win, o_doc) {
  this.win = o_win;
  this.doc = o_doc;
  var 
  s_tag_openT  = o_doc.layers ? 
    'layer visibility=show top=74 left=5 width=182' : 
    'div style=position:absolute;left:6px;top:74px;width:182px;height:0',
  s_tag_openS  = o_doc.layers ? 'layer top=52 left=6' : 'div',
  s_tag_close  = o_doc.layers ? 'layer' : 'div'
    
  this.doc.write('<' + s_tag_openS + ' id=sam name=sam><table cellpadding=0 cellspacing=0 border=1 width=250 align=center class=bd><tr><td align=center height=18><div id="samp"><font face=Tahoma size=2>sample <font color=white>sample</font></font></div></td></tr></table></' + s_tag_close + '>');
  this.sample = o_doc.layers ? o_doc.layers['sam'] : 
    o_doc.getElementById ? o_doc.getElementById('sam').style : o_doc.all['sam'].style

  for (var k = 0; k < 4; k ++) {
    this.doc.write('<' + s_tag_openT + ' id="p' + k + '" name="p' + k + '"><table cellpadding=0 cellspacing=0 border=0 align=center>' + this['build' + k]() + '</table></' + s_tag_close + '>');
    this.divs[k] = o_doc.layers 
      ? o_doc.layers['p' + k] : o_doc.all 
        ? o_doc.all['p' + k].style : o_doc.getElementById('p' + k).style
  }
  if (!o_doc.layers && o_doc.body.innerHTML) 
    this.o_samp = o_doc.all 
      ? o_doc.all.samp : o_doc.getElementById('samp');
  this.C(this.initPalette);
  if (this.field.value) this.P(this.field.value, true)
}