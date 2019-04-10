var inheriting={};var AmCharts={};
AmCharts.Class=function(c){var b=function(){if(arguments[0]===inheriting){return
}this.events={};this.construct.apply(this,arguments)
};if(c.inherits){b.prototype=new c.inherits(inheriting);
b.base=c.inherits.prototype;
delete c.inherits}else{b.prototype.createEvents=function(){for(var e=0,d=arguments.length;
e<d;e++){this.events[arguments[e]]=[]
}};b.prototype.listenTo=function(f,e,d){f.events[e].push({handler:d,scope:this})
};b.prototype.addListener=function(e,d,f){this.events[e].push({handler:d,scope:f})
};b.prototype.removeListener=function(h,g,e){var f=h.events[g];
for(var d=f.length-1;
d>=0;d--){if(f[d].handler===e){f.splice(d,1)
}}};b.prototype.fire=function(j,k){var e=this.events[j];
for(var f=0,d=e.length;
f<d;f++){var g=e[f];g.handler.call(g.scope,k)
}}}for(var a in c){b.prototype[a]=c[a]
}return b};AmCharts.charts=[];
AmCharts.addChart=function(a){AmCharts.charts.push(a)
};AmCharts.removeChart=function(b){var c=AmCharts.charts;
for(var a=c.length-1;
a>=0;a--){if(c[a]==b){c.splice(a,1)
}}};if(document.addEventListener){AmCharts.isNN=true;
AmCharts.isIE=false;AmCharts.ddd=0.5
}if(document.attachEvent){AmCharts.isNN=false;
AmCharts.isIE=true;AmCharts.ddd=0
}AmCharts.IEversion=0;
if(navigator.appVersion.indexOf("MSIE")!=-1){if(document.documentMode){AmCharts.IEversion=document.documentMode
}}if(AmCharts.IEversion>=9){AmCharts.ddd=0.5
}AmCharts.handleResize=function(){var c=AmCharts.charts;
for(var a=0;a<c.length;
a++){var b=c[a];if(b){b.handleResize()
}}};AmCharts.handleMouseUp=function(d){var c=AmCharts.charts;
for(var a=0;a<c.length;
a++){var b=c[a];if(b){b.handleReleaseOutside(d)
}}};AmCharts.handleMouseMove=function(d){var c=AmCharts.charts;
for(var a=0;a<c.length;
a++){var b=c[a];if(b){b.handleMouseMove(d)
}}};AmCharts.resetMouseOver=function(){var c=AmCharts.charts;
for(var a=0;a<c.length;
a++){var b=c[a];if(b){b.mouseIsOver=false
}}};if(AmCharts.isNN){document.addEventListener("mousemove",AmCharts.handleMouseMove,true);
window.addEventListener("resize",AmCharts.handleResize,true);
document.addEventListener("mouseup",AmCharts.handleMouseUp,true)
}if(AmCharts.isIE){document.attachEvent("onmousemove",AmCharts.handleMouseMove);
window.attachEvent("onresize",AmCharts.handleResize);
document.attachEvent("onmouseup",AmCharts.handleMouseUp)
}AmCharts.AmChart=AmCharts.Class({construct:function(){var b=this;
AmCharts.addChart(b);
b.createEvents("dataUpdated");
b.width="100%";b.height="100%";
b.dataChanged=true;b.chartCreated=false;
b.previousHeight=0;b.previousWidth=0;
b.backgroundColor="#FFFFFF";
b.backgroundAlpha=0;b.borderAlpha=0;
b.borderColor="#000000";
b.color="#000000";b.fontFamily="Verdana";
b.fontSize=11;b.numberFormatter={precision:-1,decimalSeparator:".",thousandsSeparator:","};
b.percentFormatter={precision:2,decimalSeparator:".",thousandsSeparator:","};
b.labels=[];b.allLabels=[];
b.chartDiv=document.createElement("div");
b.chartDiv.style.overflow="hidden";
b.legendDiv=document.createElement("div");
b.legendDiv.style.overflow="hidden";
b.balloon=new AmCharts.AmBalloon();
b.balloon.chart=this;
b.prefixesOfBigNumbers=[{number:1000,prefix:"k"},{number:1000000,prefix:"M"},{number:1000000000,prefix:"G"},{number:1000000000000,prefix:"T"},{number:1000000000000000,prefix:"P"},{number:1000000000000000000,prefix:"E"},{number:1e+21,prefix:"Z"},{number:1e+24,prefix:"Y"}];
b.prefixesOfSmallNumbers=[{number:1e-24,prefix:"y"},{number:1e-21,prefix:"z"},{number:1e-18,prefix:"a"},{number:1e-15,prefix:"f"},{number:1e-12,prefix:"p"},{number:1e-9,prefix:"n"},{number:0.000001,prefix:"Î¼"},{number:0.001,prefix:"m"}];
try{document.createEvent("TouchEvent");
b.touchEventsEnabled=true
}catch(a){b.touchEventsEnabled=false
}b.panEventsEnabled=false
},drawChart:function(){var d=this;
d.destroy();var g=d.container.set();
d.set=g;var c=d.container;
var j=d.backgroundColor;
var h=d.backgroundAlpha;
var i=d.realWidth;var e=d.realHeight;
if(j!=undefined&&h>0){var b=AmCharts.rect(c,i-1,e,j,h,1,d.borderColor,d.borderAlpha);
d.background=b;g.push(b)
}var f=d.backgroundImage;
if(f){if(d.path){f=d.path+f
}var a=c.image(f,0,0,i,e);
d.bgImg=a;d.set.push(a)
}},write:function(a){var f=this;
if(!f.listenersAdded){f.addListeners();
f.listenersAdded=true
}var e=document.getElementById(a);
f.div=e;e.style.overflow="hidden";
var d=f.chartDiv;var c=f.legendDiv;
var b=f.legend;f.measure();
if(b){switch(b.position){case"bottom":e.appendChild(d);
e.appendChild(c);break;
case"top":e.appendChild(c);
e.appendChild(d);break;
case"absolute":c.style.position="absolute";
d.style.position="absolute";
if(b.left!=undefined){c.style.left=b.left
}if(b.right!=undefined){c.style.right=b.right
}if(f.legend.top!=undefined){c.style.top=b.top
}if(f.legend.bottom!=undefined){c.style.bottom=b.bottom
}e.appendChild(d);e.appendChild(c);
break;case"right":c.style.position="relative";
d.style.position="absolute";
e.appendChild(d);e.appendChild(c);
break;case"left":c.style.position="relative";
d.style.position="absolute";
e.appendChild(d);e.appendChild(c);
break}}else{e.appendChild(d)
}f.divIsFixed=AmCharts.findIfFixed(d);
f.container=Raphael(f.chartDiv,f.realWidth,f.realHeight);
f.initChart()},initChart:function(){var b=this;
b.previousHeight=b.realHeight;
b.previousWidth=b.realWidth;
var a=b.container;if(a){b.destroySets();
a.clear()}b.redrawLabels()
},measure:function(){var h=this;
var g=h.div;var f=h.chartDiv;
var d=g.offsetWidth;var b=g.offsetHeight;
var a=h.container;if(g.clientHeight){d=g.clientWidth;
b=g.clientHeight}var e=AmCharts.toCoordinate(h.width,d);
var c=AmCharts.toCoordinate(h.height,b);
if(e!=h.previousWidth||c!=h.previousHeight){f.style.width=e+"px";
f.style.height=c+"px";
if(a){a.setSize(e,c)}h.balloon.setBounds(2,2,e-2,c)
}h.realWidth=e;h.realHeight=c;
h.divRealWidth=d;h.divRealHeight=b
},destroy:function(){var a=this;
AmCharts.removeSet(a.set);
a.clearTimeOuts()},clearTimeOuts:function(){var c=this;
var b=c.timeOuts;if(b){for(var a=0;
a<b.length;a++){clearTimeout(b[a])
}}c.timeOuts=[]},destroySets:function(){var a=this;
a.set=null;if(a.balloon){a.balloon.set=null
}},clear:function(){var a=this;
AmCharts.callMethod("clear",[a.chartScrollbar,a.scrollbarVertical,a.scrollbarHorizontal,a.chartCursor]);
a.chartScrollbar=null;
a.scrollbarVertical=null;
a.scrollbarHorizontal=null;
a.chartCursor=null;a.clearTimeOuts();
a.container.clear();AmCharts.removeChart(this)
},setMouseCursor:function(a){document.body.style.cursor=a
},bringLabelsToFront:function(){var c=this;
var b=c.labels;for(var a=b.length-1;
a>=0;a--){b[a].toFront()
}},redrawLabels:function(){var c=this;
c.labels=[];var b=c.allLabels;
for(var a=0;a<b.length;
a++){c.drawLabel(b[a])
}},drawLabel:function(i){var e=this;
var j=i.x;var h=i.y;var k=i.text;
var f=i.align;var m=i.size;
var b=i.color;var l=i.rotation;
var a=i.alpha;var g=i.bold;
if(e.container){var d=AmCharts.toCoordinate(j,e.realWidth);
var c=AmCharts.toCoordinate(h,e.realHeight);
if(!d){d=0}if(!c){c=0
}if(b==undefined){b=e.color
}if(isNaN(m)){m=e.fontSize
}if(!f){f="start"}if(f=="left"){f="start"
}if(f=="right"){f="end"
}if(f=="center"){f="middle";
if(!l){d=e.realWidth/2-d
}else{c=e.realHeight-c+c/2
}}if(a==undefined){a=1
}if(l==undefined){l=0
}c+=m/2;var i=AmCharts.text(e.container,d,c,k,{fill:b,"fill-opacity":a,"text-anchor":f,"font-family":e.fontFamily,"font-size":m,rotation:l});
if(g){i.attr({"font-weight":"bold"})
}i.toFront();e.labels.push(i)
}},addLabel:function(h,f,i,d,k,b,j,a,e){var c=this;
var g={x:h,y:f,text:i,align:d,size:k,color:b,alpha:a,rotation:j,bold:e};
if(c.container){c.drawLabel(g)
}c.allLabels.push(g)},clearLabels:function(){var c=this;
var b=c.labels;for(var a=b.length-1;
a>=0;a--){b[a].remove()
}c.labels=[]},updateHeight:function(){var e=this;
var a=e.divRealHeight;
var d=e.legend;if(d){var b=Number(e.legendDiv.style.height.replace("px",""));
var c=d.position;if(c=="top"||c=="bottom"){a-=b;
if(a<0){a=0}e.chartDiv.style.height=a+"px"
}}return a},updateWidth:function(){var g=this;
var e=g.divRealWidth;
var a=g.divRealHeight;
var d=g.legend;if(d){var f=Number(g.legendDiv.style.width.replace("px",""));
var b=Number(g.legendDiv.style.height.replace("px",""));
var c=d.position;if(c=="right"||c=="left"){e-=f;
if(e<0){e=0}g.chartDiv.style.width=e+"px";
if(c=="left"){g.chartDiv.style.left=(AmCharts.findPosX(g.div)+f)+"px"
}else{g.legendDiv.style.left=e+"px"
}g.legendDiv.style.top=(a-b)/2+"px"
}}return e},addListeners:function(){var a=this;
if(a.touchEventsEnabled&&a.panEventsEnabled){a.chartDiv.addEventListener("touchstart",function(b){a.handleTouchMove.call(a,b)
},true);a.chartDiv.addEventListener("touchmove",function(b){a.handleTouchMove.call(a,b)
},true);a.chartDiv.addEventListener("touchstart",function(b){a.handleTouchStart.call(a,b)
});a.chartDiv.addEventListener("touchend",function(b){a.handleTouchEnd.call(a,b)
})}else{if(AmCharts.isNN){a.chartDiv.addEventListener("mousedown",function(b){a.handleMouseDown.call(a,b)
},true);a.chartDiv.addEventListener("mouseover",function(b){a.handleMouseOver.call(a,b)
},true);a.chartDiv.addEventListener("mouseout",function(b){a.handleMouseOut.call(a,b)
},true)}if(AmCharts.isIE){a.chartDiv.attachEvent("onmousedown",function(b){a.handleMouseDown.call(a,b)
});a.chartDiv.attachEvent("onmouseover",function(b){a.handleMouseOver.call(a,b)
});a.chartDiv.attachEvent("onmouseout",function(b){a.handleMouseOut.call(a,b)
})}}},dispatchDataUpdatedEvent:function(){var a=this;
if(a.dispatchDataUpdated){a.dispatchDataUpdated=false;
a.fire("dataUpdated",{type:"dataUpdated"})
}},drb:function(){var d=this;
var a="moc.strahcma".split("").reverse().join("");
var i=window.location.hostname;
var h=i.split(".");if(h.length>=2){var e=h[h.length-2]+"."+h[h.length-1]
}

/***************** modified to hide amchart links from chart container - Aug 30 2011 ****************/
if(e!=a){a=a+"/?utm_source=swf&utm_medium=demo&utm_campaign=jsDemo";
var f=d.container.set();
//var c=AmCharts.rect(d.container,145,20,"#FFFFFF",1);
//var g=AmCharts.text(d.container,2,2,"moc.strahcma yb trahc".split("").reverse().join(""),{fill:"#000000","font-family":"Verdana","font-size":11,"text-anchor":"start"});
//g.translate(5+","+8);
//f.push(c);
//f.push(g);
d.set.push(f);
f.click(function(){window.location.href="http://"+a
});
/***************** modified to hide amchart links from chart container - Aug 30 2011 ****************/


for(var b=0;b<f.length;
b++){f[b].attr({cursor:"pointer"})
}}},invalidateSize:function(){var a=this;
a.measure();if(a.realWidth!=a.previousWidth||a.realHeight!=a.previousHeight){if(a.chartCreated){if(a.legend){a.legend.invalidateSize()
}a.initChart()}}},validateData:function(){var a=this;
if(a.chartCreated){a.dataChanged=true;
a.initChart()}},validateNow:function(){this.initChart()
},showItem:function(a){var b=this;
a.hidden=false;b.initChart()
},hideItem:function(a){var b=this;
a.hidden=true;b.initChart()
},hideBalloon:function(){var a=this;
a.hoverInt=setTimeout(function(){a.hideBalloonReal.call(a)
},100)},hideBalloonReal:function(){var a=this;
if(a.balloon){a.balloon.hide()
}},showBalloon:function(d,c,b,a,f){var e=this;
if(e.balloon.enabled){e.balloon.followCursor(false);
e.balloon.changeColor(c);
if(!b){e.balloon.setPosition(a,f)
}e.balloon.followCursor(b);
if(d){e.balloon.showBalloon(d)
}}},handleTouchMove:function(c){var g=this;
g.hideBalloon();var a;
var f;var d=g.chartDiv;
if(c.touches){var b=c.touches.item(0);
g.mouseX=b.clientX-AmCharts.findPosX(d);
g.mouseY=b.clientY-AmCharts.findPosY(d)
}},handleMouseOver:function(a){AmCharts.resetMouseOver();
this.mouseIsOver=true
},handleMouseOut:function(a){this.mouseIsOver=false
},handleMouseMove:function(b){var f=this;
var d=f.chartDiv;if(!b){b=window.event
}var a;var c;if(document.attachEvent&&!window.opera){if(AmCharts.IEversion<9){a=b.x;
c=b.y}else{a=b.offsetX;
c=b.offsetY}}if(AmCharts.isNN){if(!isNaN(b.layerX)){a=b.layerX;
c=b.layerY}if(!isNaN(b.offsetX)&&f.divIsFixed){a=b.offsetX;
c=b.offsetY}}if(window.opera){if(f.divIsFixed){a=b.clientX-AmCharts.findPosX(d);
c=b.clientY-AmCharts.findPosY(d)
}else{a=b.pageX-AmCharts.findPosX(d);
c=b.pageY-AmCharts.findPosY(d)
}}f.mouseX=a;f.mouseY=c
},handleTouchStart:function(a){AmCharts.resetMouseOver();
this.mouseIsOver=true;
this.handleMouseDown(a)
},handleTouchEnd:function(a){this.handleReleaseOutside(a)
},handleReleaseOutside:function(a){},handleMouseDown:function(a){AmCharts.resetMouseOver();
this.mouseIsOver=true;
if(a){if(a.preventDefault){a.preventDefault()
}}},addLegend:function(b){var c=this;
c.legend=b;b.chart=this;
b.div=c.legendDiv;var a=c.handleLegendEvent;
c.listenTo(b,"showItem",a);
c.listenTo(b,"hideItem",a);
c.listenTo(b,"clickMarker",a);
c.listenTo(b,"rollOverItem",a);
c.listenTo(b,"rollOutItem",a);
c.listenTo(b,"rollOverMarker",a);
c.listenTo(b,"rollOutMarker",a);
c.listenTo(b,"clickLabel",a)
},removeLegend:function(){this.legend=undefined
},handleResize:function(){var a=this;
if(AmCharts.isPercents(a.width)||AmCharts.isPercents(a.height)){a.invalidateSize()
}}});AmCharts.Slice=AmCharts.Class({construct:function(){}});
AmCharts.SerialDataItem=AmCharts.Class({construct:function(){}});
AmCharts.GraphDataItem=AmCharts.Class({construct:function(){}});
AmCharts.Guide=AmCharts.Class({construct:function(){}});
AmCharts.toBoolean=function(b,a){if(b==undefined){return a
}switch(String(b).toLowerCase()){case"true":case"yes":case"1":return true;
case"false":case"no":case"0":case null:return false;
default:return Boolean(b)
}};AmCharts.formatMilliseconds=function(d,c){if(d.indexOf("fff")!=-1){var b=c.getMilliseconds();
var a=String(b);if(b<10){a="00"+b
}if(b>=10&&b<100){a="0"+b
}d=d.replace(/fff/g,a)
}return d};AmCharts.callMethod=function(g,a){for(var c=0;
c<a.length;c++){var b=a[c];
if(b){if(b[g]){b[g]()
}var e=b.length;if(e>0){for(var d=0;
d<e;d++){var f=b[d];if(f){if(f[g]){f[g]()
}}}}}}},AmCharts.toNumber=function(a){if(typeof(a)=="number"){return a
}else{return Number(String(a).replace(/[^0-9\-.]+/g,""))
}};AmCharts.toColor=function(c){if(c!=""&&c!=undefined){if(c.indexOf(",")!=-1){var a=c.split(",");
for(var b=0;b<a.length;
b++){var d=a[b].substring(a[b].length-6,a[b].length);
a[b]="#"+d}c=a}else{c=c.substring(c.length-6,c.length);
c="#"+c}}return c},AmCharts.toSvgColor=function(a,d){if(typeof(a)=="object"){if(d==undefined){d=90
}var b=d;for(var c=0;
c<a.length;c++){b+="-"+a[c]
}return b}else{return a
}};AmCharts.toCoordinate=function(c,a,b){var d;
if(c!=undefined){c=c.toString();
if(b){if(b<a){a=b}}d=Number(c);
if(c.indexOf("!")!=-1){d=a-Number(c.substr(1))
}if(c.indexOf("%")!=-1){d=a*Number(c.substr(0,c.length-1))/100
}}return d};AmCharts.fitToBounds=function(c,b,a){if(c<b){c=b
}if(c>a){c=a}return c
};AmCharts.isDefined=function(a){if(a==undefined){return false
}else{return true}};AmCharts.stripNumbers=function(a){return a.replace(/[0-9]+/g,"")
};AmCharts.extractPeriod=function(c){var a=AmCharts.stripNumbers(c);
var b=1;if(a!=c){b=Number(c.slice(0,c.indexOf(a)))
}return{period:a,count:b}
};AmCharts.resetDateToMin=function(a,f,d){var g;
var e;var h;var i;var c;
var j;var b;switch(f){case"YYYY":g=Math.floor(a.getFullYear()/d)*d;
e=0;h=1;i=0;c=0;j=0;b=0;
break;case"MM":g=a.getFullYear();
e=Math.floor((a.getMonth())/d)*d;
h=1;i=0;c=0;j=0;b=0;break;
case"WW":g=a.getFullYear();
e=a.getMonth();var k=a.getDay();
if(k==0){k=7}h=a.getDate()-k+1;
i=0;c=0;j=0;b=0;break;
case"DD":g=a.getFullYear();
e=a.getMonth();h=Math.floor((a.getDate())/d)*d;
i=0;c=0;j=0;b=0;break;
case"hh":g=a.getFullYear();
e=a.getMonth();h=a.getDate();
i=Math.floor(a.getHours()/d)*d;
c=0;j=0;b=0;break;case"mm":g=a.getFullYear();
e=a.getMonth();h=a.getDate();
i=a.getHours();c=Math.floor(a.getMinutes()/d)*d;
j=0;b=0;break;case"ss":g=a.getFullYear();
e=a.getMonth();h=a.getDate();
i=a.getHours();c=a.getMinutes();
j=Math.floor(a.getSeconds()/d)*d;
b=0;break;case"fff":g=a.getFullYear();
e=a.getMonth();h=a.getDate();
i=a.getHours();c=a.getMinutes();
j=a.getSeconds();b=Math.floor(a.getMilliseconds()/d)*d;
break}a=new Date(g,e,h,i,c,j,b);
return a};AmCharts.getPeriodDuration=function(c,a){if(a==undefined){a=1
}var b;switch(c){case"YYYY":b=31622400000;
break;case"MM":b=2678400000;
break;case"WW":b=604800000;
break;case"DD":b=86400000;
break;case"hh":b=3600000;
break;case"mm":b=60000;
break;case"ss":b=1000;
break;case"fff":b=1;break
}return b*a};AmCharts.roundTo=function(b,a){if(a<0){return b
}else{var c=Math.pow(10,a);
return(Math.round(b*c)/c)
}};AmCharts.intervals={s:{nextInterval:"ss",contains:1000},ss:{nextInterval:"mm",contains:60,count:0},mm:{nextInterval:"hh",contains:60,count:1},hh:{nextInterval:"DD",contains:24,count:2},DD:{nextInterval:"",contains:Infinity,count:3}};
AmCharts.getMaxInterval=function(c,a){var b=AmCharts.intervals;
if(c>=b[a].contains){c=Math.round(c/b[a].contains);
a=b[a].nextInterval;return AmCharts.getMaxInterval(c,a)
}else{if(a=="ss"){return b[a].nextInterval
}else{return a}}};AmCharts.formatDuration=function(c,a,k,f,b,g){var e=AmCharts.intervals;
var j=g.decimalSeparator;
if(c>=e[a].contains){var h=c-Math.floor(c/e[a].contains)*e[a].contains;
if(a=="ss"){h=AmCharts.formatNumber(h,g);
if(h.split(j)[0].length==1){h="0"+h
}}if((a=="mm"||a=="hh")&&h<10){h="0"+h
}k=h+""+f[a]+""+k;c=Math.floor(c/e[a].contains);
a=e[a].nextInterval;return AmCharts.formatDuration(c,a,k,f,b,g)
}else{if(a=="ss"){c=AmCharts.formatNumber(c,g);
if(c.split(j)[0].length==1){c="0"+c
}}if((a=="mm"||a=="hh")&&c<10){c="0"+c
}k=c+""+f[a]+""+k;if(e[b].count>e[a].count){for(var d=e[a].count;
d<e[b].count;d++){a=e[a].nextInterval;
if(a=="ss"||a=="mm"||a=="hh"){k="00"+f[a]+""+k
}else{if(a=="DD"){k="0"+f[a]+""+k
}}}}if(k.charAt(k.length-1)==":"){k=k.substring(0,k.length-1)
}return k}};AmCharts.formatNumber=function(c,j,g,e,m){c=AmCharts.roundTo(c,j.precision);
if(isNaN(g)){g=j.precision
}var n=j.decimalSeparator;
var h=j.thousandsSeparator;
if(c<0){var a="-"}else{var a=""
}c=Math.abs(c);var k=c.toString();
if(k.indexOf("e")==-1){var f=k.split(".");
var l="";var d=f[0].toString();
for(var b=d.length;b>=0;
b=b-3){if(b!=d.length){if(b!=0){l=d.substring(b-3,b)+h+l
}else{l=d.substring(b-3,b)+l
}}else{l=d.substring(b-3,b)
}}if(f[1]!=undefined){l=l+n+f[1]
}if(g!=undefined&&g>0&&l!="0"){l=AmCharts.addZeroes(l,n,g)
}}else{l=k}l=a+l;if(a==""&&e==true&&c!=0){l="+"+l
}if(m==true){l=l+"%"}return(l)
};AmCharts.addZeroes=function(b,c,a){var d=b.split(c);
if(d[1]==undefined&&a>0){d[1]="0"
}if(d[1].length<a){d[1]=d[1]+"0";
return AmCharts.addZeroes(d[0]+c+d[1],c,a)
}else{if(d[1]!=undefined){return d[0]+c+d[1]
}else{return d[0]}}};
AmCharts.scientificToNormal=function(b){var f=b.toString();
var e;var a=f.split("e");
if(a[1].substr(0,1)=="-"){e="0.";
for(var d=0;d<Math.abs(Number(a[1]))-1;
d++){e+="0"}e+=a[0].split(".").join("")
}else{var g=0;var c=a[0].split(".");
if(c[1]){g=c[1].length
}e=a[0].split(".").join("");
for(var d=0;d<Math.abs(Number(a[1]))-g;
d++){e+="0"}}return e
};AmCharts.toScientific=function(b,d){if(b==0){return"0"
}var c=Math.floor(Math.log(Math.abs(b))*Math.LOG10E);
var a=Math.pow(10,c);
mantissa=mantissa.toString().split(".").join(d);
return mantissa.toString()+"e"+c
};AmCharts.generateGradient=function(a,e,c){var d=e;
if(c){for(var b=c.length-1;
b>=0;b--){d+="-"+AmCharts.adjustLuminosity(a,c[b]/100)
}}else{if(typeof(a)=="object"){if(a.length>1){for(var b=0;
b<a.length;b++){d+="-"+a[b]
}}else{d=a[0]}}else{d=a
}}return d};AmCharts.randomColor=function(){function a(){return Math.floor(Math.random()*256).toString(16)
}return"#"+a()+a()+a()
};AmCharts.hitTest=function(e,c,g){var a=false;
var d=e.x;var b=e.x+e.width;
var i=e.y;var h=e.y+e.height;
var f=AmCharts.isInRectangle;
if(!a){a=f(d,i,c)}if(!a){a=f(d,h,c)
}if(!a){a=f(b,i,c)}if(!a){a=f(b,h,c)
}if(!a&&g!=true){a=AmCharts.hitTest(c,e,true)
}return a};AmCharts.isInRectangle=function(a,c,b){if(a>=b.x&&a<=b.x+b.width&&c>=b.y&&c<=b.y+b.height){return true
}else{return false}};
AmCharts.isPercents=function(a){if(String(a).indexOf("%")!=-1){return true
}};AmCharts.dayNames=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
AmCharts.shortDayNames=["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
AmCharts.monthNames=["January","February","March","April","May","June","July","August","September","October","November","December"];
AmCharts.shortMonthNames=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
AmCharts.formatDate=function(v,t){var i=v.getFullYear();
var u=String(i).substr(-2,2);
var z=v.getMonth();var g=z+1;
if(z<9){g="0"+g}var x=v.getDate();
var j=x;if(x<10){j="0"+x
}var q=v.getDay();var c="0"+q;
var s=v.getHours();var r=s;
if(r==24){r=0}var y=r;
if(y<10){y="0"+y}t=t.replace(/JJ/g,y);
t=t.replace(/J/g,r);var l=s;
if(l==0){l=24}var e=l;
if(e<10){e="0"+e}t=t.replace(/HH/g,e);
t=t.replace(/H/g,l);var w=s;
if(w>11){w-=12}var B=w;
if(B<10){B="0"+B}t=t.replace(/KK/g,B);
t=t.replace(/K/g,w);var a=s;
if(a>12){a-=12}var m=a;
if(m<10){m="0"+m}t=t.replace(/LL/g,m);
t=t.replace(/L/g,a);var p=v.getMinutes();
var b=p;if(b<10){b="0"+b
}t=t.replace(/NN/g,b);
t=t.replace(/N/g,p);var k=v.getSeconds();
var o=k;if(o<10){o="0"+o
}t=t.replace(/SS/g,o);
t=t.replace(/S/g,k);var h=v.getMilliseconds();
var n=h;if(n<10){n="00"+n
}if(n<100){n="0"+n}var A=h;
if(A<10){A="00"+A}t=t.replace(/QQQ/g,n);
t=t.replace(/QQ/g,A);
t=t.replace(/Q/g,h);if(s<12){t=t.replace(/A/g,"am")
}else{t=t.replace(/A/g,"pm")
}t=t.replace(/YYYY/g,"@IIII@");
t=t.replace(/YY/g,"@II@");
t=t.replace(/MMMM/g,"@XXXX@");
t=t.replace(/MMM/g,"@XXX@");
t=t.replace(/MM/g,"@XX@");
t=t.replace(/M/g,"@X@");
t=t.replace(/DD/g,"@RR@");
t=t.replace(/D/g,"@R@");
t=t.replace(/EEEE/g,"@PPPP@");
t=t.replace(/EEE/g,"@PPP@");
t=t.replace(/EE/g,"@PP@");
t=t.replace(/E/g,"@P@");
t=t.replace(/@IIII@/g,i);
t=t.replace(/@II@/g,u);
t=t.replace(/@XXXX@/g,AmCharts.monthNames[z]);
t=t.replace(/@XXX@/g,AmCharts.shortMonthNames[z]);
t=t.replace(/@XX@/g,g);
t=t.replace(/@X@/g,(z+1));
t=t.replace(/@RR@/g,j);
t=t.replace(/@R@/g,x);
t=t.replace(/@PPPP@/g,AmCharts.dayNames[q]);
t=t.replace(/@PPP@/g,AmCharts.shortDayNames[q]);
t=t.replace(/@PP@/g,c);
t=t.replace(/@P@/g,q);
return t};AmCharts.findPosX=function(a){var b=a.offsetLeft;
while((a=a.offsetParent)){b+=a.offsetLeft;
if(a!=document.body&&a!=document.documentElement){b-=a.scrollLeft
}}return b};AmCharts.findPosY=function(a){var b=a.offsetTop;
while((a=a.offsetParent)){b+=a.offsetTop;
if(a!=document.body&&a!=document.documentElement){b-=a.scrollTop
}}return b};AmCharts.findIfFixed=function(a){while((a=a.offsetParent)){if(a.style.position=="fixed"){return true
}}return false};AmCharts.formatString=function(b,c,e,d){b=b.replace(/<br>/g,"\n");
var a=AmCharts.formatNumber;
if(c.value!=undefined){b=b.replace(/\[\[value\]\]/g,a(c.value,e))
}if(c.open!=undefined){b=b.replace(/\[\[open\]\]/g,a(c.open,e))
}if(c.close!=undefined){b=b.replace(/\[\[close\]\]/g,a(c.close,e))
}if(c.low!=undefined){b=b.replace(/\[\[low\]\]/g,a(c.low,e))
}if(c.high!=undefined){b=b.replace(/\[\[high\]\]/g,a(c.high,e))
}if(c.x!=undefined){b=b.replace(/\[\[x\]\]/g,a(c.x,e))
}if(c.y!=undefined){b=b.replace(/\[\[y\]\]/g,a(c.y,e))
}if(c.percents!=undefined){b=b.replace(/\[\[percents\]\]/g,a(c.percents,d))
}if(c.title!=undefined){b=b.replace(/\[\[title\]\]/g,c.title)
}else{b=b.replace(/\[\[title\]\]/g,"")
}if(c.category!=undefined){b=b.replace(/\[\[category\]\]/g,c.category)
}else{b=b.replace(/\[\[category\]\]/g,"")
}if(c.graphTitle!=undefined){b=b.replace(/\[\[graphTitle\]\]/g,c.graphTitle)
}else{b=b.replace(/\[\[graphTitle\]\]/g,"")
}if(c.description!=undefined){b=b.replace(/\[\[description\]\]/g,c.description)
}else{b=b.replace(/\[\[description\]\]/g,"")
}return b};AmCharts.addPrefix=function(i,j,e,f){var h=AmCharts.formatNumber(i,f);
var d="";var g;var b;
var a;if(i==0){return"0"
}if(i<0){d="-"}i=Math.abs(i);
if(i>1){for(g=j.length-1;
g>-1;g--){if(i>=j[g].number){b=i/j[g].number;
a=Number(f.precision);
if(a<1){a=1}b=AmCharts.roundTo(b,a);
h=d+""+b+""+j[g].prefix;
break}}}else{for(g=0;
g<e.length;g++){if(i<=e[g].number){b=i/e[g].number;
a=Math.abs(Math.round(Math.log(b)*Math.LOG10E));
b=AmCharts.roundTo(b,a);
h=d+""+b+""+e[g].prefix;
break}}}return h};AmCharts.removeSet=function(e){if(e){for(var a=0;
a<e.length;a++){var d=e[a];
if(d.length>0){AmCharts.removeSet(d)
}var c=d.clip;var b=d.node;
if(b){if(b.clipRect){c=b.clipRect
}if(b.parentNode){d.remove()
}}if(c){if(c.parentNode){c.parentNode.removeChild(c)
}delete c}}}};AmCharts.copyProperties=function(a,d,c){for(var b=0;
b<c.length;b++){d[c[b]]=a[c[b]]
}};AmCharts.recommended=function(){var b="js";
var a=document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure","1.1");
if(!a){if(swfobject){if(swfobject.hasFlashPlayerVersion("8")){b="flash"
}}}return b};AmCharts.Bezier=AmCharts.Class({construct:function(a,p,m,g,f,o,c,d,b,r){if(typeof(c)=="object"){c=c[0]
}if(typeof(d)=="object"){d=d[0]
}var l="";if(b==1){l="."
}if(b>1){l="-"}var j={stroke:g,fill:c,"fill-opacity":d,"stroke-dasharray":l,opacity:f,"stroke-width":o};
var e="L";var n=p.length;
this.lineArray=["M",p[0],m[0]];
var q=[];for(var h=0;
h<n;h++){q.push({x:p[h],y:m[h]})
}if(q.length>1){var k=this.interpolate(q);
this.drawBeziers(k)}this.lineArray=this.lineArray.concat(r);
this.path=a.path(this.lineArray).attr(j)
},interpolate:function(l){var j=[];
j.push({x:l[0].x,y:l[0].y});
var e=l[1].x-l[0].x;var c=l[1].y-l[0].y;
j.push({x:l[0].x+e/6,y:l[0].y+c/6});
var b=3;var a=6;for(var d=1;
d<l.length-1;d++){var k=l[d-1];
var g=l[d];var f=l[d+1];
e=f.x-g.x;c=f.y-k.y;var h=g.x-k.x;
if(h>e){h=e}j.push({x:g.x-h/b,y:g.y-c/a});
j.push({x:g.x,y:g.y});
j.push({x:g.x+h/b,y:g.y+c/a})
}c=l[l.length-1].y-l[l.length-2].y;
e=l[l.length-1].x-l[l.length-2].x;
j.push({x:l[l.length-1].x-e/b,y:l[l.length-1].y-c/6});
j.push({x:l[l.length-1].x,y:l[l.length-1].y});
return j},drawBeziers:function(b){for(var a=0;
a<(b.length-1)/3;a++){this.drawBezierMidpoint(b[3*a],b[3*a+1],b[3*a+2],b[3*a+3])
}},drawBezierMidpoint:function(c,b,o,k){var f=this.getPointOnSegment(c,b,3/4);
var d=this.getPointOnSegment(k,o,3/4);
var p=(k.x-c.x)/16;var l=(k.y-c.y)/16;
var i=this.getPointOnSegment(c,b,3/8);
var h=this.getPointOnSegment(f,d,3/8);
h.x-=p;h.y-=l;var g=this.getPointOnSegment(d,f,3/8);
g.x+=p;g.y+=l;var e=this.getPointOnSegment(k,o,3/8);
var a=this.getMiddle(i,h);
var n=this.getMiddle(f,d);
var j=this.getMiddle(g,e);
var m=this.lineArray;
m.push("Q",i.x,i.y,a.x,a.y);
m.push("Q",h.x,h.y,n.x,n.y);
m.push("Q",g.x,g.y,j.x,j.y);
m.push("Q",e.x,e.y,k.x,k.y)
},getMiddle:function(c,b){var a={x:(c.x+b.x)/2,y:(c.y+b.y)/2};
return a},getPointOnSegment:function(c,b,d){var a={x:c.x+(b.x-c.x)*d,y:c.y+(b.y-c.y)*d};
return a}});AmCharts.Cuboid=AmCharts.Class({construct:function(b,c,k,m,l,a,e,d,g,f,j,h){var i=this;
i.set=b.set();i.container=b;
i.h=k;i.w=c;i.dx=m;i.dy=l;
i.colors=a;i.alpha=e;
i.bwidth=d;i.bcolor=g;
i.balpha=f;if(typeof(a)!="object"){i.colors=[a]
}if(c<0&&j==0){j=180}if(k<0&&j==270){j=90
}i.gradientRotation=j;
if(m==0&&l==0){i.cornerRadius=h
}i.draw()},draw:function(){var B=this;
var v=B.set;v.remove();
var t=B.container;var E=0;
var r=Math.abs(B.w);var D=Math.abs(B.h);
var s=B.dx;var q=B.dy;
var n=B.colors;var f=B.alpha;
var y=B.bwidth;var A=B.bcolor;
var d=B.balpha;var a=B.gradientRotation;
var b=B.cornerRadius;
if(s>0||q>0){var g=n[n.length-1];
if(D>0){g=n[0]}var j=AmCharts.adjustLuminosity(g,-0.2);
var u=AmCharts.polygon(t,[0,s,r+s,r,0],[0,q,q,0,0],[j],f,0,0,0,a);
v.push(u);var e=AmCharts.line(t,[0,s,r+s],[0,q,q],A,d,y);
v.push(e);var j=AmCharts.adjustLuminosity(n[0],-0.2);
if(D>0&&r>0){var z=AmCharts.rect(t,r,D,j,f,0,0,0,0,a);
v.push(z);z.translate(s+","+(-D+q));
var p=AmCharts.line(t,[s,s],[q,-D+q],A,d,y);
v.push(p);var C=AmCharts.polygon(t,[0,0,s,s,0],[0,-D,-D+q,q,0],j,f,0,0,0,a);
v.push(C);var o=AmCharts.polygon(t,[0,0,s,s,0],[0,-D,-D+q,q,0],j,f,0,0,0,a);
o.translate(r+","+0);
v.push(o);var l=AmCharts.line(t,[0,s,s,0],[-D,-D+q,q,0],A,d,y);
v.push(l);l.translate(r+","+0)
}var k=n[0];var m=f[0];
if(D>0){k=n[n.length-1];
m=f[f.length-1]}var j=AmCharts.adjustLuminosity(k,0.2);
var x=AmCharts.polygon(t,[0,s,r+s,r,0],[0,q,q,0,0],[j],f,0,0,0,a);
x.translate(0+","+(-D));
v.push(x);var c=AmCharts.line(t,[0,s,r+s],[0,q,q],A,d,y);
c.translate(0+","+(-D));
v.push(c)}if(D==0){D=1;
f=0;d=0}var i=AmCharts.rect(t,r,D,n,f,y,A,d,b,a);
i.translate(0+","+(-D));
v.push(i);B.front=i},y:function(a){var d=this;
a=Math.round(a*10)/10;
var b=d.h;var c=d.set;
if(b<0){c.translate(0+","+a)
}else{c.translate(0+","+(a+b))
}},x:function(b){var d=this;
b=Math.round(b*10)/10;
var a=d.w;var c=d.set;
if(a<0){c.translate((b+a)+","+0)
}else{c.translate(b+","+0)
}},width:function(a){var b=this;
b.w=a;b.draw()},height:function(a){var b=this;
b.h=a;b.draw()},getX:function(){return this.front.getBBox().x
},getY:function(){return this.front.getBBox().y
}});AmCharts.AmLegend=AmCharts.Class({construct:function(){var a=this;
a.createEvents("rollOverMarker","rollOverItem","rollOutMarker","rollOutItem","showItem","hideItem","clickMarker","rollOverItem","rollOutItem","clickLabel");
a.position="bottom";a.color="#000000";
a.borderColor="#000000";
a.borderAlpha=0;a.markerLabelGap=5;
a.verticalGap=10;a.align="left";
a.horizontalGap=0;a.spacing=10;
a.markerDisabledColor="#AAB3B3";
a.markerType="square";
a.markerSize=16;a.markerBorderAlpha=0;
a.markerBorderThickness=1;
a.marginTop=10;a.marginBottom=10;
a.marginRight=15;a.marginLeft=80;
a.valueWidth=50;a.switchable=true;
a.switchType="x";a.switchColor="#FFFFFF";
a.rollOverColor="#CC0000";
a.selectedColor;a.reversedOrder=false;
a.labelText="[[title]]";
a.useMarkerColorForLabels=false;
a.rollOverGraphAlpha=1;
a.textClickEnabled=true;
a.usePositiveNegativeOnPercentsOnly=false
},setData:function(a){var b=this;
b.data=a;b.invalidateSize()
},invalidateSize:function(){var b=this;
b.destroy();b.entries=[];
b.valueLabels=[];var a=b.data;
if(a){if(a.length>0){b.drawLegend()
}}},drawLegend:function(){var g=this;
var h=g.chart;var f=g.position;
var b=g.width;var k=h.realWidth;
var j=h.realHeight;var a=g.div;
var e=g.data;if(f=="right"||f=="left"){g.maxColumns=1;
g.marginRight=10;g.marginLeft=10
}var c;if(b!=undefined){c=AmCharts.toCoordinate(b,k)
}else{c=h.realWidth}g.divWidth=c;
a.style.width=c+"px";
if(!g.container){g.container=Raphael(a,c,j)
}g.maxLabelWidth=0;g.index=0;
for(var d=0;d<e.length;
d++){g.createEntry(e[d])
}g.index=0;for(var d=0;
d<e.length;d++){g.createValue(e[d])
}g.arrangeEntries();g.updateValues()
},arrangeEntries:function(){var z=this;
var G=z.position;var A=z.marginLeft;
var j=z.marginRight;var e=z.marginTop;
var k=z.marginBottom;
var o=z.horizontalGap;
var u=z.div;var b=z.divWidth;
var E=z.maxColumns;var m=z.verticalGap;
var r=b-j-A;var C=0;var v=0;
var s=z.container.set();
z.set=s;var t=z.entries;
for(var B=0;B<t.length;
B++){var a=t[B].getBBox();
var D=a.width;if(D>C){C=D
}var g=a.height;if(g>v){v=g
}}var l=0;var d=0;for(var B=0;
B<t.length;B++){var c=t[B];
if(z.reversedOrder){c=t[t.length-B-1]
}var a=c.getBBox();var p=o+d*(C+z.spacing+z.markerLabelGap);
if(p+a.width>r&&B>0){l++;
d=0;p=o}var n=m+(v+m)*l;
c.translate(p+","+n);
d++;if(!isNaN(E)){if(d>=E){d=0;
l++}}s.push(c)}var a=s.getBBox();
var h=a.height+2*m;if(G=="left"||G=="right"){var F=a.width+2*o;
var b=F+A+j;u.style.width=b+"px"
}else{var F=b-A-j}var f=AmCharts.rect(z.container,F,h,z.backgroundColor,z.backgroundAlpha,1,z.borderColor,z.borderAlpha);
f.toBack();s.push(f);
s.translate(A+","+e);
if(G=="top"||G=="bottom"){s.pop();
if(z.align=="center"){s.translate(((F-(a.width))/2)+","+0)
}}var q=h+e+k;u.style.height=q+"px"
},createEntry:function(q){if(q.visibleInLegend!==false){var m=this;
var l=m.chart;var j=q.markerType;
if(!j){j=m.markerType
}var n=q.color;var d=q.alpha;
if(q.legendKeyColor){n=q.legendKeyColor()
}if(q.legendKeyAlpha){d=q.legendKeyAlpha()
}if(q.hidden==true){n=m.markerDisabledColor
}var g=m.createMarker(j,n,d);
if(g.length>0){for(var p=0;
p<g.length;p++){g[p].dItem=q
}}else{g.dItem=q}var r=m.switchType;
if(r){if(r=="x"){var f=m.createX()
}else{var f=m.createV()
}}f.dItem=q;if(q.hidden!=true){if(r=="x"){f.hide()
}else{f.show()}}else{if(r!="x"){f.hide()
}}var o=m.container.set([g,f]);
if(l.touchEventsEnabled){o.touchend(function(){m.clickMarker(this.dItem)
});o.touchstart(function(){m.rollOverMarker(this.dItem)
})}else{o.hover(function(){m.rollOverMarker(this.dItem)
},function(){m.rollOutMarker(this.dItem)
}).click(function(){m.clickMarker(this.dItem)
})}var s=m.color;if(q.showBalloon&&m.textClickEnabled&&m.selectedColor!=undefined){s=m.selectedColor
}if(m.useMarkerColorForLabels){s=n
}if(q.hidden==true){s=m.markerDisabledColor
}var b=m.chart.fontSize;
if(!isNaN(m.fontSize)){b=m.fontSize
}var k=AmCharts.formatString(m.labelText,q,l.numberFormatter,l.percentFormatter);
if(k){var h=AmCharts.text(m.container,m.markerSize+m.markerLabelGap,m.markerSize/2,k,{fill:s,"text-anchor":"start","font-family":m.chart.fontFamily,"font-size":b});
var a=h.getBBox();var c=a.width;
if(m.maxLabelWidth<c){m.maxLabelWidth=c
}}var e=m.container.set();
if(g){e.push(g)}if(f){e.push(f)
}if(h){e.push(h)}m.entries[m.index]=e;
q.legendEntry=m.entries[m.index];
q.legendLabel=h;q.legendSwitch=f;
m.index++}},rollOverMarker:function(a){var b=this;
if(b.switchable){b.dispatch("rollOverMarker",a)
}else{b.dispatch("rollOverItem",a)
}},rollOutMarker:function(a){var b=this;
if(b.switchable){b.dispatch("rollOutMarker",a)
}else{b.dispatch("rollOutItem",a)
}},clickMarker:function(a){var b=this;
if(b.switchable){if(a.hidden==true){b.dispatch("showItem",a)
}else{b.dispatch("hideItem",a)
}}else{b.dispatch("clickMarker",a)
}},rollOverLabel:function(a){var b=this;
if(!a.hidden){if(a.legendLabel){a.legendLabel.attr({fill:b.rollOverColor})
}b.dispatch("rollOverItem",a)
}},rollOutLabel:function(b){var c=this;
if(!b.hidden){if(b.legendLabel){var a=c.color;
if(c.selectedColor!=undefined&&b.showBalloon){a=c.selectedColor
}b.legendLabel.attr({fill:a})
}c.dispatch("rollOutItem",b)
}},clickLabel:function(a){if(!a.hidden){this.dispatch("clickLabel",a)
}},dispatch:function(a,b){this.fire(a,{type:a,dataItem:b})
},createValue:function(d){var b=this;
if(d.visibleInLegend!==false){var c=b.maxLabelWidth;
if(b.valueText){var g=b.color;
if(b.useMarkerColorForLabels){g=color
}if(d.hidden==true){g=b.markerDisabledColor
}var i=b.chart.fontSize;
if(isNaN(b.fontSize)){i=b.fontSize
}var a=b.valueText;var f=b.maxLabelWidth+b.markerSize+b.markerLabelGap*2+b.valueWidth;
var h=AmCharts.text(b.container,f,b.markerSize/2,a,{fill:g,"text-anchor":"end","font-family":b.chart.fontFamily,"font-size":i});
b.entries[b.index].push(h);
c+=b.valueWidth+b.markerLabelGap;
h.dItem=d;b.valueLabels.push(h)
}b.index++;var e=b.container.rect(b.markerSize+b.markerLabelGap,0,c,b.markerSize).attr({stroke:"none",fill:"#FFCCFF","fill-opacity":0});
e.dItem=d;b.entries[b.index-1].push(e);
e.mouseover(function(){b.rollOverLabel(this.dItem)
}).mouseout(function(){b.rollOutLabel(this.dItem)
}).click(function(){b.clickLabel(this.dItem)
})}},createV:function(){var b=this;
var a=b.markerSize;return b.container.path(["M",a/5,a/3,"L",a/2,a-a/5,"L",a-a/5,a/5,"L",a/2,a/1.7,"Z"]).attr({fill:b.switchColor,stroke:b.switchColor})
},createX:function(){var b=this;
var a=b.markerSize-3;
return b.container.path(["M",3,3,"L",a,a,"M",a,3,"L",3,a]).attr({stroke:b.switchColor,"stroke-width":3})
},createMarker:function(h,b,a){var e=this;
var j=e.markerSize;var g=e.container;
var d;var i=e.markerBorderColor;
if(!i){i=b}var f={fill:b,stroke:i,opacity:a,"stroke-opacity":e.markerBorderAlpha,"stroke-width":e.markerBorderThickness};
switch(h){case"square":d=g.rect(0,0,j,j).attr(f);
break;case"circle":d=g.circle(j/2,j/2,j/2).attr(f);
break;case"line":d=g.path(["M",0,j/2,"L",j,j/2]).attr({stroke:b,"stroke-width":e.markerBorderThickness});
break;case"dashedLine":d=g.path(["M",0,j/2,"L",j/2-2,j/2,"M",j/2+2,j/2,"L",j,j/2]).attr({stroke:b,"stroke-width":e.markerBorderThickness});
break;case"triangleUp":d=g.path(["M",0,j,"L",j/2,0,"L",j,j,"L",0,j,"Z"]).attr(f);
break;case"triangleDown":d=g.path(["M",0,0,"L",j/2,j,"L",j,0,"L",0,0,"Z"]).attr(f);
break;case"bubble":f.fill=NaN;
f.gradient="r"+b+"-"+AmCharts.adjustLuminosity(b,-0.4);
d=g.circle(j/2,j/2,j/2).attr(f);
break;case"none":break
}return d},validateNow:function(){this.invalidateSize()
},updateValues:function(){var h=this;
var b=h.valueLabels;for(var g=0;
g<b.length;g++){var k=b[g];
var a=k.dItem;if(a.type!=undefined){if(a.currentDataItem){var c=h.valueText;
if(a.legendValueText){c=a.legendValueText
}var e=h.positiveValueColor;
var j=h.negativeValueColor;
if(a.hidden){e=NaN;j=NaN
}var d=AmCharts.formatString(c,a.currentDataItem.values,h.chart.numberFormatter,h.chart.percentFormatter,e,j,h.usePositiveNegativeOnPercentsOnly);
d=AmCharts.formatString(d,a.currentDataItem,h.chart.numberFormatter,h.chart.percentFormatter,e,j,h.usePositiveNegativeOnPercentsOnly);
d=h.cleanFromEmpty(d);
k.attr({text:d})}else{k.attr({text:" "})
}}else{var f=AmCharts.formatString(h.valueText,a,h.chart.numberFormatter,h.chart.percentFormatter);
k.attr({text:f})}}},cleanFromEmpty:function(b){var a=b.replace(/\[\[[^\]]*\]\]/,"");
return a},destroy:function(){var a=this.container;
if(a){a.clear()}}});AmCharts.AmBalloon=AmCharts.Class({construct:function(){var a=this;
a.enabled=true;a.fillColor="#CC0000";
a.fillAlpha=1;a.borderThickness=2;
a.borderColor="#FFFFFF";
a.borderAlpha=1;a.cornerRadius=6;
a.maximumWidth=220;a.horizontalPadding=8;
a.verticalPadding=5;a.pointerWidth=10;
a.pointerOrientation="vertical";
a.color="#FFFFFF";a.textShadowColor="#000000";
a.adjustBorderColor=false;
a.showBullet=true;a.follow=false;
a.show=false},draw:function(){var B=this;
var G=B.pointToX;var F=B.pointToY;
if(!isNaN(G)){var v=B.chart;
var s=v.container;AmCharts.removeSet(B.set);
var t=s.set();B.set=t;
if(B.show){var r=B.l;
var a=B.t;var p=B.r;var m=B.b;
var o=B.textShadowColor;
if(B.color==o){o=null
}var I=B.balloonColor;
var l=B.fillColor;var c=B.borderColor;
if(I!=undefined){if(B.adjustBorderColor){c=I
}else{l=I}}var E=B.horizontalPadding;
var e=B.verticalPadding;
var y=B.pointerWidth;
var j=B.pointerOrientation;
var d=B.cornerRadius;
var D=v.fontFamily;var u=B.fontSize;
if(u==undefined){u=v.fontSize
}var A=AmCharts.text(s,0,0,B.text,{fill:B.color,"font-family":D,"font-size":u});
t.push(A);if(o!=undefined){var k=AmCharts.text(s,1,1,B.text,{fill:o,opacity:0.4,"font-family":D,"font-size":u});
t.push(k)}var b=A.getBBox();
var C=b.height+2*e;var q=b.width+2*E;
if(window.opera){C+=6
}A.translate((q/2)+","+(C/2));
if(k){k.translate((q/2)+","+(C/2))
}var g;var f;if(j!="horizontal"){g=G-q/2;
if(F<a+C&&j!="down"){f=F+y
}else{f=F-C-y}}else{if(y*2>C){y=C/2
}f=F-C/2;if(G<r+(p-r)/2){g=G+y
}else{g=G-q-y}}if(f+C>=m){f=m-C
}if(f<a){f=a}if(g<r){g=r
}if(g+q>p){g=p-q}var i;
if(d>0){i=AmCharts.rect(s,q,C,[l],[B.fillAlpha],B.borderThickness,c,B.borderAlpha,B.cornerRadius);
if(B.showBullet){var z=AmCharts.circle(s,3,l,B.fillAlpha);
z.translate(G+","+F)}}else{var n=[];
var x=[];if(j!="horizontal"){var J=G-g;
if(J>q-y){J=q-y}if(J<y){J=y
}n=[0,J-y,G-g,J+y,q,q,0,0];
if(F<a+(m-a)/2&&j!="down"){x=[0,0,F-f+1,0,0,C,C,0]
}else{x=[C,C,F-f-1,C,C,0,0,C]
}}else{var H=F-f;if(H>C-y){H=C-y
}if(H<y){H=y}x=[0,H-y,F-f,H+y,C,C,0,0];
if(G<r+(p-r)/2){n=[0,0,G-g,0,0,q,q,0]
}else{n=[q,q,G-g,q,q,0,0,q]
}}i=AmCharts.polygon(s,n,x,l,B.fillAlpha,B.borderThickness,c,B.borderAlpha)
}B.set.push(i);i.toFront();
if(k){k.toFront()}A.toFront();
B.set.translate(g+","+f);
var b=i.getBBox();B.bottom=b.y+b.height;
B.yPos=b.y;if(z){B.set.push(z)
}}}},followMouse:function(){var g=this;
if(g.follow&&g.show){var c=g.chart.mouseX;
var b=g.chart.mouseY;
g.pointToX=c;g.pointToY=b;
if(c!=g.previousX||b!=g.previousY){g.previousX=c;
g.previousY=b;if(g.cornerRadius==0){g.draw()
}else{var f=g.set;if(f){var d=f.getBBox();
var a=c-d.width/2;var e=b-d.height-10;
if(a<g.l){a=g.l}if(a>g.r-d.width){a=g.r-d.width
}if(e<g.t){e=b+10}f.translate((a-d.x)+","+(e-d.y))
}}}}},changeColor:function(a){this.balloonColor=a
},setBounds:function(c,d,e,a){var f=this;
f.l=c;f.t=d;f.r=e;f.b=a
},showBalloon:function(a){var b=this;
b.text=a;b.show=true;
b.draw()},hide:function(){var a=this;
a.show=false;a.follow=false;
a.destroy()},setPosition:function(a,d,c){var b=this;
b.pointToX=a;b.pointToY=d;
if(c){if(a!=b.previousX||d!=b.previousY){b.draw()
}}b.previousX=a;b.previousY=d
},followCursor:function(c){var d=this;
d.follow=c;if(c){d.pShowBullet=d.showBullet;
d.showBullet=false}else{if(d.pShowBullet!=undefined){d.showBullet=d.pShowBullet
}}clearInterval(d.interval);
var b=d.chart.mouseX;
var a=d.chart.mouseY;
if(!isNaN(b)){if(c){d.pointToX=b;
d.pointToY=a;d.interval=setInterval(function(){d.followMouse.call(d)
},20)}}},destroy:function(){var a=this;
clearInterval(a.interval);
AmCharts.removeSet(a.set)
}});AmCharts.AmCoordinateChart=AmCharts.Class({inherits:AmCharts.AmChart,construct:function(){var a=this;
AmCharts.AmCoordinateChart.base.construct.call(a);
a.createEvents("rollOverGraphItem","rollOutGraphItem","clickGraphItem","doubleClickGraphItem");
a.plotAreaFillColors="#FFFFFF";
a.plotAreaFillAlphas=0;
a.plotAreaBorderColor="#000000";
a.plotAreaBorderAlpha=0;
a.startAlpha=0;a.startDuration=0;
a.startEffect="elastic";
a.sequencedAnimation=true;
a.colors=["#FF6600","#FCD202","#B0DE09","#0D8ECF","#2A0CD0","#CD0D74","#CC0000","#00CC00","#0000CC","#DDDDDD","#999999","#333333","#990000"];
a.balloonDateFormat="MMM DD, YYYY";
a.valueAxes=[];a.graphs=[]
},initChart:function(){var b=this;
AmCharts.AmCoordinateChart.base.initChart.call(b);
b.createValueAxes();var a=b.legend;
if(a){a.setData(b.graphs)
}},createValueAxes:function(){var b=this;
if(b.valueAxes.length==0){var a=new AmCharts.ValueAxis();
b.addValueAxis(a)}},parseData:function(){var a=this;
a.processValueAxes();
a.processGraphs()},parseSerialData:function(){var n=this;
AmCharts.AmSerialChart.base.parseData.call(n);
n.chartData=[];var u=n.dataProvider;
if(u){var f=false;if(n.categoryAxis){f=n.categoryAxis.parseDates
}if(f){var v=AmCharts.extractPeriod(n.categoryAxis.minPeriod);
var h=v.period;var d=v.count
}for(var q=0;q<u.length;
q++){var t={};var c=u[q];
var m=c[n.categoryField];
t.category=m;if(f){m=new Date(m);
m=AmCharts.resetDateToMin(m,h,d);
t.category=m;t.time=m.getTime()
}var g=n.valueAxes;t.axes={};
t.x={};for(var p=0;p<g.length;
p++){var s=g[p].id;t.axes[s]={};
t.axes[s].graphs={};var e=n.graphs;
for(var o=0;o<e.length;
o++){var b=e[o];var l=b.id;
if(b.valueAxis.id==s){t.axes[s].graphs[l]={};
var r={};r.index=q;var a={};
var w=Number(c[b.valueField]);
if(!isNaN(w)){a.value=w
}var w=Number(c[b.openField]);
if(!isNaN(w)){a.open=w
}var w=Number(c[b.closeField]);
if(!isNaN(w)){a.close=w
}var w=Number(c[b.lowField]);
if(!isNaN(w)){a.low=w
}var w=Number(c[b.highField]);
if(!isNaN(w)){a.high=w
}r.values=a;n.processFields(b,r,c);
r.category=String(t.category);
r.serialDataItem=t;r.graphTitle=b.title;
t.axes[s].graphs[l]=r
}}}n.chartData[q]=t}}},addValueAxis:function(a){var b=this;
a.chart=this;b.valueAxes.push(a);
b.validateData()},removeValueAxesAndGraphs:function(){var c=this;
var a=c.valueAxes;for(var b=a.length-1;
b>-1;b--){c.removeValueAxis(a[b])
}},removeValueAxis:function(f){var e=this;
var b=e.graphs;var c;
for(c=b.length-1;c>=0;
c--){var d=b[c];if(d){if(d.valueAxis==f){e.removeGraph(d)
}}}var a=e.valueAxes;
for(c=a.length-1;c>=0;
c--){if(a[c]==f){a.splice(c,1)
}}e.validateData()},addGraph:function(a){var b=this;
b.graphs.push(a);b.chooseGraphColor(a,b.graphs.length-1);
b.validateData()},removeGraph:function(c){var d=this;
var a=d.graphs;for(var b=a.length-1;
b>=0;b--){if(a[b]==c){a.splice(b,1);
c.destroy()}}d.validateData()
},processValueAxes:function(){var d=this;
var a=d.valueAxes;for(var b=0;
b<a.length;b++){var c=a[b];
c.chart=this;if(!c.id){c.id="valueAxis"+b
}if(d.usePrefixes===true||d.usePrefixes===false){c.usePrefixes=d.usePrefixes
}}},processGraphs:function(){var d=this;
var a=d.graphs;for(var b=0;
b<a.length;b++){var c=a[b];
c.chart=this;if(!c.valueAxis){c.valueAxis=d.valueAxes[0]
}if(!c.id){c.id="graph"+b
}}},formatString:function(l,i,m){var h=this;
var d=i.serialDataItem;
var g=h.categoryAxis;
if(g){if(g.parseDates){var k=h.balloonDateFormat;
var c=h.chartCursor;if(c){k=c.categoryBalloonDateFormat
}if(l.indexOf("[[category]]")!=-1){var j=AmCharts.formatDate(d.category,k);
var f=AmCharts.formatDate(d.category,k);
if(f.indexOf("fff")!=-1){f=AmCharts.formatMilliseconds(j,d.category)
}l=l.split("[[category]]").join(f)
}}}var b=m.numberFormatter;
if(!b){b=h.numberFormatter
}if(d){l=l.replace(/\[\[category\]\]/g,d.category)
}var a=m.valueAxis;if(a.duration){if(i.values.value){var e=AmCharts.formatDuration(i.values.value,a.duration,"",a.durationUnits,a.maxInterval,a.numberFormatter);
l=l.split("[[value]]").join(e)
}}l=AmCharts.formatString(l,i,b,h.percentFormatter);
l=AmCharts.formatString(l,i.values,b,h.percentFormatter);
return l},getBalloonColor:function(f,d){var h=this;
var c=f.lineColor;var b=f.balloonColor;
var a=f.fillColors;if(typeof(a)=="object"){c=a[0]
}else{if(a!=undefined){c=a
}}if(d.isNegative){var e=f.negativeLineColor;
var g=f.negativeFillColors;
if(typeof(g)=="object"){e=g[0]
}else{if(g!=undefined){e=g
}}if(e!=undefined){c=e
}}if(d.color!=undefined){c=d.color
}if(b==undefined){b=c
}return b},getGraphById:function(f){var e=this;
var c;var a=e.graphs;
for(var b=0;b<a.length;
b++){var d=a[b];if(d.id==f){c=d
}}return c},processFields:function(k,l,b){var f=this;
if(k.itemColors){var d=k.itemColors;
var g=l.index;if(g<d.length){l.color=d[g]
}else{l.color=AmCharts.randomColor()
}}var e=["color","alpha","fillColors","description","bullet","customBullet","bulletSize","bulletConfig","url"];
for(var c=0;c<e.length;
c++){var h=e[c];var j=k[h+"Field"];
if(j){var a=b[j];if(AmCharts.isDefined(a)){l[h]=a
}}}l.dataContext=b},chooseGraphColor:function(c,b){var d=this;
if(c.lineColor==undefined){var a;
if(d.colors.length-1>b){a=d.colors[b]
}else{a=AmCharts.randomColor()
}c.lineColor=a}},handleLegendEvent:function(d){var f=this;
var c=d.type;var b=d.dataItem;
if(b){var e=b.hidden;
var a=b.showBalloon;switch(c){case"clickMarker":if(a){f.hideGraphsBalloon(b)
}else{f.showGraphsBalloon(b)
}break;case"clickLabel":if(a){f.hideGraphsBalloon(b)
}else{f.showGraphsBalloon(b)
}break;case"rollOverItem":if(!e){f.highlightGraph(b)
}break;case"rollOutItem":if(!e){f.unhighlightGraph()
}break;case"hideItem":f.hideGraph(b);
break;case"showItem":f.showGraph(b);
break}}},highlightGraph:function(a){var f=this;
var b=f.graphs;var c;
var e=0.2;if(f.legend){e=f.legend.rollOverGraphAlpha
}for(c=0;c<b.length;c++){var d=b[c];
if(d!=a){d.changeOpacity(e)
}}},unhighlightGraph:function(){var d=this;
var a=d.graphs;for(var b=0;
b<a.length;b++){var c=a[b];
c.changeOpacity(1)}},showGraph:function(a){var b=this;
a.hidden=false;b.dataChanged=true;
b.initChart(true)},hideGraph:function(a){var b=this;
a.hidden=true;b.dataChanged=true;
b.initChart(true)},hideGraphsBalloon:function(a){var b=this;
a.showBalloon=false;b.updateLegend()
},showGraphsBalloon:function(a){var b=this;
a.showBalloon=true;b.updateLegend()
},updateLegend:function(){var a=this;
if(a.legend){a.legend.invalidateSize()
}},destroySets:function(){var d=this;
AmCharts.AmCoordinateChart.base.destroySets.call(d);
var b=d.graphs;if(b){for(var c=0;
c<b.length;c++){b[c].set=null
}}var a=d.valueAxes;if(a){for(var c=0;
c<a.length;c++){a[c].set=null
}}}});AmCharts.AmRectangularChart=AmCharts.Class({inherits:AmCharts.AmCoordinateChart,construct:function(){var a=this;
AmCharts.AmRectangularChart.base.construct.call(a);
a.createEvents("zoomed");
a.marginLeft=80;a.marginTop=15;
a.marginBottom=35;a.marginRight=15;
a.angle=0;a.depth3D=0;
a.horizontalPosition=0;
a.verticalPosition=0;
a.widthMultiplyer=1;a.heightMultiplyer=1;
a.zoomOutText="Show all";
a.zoomOutButtonSet;a.zoomOutButton={backgroundColor:"#b2e1ff",backgroundAlpha:1}
},initChart:function(){var a=this;
AmCharts.AmRectangularChart.base.initChart.call(a);
a.updateDxy();a.updateMargins();
a.updatePlotArea();a.updateScrollbars();
a.updateChartCursor();
a.updateValueAxes();a.updateGraphs()
},drawChart:function(){var d=this;
AmCharts.AmRectangularChart.base.drawChart.call(d);
d.drawPlotArea();var c=d.chartData;
if(c){if(c.length>0){var a=d.chartCursor;
if(a){a.draw()}var b=d.zoomOutText;
if(b!=""&&b){d.drawZoomOutButton()
}}}},drawZoomOutButton:function(){var f=this;
var k=f.container.set();
var a=f.color;var l=f.fontSize;
var g=f.zoomOutButton;
if(g){if(g.fontSize){l=g.fontSize
}if(g.color){a=g.color
}}var h=AmCharts.text(f.container,29,8,f.zoomOutText,{fill:a,"font-family":f.fontFamily,"font-size":l,"text-anchor":"start"});
var e=h.getBBox();h.translate(0+","+e.height/2);
var c=AmCharts.rect(f.container,e.width+40,e.height+15,[g.backgroundColor],[g.backgroundAlpha]);
var d=f.container.image(f.pathToImages+"lens.png",7,7,16,16);
d.translate(0+","+(e.height/2-6));
d.toFront();h.toFront();
c.hide();f.zoomOutButtonBG=c;
f.lens=d;k.push(c);k.push(d);
k.push(h);f.set.push(k);
var i=k.getBBox();k.translate((f.marginLeftReal+f.plotAreaWidth-i.width)+","+f.marginTopReal);
k.hide();if(f.touchEventsEnabled){k.touchstart(function(){f.rollOverZB()
}).touchend(function(){f.clickZB()
})}k.mouseover(function(){f.rollOverZB()
}).mouseout(function(){f.rollOutZB()
}).click(function(){f.clickZB()
});for(var b=0;b<k.length;
b++){k[b].attr({cursor:"pointer"})
}f.zoomOutButtonSet=k
},rollOverZB:function(){this.zoomOutButtonBG.show()
},rollOutZB:function(){this.zoomOutButtonBG.hide()
},clickZB:function(){this.zoomOut()
},zoomOut:function(){var a=this;
a.updateScrollbar=true;
a.zoom()},drawPlotArea:function(){var f=this;
var n=f.dx;var m=f.dy;
var a=f.marginLeftReal;
var j=f.marginTopReal;
var k=f.plotAreaWidth;
var e=f.plotAreaHeight;
var c=AmCharts.toSvgColor(f.plotAreaFillColors);
var b=f.plotAreaFillAlphas;
if(typeof(b)=="object"){b=b[0]
}var d=AmCharts.rect(f.container,k,e,f.plotAreaFillColors,b,1,f.plotAreaBorderColor,f.plotAreaBorderAlpha);
d.translate(a+","+j);
f.set.push(d);if(n!=0&&m!=0){d.translate(n+","+m);
c=f.plotAreaFillColors;
if(typeof(c)=="object"){c=c[0]
}c=AmCharts.adjustLuminosity(c,-0.15);
var g={fill:c,"fill-opacity":b,stroke:f.plotAreaBorderColor,"stroke-opacity":f.plotAreaBorderAlpha};
var l=f.container.path(["M",0,0,"L",n,m,"L",k+n,m,"L",k,0,"L",0,0,"Z"]).attr(g);
l.translate(a+","+(j+e));
f.set.push(l);var i=f.container.path(["M",0,0,"L",0,e,"L",n,e+m,"L",n,m,"L",0,0,"Z"]).attr(g);
i.translate(a+","+j);
f.set.push(i)}},updatePlotArea:function(){var g=this;
g.realWidth=g.updateWidth();
g.realHeight=g.updateHeight();
var c=g.dx;var b=g.dy;
var d=g.marginLeftReal;
var f=g.marginTopReal;
var a=g.realWidth-d-g.marginRightReal-c;
var e=g.realHeight-f-g.marginBottomReal;
if(a<1){a=1}if(e<1){e=1
}g.plotAreaWidth=Math.round(a);
g.plotAreaHeight=Math.round(e)
},updateDxy:function(){var a=this;
a.dx=a.depth3D*Math.cos(a.angle*Math.PI/180);
a.dy=-a.depth3D*Math.sin(a.angle*Math.PI/180)
},updateMargins:function(){var a=this;
a.marginTopReal=a.marginTop-a.dy;
a.marginBottomReal=a.marginBottom;
a.marginLeftReal=a.marginLeft;
a.marginRightReal=a.marginRight
},updateValueAxes:function(){var d=this;
var a=d.valueAxes;for(var b=0;
b<a.length;b++){var c=a[b];
c.axisRenderer=AmCharts.RectangularAxisRenderer;
c.guideFillRenderer=AmCharts.RectangularAxisGuideFillRenderer;
c.axisItemRenderer=AmCharts.RectangularAxisItemRenderer;
c.dx=d.dx;c.dy=d.dy;c.visibleAxisWidth=d.plotAreaWidth;
c.visibleAxisHeight=d.plotAreaHeight;
c.visibleAxisX=d.marginLeftReal;
c.visibleAxisY=d.marginTopReal;
d.updateObjectSize(c)
}},updateObjectSize:function(a){var b=this;
a.width=b.plotAreaWidth*b.widthMultiplyer;
a.height=b.plotAreaHeight*b.heightMultiplyer;
a.x=b.marginLeftReal+b.horizontalPosition;
a.y=b.marginTopReal+b.verticalPosition
},updateGraphs:function(){var d=this;
var a=d.graphs;for(var b=0;
b<a.length;b++){var c=a[b];
c.x=d.marginLeftReal+d.horizontalPosition;
c.y=d.marginTopReal+d.verticalPosition;
c.width=d.plotAreaWidth*d.widthMultiplyer;
c.height=d.plotAreaHeight*d.heightMultiplyer;
c.index=b;c.dx=d.dx;c.dy=d.dy;
c.rotate=d.rotate;c.chartType=d.chartType
}},updateChartCursor:function(){var b=this;
var a=b.chartCursor;if(a){a.x=b.marginLeftReal;
a.y=b.marginTopReal;a.width=b.plotAreaWidth;
a.height=b.plotAreaHeight;
a.chart=this}},updateScrollbars:function(){},addChartCursor:function(a){var b=this;
AmCharts.callMethod("destroy",[b.chartCursor]);
if(a){b.listenTo(a,"changed",b.handleCursorChange);
b.listenTo(a,"zoomed",b.handleCursorZoom)
}b.chartCursor=a},removeChartCursor:function(){var a=this;
AmCharts.callMethod("destroy",[a.chartCursor]);
a.chartCursor=null},adjustMargins:function(e,b){var d=this;
var a=e.position;var c=e.scrollbarHeight;
if(a=="top"){if(b){d.marginLeftReal+=c
}else{d.marginTopReal+=c
}}else{if(b){d.marginRightReal+=c
}else{d.marginBottomReal+=c
}}},getScrollbarPosition:function(e,a,b){var d=this;
var c;if(a){if(b=="bottom"||b=="left"){c="bottom"
}else{c="top"}}else{if(b=="top"||b=="right"){c="bottom"
}else{c="top"}}e.position=c
},updateChartScrollbar:function(h,c){var e=this;
if(h){h.rotate=c;var d=h.position;
var b=e.marginTopReal;
var f=e.marginLeftReal;
var a=h.scrollbarHeight;
var i=e.dx;var g=e.dy;
if(d=="top"){if(c){h.y=b;
h.x=f-a}else{h.y=b-a+g;
h.x=f+i}}else{if(c){h.y=b+g;
h.x=f+e.plotAreaWidth+i
}else{h.y=b+e.plotAreaHeight+1;
h.x=e.marginLeftReal}}}},showZoomOutButton:function(){var b=this;
var a=b.zoomOutButtonSet;
if(a){a.show();b.zoomOutButtonBG.hide()
}},hideZoomOutButton:function(){var b=this;
var a=b.zoomOutButtonSet;
if(a){a.hide();b.zoomOutButtonBG.hide()
}},destroySets:function(){var b=this;
AmCharts.AmRectangularChart.base.destroySets.call(b);
var a=b.chartCursor;if(a){a.set=null
}},handleReleaseOutside:function(b){var c=this;
AmCharts.AmRectangularChart.base.handleReleaseOutside.call(c,b);
var a=c.chartCursor;if(a){a.handleReleaseOutside()
}},handleMouseDown:function(b){var c=this;
AmCharts.AmRectangularChart.base.handleMouseDown.call(c,b);
var a=c.chartCursor;if(a){a.handleMouseDown(b)
}},handleCursorChange:function(a){}});
AmCharts.AmSerialChart=AmCharts.Class({inherits:AmCharts.AmRectangularChart,construct:function(){var a=this;
AmCharts.AmSerialChart.base.construct.call(a);
a.createEvents("changed");
a.columnSpacing=5;a.columnWidth=0.8;
a.maxSelectedSeries;a.updateScrollbar=true;
a.maxSelectedTime;a.categoryAxis=new AmCharts.CategoryAxis();
a.categoryAxis.chart=this;
a.chartType="serial";
a.zoomOutOnDataUpdate=true
},initChart:function(a){var b=this;
AmCharts.AmSerialChart.base.initChart.call(b);
b.updateCategoryAxis();
if(b.dataChanged){if(b.zoomOutOnDataUpdate&&a!=true){b.start=NaN;
b.startTime=NaN;b.end=NaN;
b.endTime=NaN}b.updateData();
b.dataChanged=false;b.dispatchDataUpdated=true
}b.updateScrollbar=true;
b.drawChart()},drawChart:function(){var g=this;
AmCharts.AmSerialChart.base.drawChart.call(g);
var d=g.chartData;if(d){if(d.length>0){var c=g.chartScrollbar;
if(c){c.draw()}var b=d.length-1;
var f;var a;var e=g.categoryAxis;
if(e.parseDates&&!e.equalSpacing){f=g.startTime;
a=g.endTime;if(isNaN(f)||isNaN(a)){f=d[0].time;
a=d[b].time}}else{f=g.start;
a=g.end;if(isNaN(f)||isNaN(a)){f=0;
a=b}}g.start=undefined;
g.end=undefined;g.startTime=undefined;
g.endTime=undefined;g.zoom(f,a)
}else{g.cleanChart()}}g.bringLabelsToFront();
g.chartCreated=true;g.dispatchDataUpdatedEvent()
},cleanChart:function(){var a=this;
AmCharts.callMethod("destroy",[a.valueAxes,a.graphs,a.categoryAxis,a.chartScrollbar,a.chartCursor])
},updateCategoryAxis:function(){var b=this;
var a=b.categoryAxis;
a.id="categoryAxis";a.rotate=b.rotate;
a.axisRenderer=AmCharts.RectangularAxisRenderer;
a.guideFillRenderer=AmCharts.RectangularAxisGuideFillRenderer;
a.axisItemRenderer=AmCharts.RectangularAxisItemRenderer;
a.setOrientation(!b.rotate);
a.x=b.marginLeftReal;
a.y=b.marginTopReal;a.dx=b.dx;
a.dy=b.dy;a.width=b.plotAreaWidth;
a.height=b.plotAreaHeight;
a.visibleAxisWidth=b.plotAreaWidth;
a.visibleAxisHeight=b.plotAreaHeight;
a.visibleAxisX=b.marginLeftReal;
a.visibleAxisY=b.marginTopReal
},updateValueAxes:function(){var e=this;
AmCharts.AmSerialChart.base.updateValueAxes.call(e);
var a=e.valueAxes;for(var b=0;
b<a.length;b++){var d=a[b];
d.rotate=e.rotate;d.setOrientation(e.rotate);
var c=e.categoryAxis;
if(!c.startOnAxis||c.parseDates){d.expandMinMax=true
}}},updateData:function(){var d=this;
d.parseData();d.columnCount=d.countColumns();
if(d.chartCursor){d.chartCursor.updateData()
}var a=d.graphs;for(var b=0;
b<a.length;b++){var c=a[b];
c.columnCount=d.columnCount;
c.data=d.chartData}},updateMargins:function(){var b=this;
AmCharts.AmSerialChart.base.updateMargins.call(b);
var a=b.chartScrollbar;
if(a){b.getScrollbarPosition(a,b.rotate,b.categoryAxis.position);
b.adjustMargins(a,b.rotate)
}},updateScrollbars:function(){var a=this;
a.updateChartScrollbar(a.chartScrollbar,a.rotate)
},zoom:function(d,a){var c=this;
var b=c.categoryAxis;
if(b.parseDates&&!b.equalSpacing){c.timeZoom(d,a)
}else{c.indexZoom(d,a)
}c.updateDepths()},timeZoom:function(f,k){var l=this;
var e=l.maxSelectedTime;
if(!isNaN(e)){if(k!=l.endTime){if(k-f>e){f=k-e;
l.updateScrollbar=true
}}if(f!=l.startTime){if(k-f>e){k=f+e;
l.updateScrollbar=true
}}}var h=l.chartData;
var i=l.categoryAxis;
if(h){if(h.length>0){if(f!=l.startTime||k!=l.endTime){var d=i.minDuration();
var a=h[0].time;l.firstTime=a;
var b=h[h.length-1].time;
l.lastTime=b;if(!f){f=a;
if(!isNaN(e)){f=b-e}}if(!k){k=b
}if(f>b){f=b}if(k<a){k=a
}if(f<a){f=a}if(k>b){k=b
}if(k<f){k=f+d}l.startTime=f;
l.endTime=k;var j=h.length-1;
var c=l.getClosestIndex(h,"time",f,true,0,j);
var g=l.getClosestIndex(h,"time",k,false,c,j);
i.timeZoom(f,k);i.zoom(c,g);
l.start=AmCharts.fitToBounds(c,0,j);
l.end=AmCharts.fitToBounds(g,0,j);
l.zoomAxesAndGraphs();
l.zoomScrollbar();if(f!=a||k!=b){l.showZoomOutButton()
}else{l.hideZoomOutButton()
}l.dispatchTimeZoomEvent()
}}}},indexZoom:function(e,a){var d=this;
var b=d.maxSelectedSeries;
if(!isNaN(b)){if(a!=d.end){if(a-e>b){e=a-b;
d.updateScrollbar=true
}}if(e!=d.start){if(a-e>b){a=e+b;
d.updateScrollbar=true
}}}if(e!=d.start||a!=d.end){var c=d.chartData.length-1;
if(isNaN(e)){e=0;if(!isNaN(b)){e=c-b
}}if(isNaN(a)){a=c}if(a<e){a=e
}if(a>c){a=c}if(e>c){e=c-1
}if(e<0){e=0}d.start=e;
d.end=a;d.categoryAxis.zoom(e,a);
d.zoomAxesAndGraphs();
d.zoomScrollbar();if(e!=0||a!=d.chartData.length-1){d.showZoomOutButton()
}else{d.hideZoomOutButton()
}d.dispatchIndexZoomEvent()
}},updateGraphs:function(){var d=this;
AmCharts.AmSerialChart.base.updateGraphs.call(d);
var a=d.graphs;for(var b=0;
b<a.length;b++){var c=a[b];
c.columnWidth=d.columnWidth;
c.categoryAxis=d.categoryAxis
}},updateDepths:function(){var l=this;
var g=l.container.rect(0,0,10,10);
l.mostFrontObj=g;l.updateColumnsDepth();
var c=l.graphs;for(var o=0;
o<c.length;o++){var a=c[o];
if(a.type!="column"){a.set.insertBefore(g)
}var q=a.allBullets;if(q){for(var m=0;
m<q.length;m++){var r=q[m];
if(r){r.insertBefore(g)
}}}var b=a.positiveObjectsToClip;
if(b){for(var m=0;m<b.length;
m++){a.setPositiveClipRect(b[m])
}}var e=a.negativeObjectsToClip;
if(e){for(var m=0;m<e.length;
m++){a.setNegativeClipRect(e[m])
}}var n=a.objectsToAddListeners;
if(n){for(var m=0;m<n.length;
m++){a.addClickListeners(n[m]);
if(!l.chartCursor){a.addHoverListeners(n[m])
}}}}var s=l.chartCursor;
if(s){s.set.insertBefore(g)
}var k=l.zoomOutButtonSet;
if(k){k.insertBefore(g)
}var f=l.valueAxes;for(var o=0;
o<f.length;o++){var p=f[o];
p.axisLine.set.toFront();
if(p.grid0){AmCharts.putSetToFront(p.grid0)
}AmCharts.putSetToFront(p.axisLine.set);
var t=p.allLabels;for(var m=0;
m<t.length;m++){var d=t[m];
if(d){d.toFront()}}}var h=l.categoryAxis;
h.axisLine.set.toFront();
var t=h.allLabels;for(var m=0;
m<t.length;m++){var d=h.allLabels[m];
if(d){d.toFront()}}g.remove();
if(l.bgImg){l.bgImg.toBack()
}if(l.background){l.background.toBack()
}l.drb()},updateColumnsDepth:function(){var f=this;
var c;var a=f.graphs;
f.columnsArray=[];for(c=0;
c<a.length;c++){var e=a[c];
var d=e.columnsArray;
if(d){for(var b=0;b<d.length;
b++){f.columnsArray.push(d[b])
}}}f.columnsArray.sort(f.compareDepth);
for(c=0;c<f.columnsArray.length;
c++){f.columnsArray[c].column.set.insertBefore(f.mostFrontObj)
}},compareDepth:function(d,c){if(d.depth>c.depth){return 1
}else{return -1}},zoomScrollbar:function(){var c=this;
var a=c.chartScrollbar;
var b=c.categoryAxis;
if(a){if(c.updateScrollbar){if(b.parseDates&&!b.equalSpacing){a.timeZoom(c.startTime,c.endTime)
}else{a.zoom(c.start,c.end)
}c.updateScrollbar=true
}}},zoomAxesAndGraphs:function(){var g=this;
var c=g.valueAxes;for(var d=0;
d<c.length;d++){var f=c[d];
f.zoom(g.start,g.end)
}var b=g.graphs;for(d=0;
d<b.length;d++){var e=b[d];
e.zoom(g.start,g.end)
}var a=g.chartCursor;
if(a){a.zoom(g.start,g.end,g.startTime,g.endTime)
}},countColumns:function(){var f=this;
var g=0;var l=f.valueAxes.length;
var b=f.graphs.length;
var k;var a;var h=false;
var c;for(var d=0;d<l;
d++){a=f.valueAxes[d];
var e=a.stackType;if(e=="100%"||e=="regular"){h=false;
for(c=0;c<b;c++){k=f.graphs[c];
if(!k.hidden){if(k.valueAxis==a&&k.type=="column"){if(!h&&k.stackable){g++;
h=true}if(!k.stackable){g++
}k.columnIndex=g-1}}}}if(e=="none"||e=="3d"){for(c=0;
c<b;c++){k=f.graphs[c];
if(!k.hidden){if(k.valueAxis==a&&k.type=="column"){k.columnIndex=g;
g++}}}}if(e=="3d"){for(d=0;
d<b;d++){k=f.graphs[d];
k.depthCount=g}g=1}}return g
},parseData:function(){var a=this;
AmCharts.AmSerialChart.base.parseData.call(a);
a.parseSerialData()},getCategoryIndexByValue:function(d){var e=this;
var c=e.chartData;var a;
for(var b=0;b<c.length;
b++){if(c[b].category==d){a=b
}}return a},handleCursorChange:function(a){this.dispatchCursorEvent(a.index)
},handleCursorZoom:function(a){var b=this;
b.updateScrollbar=true;
b.zoom(a.start,a.end)
},handleScrollbarZoom:function(a){var b=this;
b.updateScrollbar=false;
b.zoom(a.start,a.end)
},dispatchTimeZoomEvent:function(){var c=this;
if(c.prevStartTime!=c.startTime||c.prevEndTime!=c.endTime){var a={};
a.type="zoomed";a.startDate=new Date(c.startTime);
a.endDate=new Date(c.endTime);
a.startIndex=c.start;
a.endIndex=c.end;c.startIndex=c.start;
c.endIndex=c.end;c.prevStartTime=c.startTime;
c.prevEndTime=c.endTime;
var b=c.categoryAxis;
a.startValue=AmCharts.formatDate(a.startDate,b.dateFormatsObject[b.minPeriod]);
a.endValue=AmCharts.formatDate(a.endDate,b.dateFormatsObject[b.minPeriod]);
c.fire(a.type,a)}},dispatchIndexZoomEvent:function(){var c=this;
if(c.prevStartIndex!=c.start||c.prevEndIndex!=c.end){c.startIndex=c.start;
c.endIndex=c.end;var a=c.chartData;
if(a){if(a.length>0){if(!isNaN(c.start)&&!isNaN(c.end)){var b={};
b.type="zoomed";b.startIndex=c.start;
b.endIndex=c.end;b.startValue=a[c.start].category;
b.endValue=a[c.end].category;
if(c.categoryAxis.parseDates){c.startTime=a[c.start].time;
c.endTime=a[c.end].time;
b.startDate=new Date(c.startTime);
b.endDate=new Date(c.endTime)
}c.prevStartIndex=c.start;
c.prevEndIndex=c.end;
c.fire(b.type,b)}}}}},dispatchCursorEvent:function(d){var g=this;
var a=g.graphs;for(var e=0;
e<a.length;e++){var f=a[e];
if(isNaN(d)){f.currentDataItem=undefined
}else{var c=g.chartData[d];
var b=c.axes[f.valueAxis.id].graphs[f.id];
f.currentDataItem=b}}if(g.legend){g.legend.updateValues()
}},getClosestIndex:function(j,g,h,c,a,b){var e=this;
if(a<0){a=0}if(b>j.length-1){b=j.length-1
}var f=a+Math.round((b-a)/2);
var k=j[f][g];if(b-a<=1){if(c){return a
}else{var d=j[a][g];var i=j[b][g];
if(Math.abs(d-h)<Math.abs(i-h)){return a
}else{return b}}}if(h==k){return f
}else{if(h<k){return e.getClosestIndex(j,g,h,c,a,f)
}else{return e.getClosestIndex(j,g,h,c,f,b)
}}},zoomToIndexes:function(f,a){var e=this;
e.updateScrollbar=true;
var c=e.chartData;if(c){var b=c.length;
if(b>0){if(f<0){f=0}if(a>b-1){a=b-1
}var d=e.categoryAxis;
if(d.parseDates&&!d.equalSpacing){e.zoom(c[f].time,c[a].time)
}else{e.zoom(f,a)}}}},zoomToDates:function(f,a){var e=this;
e.updateScrollbar=true;
var c=e.chartData;if(e.categoryAxis.equalSpacing){var d=e.getClosestIndex(c,"time",f.getTime(),true,0,c.length);
var b=e.getClosestIndex(c,"time",a.getTime(),false,0,c.length);
e.zoom(d,b)}else{e.zoom(f.getTime(),a.getTime())
}},zoomToCategoryValues:function(c,a){var b=this;
b.updateScrollbar=true;
b.zoom(b.getCategoryIndexByValue(c),b.getCategoryIndexByValue(a))
},destroySets:function(){var a=this;
AmCharts.AmSerialChart.base.destroySets.call(a);
if(a.categoryAxis){a.categoryAxis.set=null
}if(a.chartScrollbar){a.chartScrollbar.set=null
}},addChartScrollbar:function(a){var b=this;
AmCharts.callMethod("destroy",[b.chartScrollbar]);
if(a){a.chart=this;b.listenTo(a,"zoomed",b.handleScrollbarZoom)
}if(b.rotate){if(a.width==undefined){a.width=a.scrollbarHeight
}}else{if(a.height==undefined){a.height=a.scrollbarHeight
}}b.chartScrollbar=a},removeChartScrollbar:function(){var a=this;
AmCharts.callMethod("destroy",[a.chartScrollbar]);
a.chartScrollbar=null
},handleReleaseOutside:function(a){var b=this;
AmCharts.AmSerialChart.base.handleReleaseOutside.call(b,a);
AmCharts.callMethod("handleReleaseOutside",[b.chartScrollbar])
}});AmCharts.AmRadarChart=AmCharts.Class({inherits:AmCharts.AmCoordinateChart,construct:function(){var a=this;
AmCharts.AmRadarChart.base.construct.call(a);
a.chartType="radar";a.radius="35%"
},initChart:function(){var a=this;
AmCharts.AmRadarChart.base.initChart.call(a);
if(a.dataChanged){a.updateData();
a.dataChanged=false;a.dispatchDataUpdated=true
}a.drawChart()},updateData:function(){var d=this;
d.parseData();var a=d.graphs;
for(var b=0;b<a.length;
b++){var c=a[b];c.data=d.chartData
}},updateGraphs:function(){var d=this;
var a=d.graphs;for(var b=0;
b<a.length;b++){var c=a[b];
c.x=d.marginLeftReal+d.horizontalPosition;
c.y=d.marginTopReal+d.verticalPosition;
c.index=b;c.width=d.realRadius;
c.height=d.realRadius;
c.x=d.centerX;c.y=d.centerY;
c.chartType=d.chartType
}},parseData:function(){var a=this;
AmCharts.AmRadarChart.base.parseData.call(a);
a.parseSerialData()},updateValueAxes:function(){var d=this;
var a=d.valueAxes;for(var b=0;
b<a.length;b++){var c=a[b];
c.axisRenderer=AmCharts.RadarAxisRenderer;
c.guideFillRenderer=AmCharts.RadarAxisGuideFillRenderer;
c.axisItemRenderer=AmCharts.RadarAxisItemRenderer;
c.x=d.centerX;c.y=d.centerY;
c.width=d.realRadius;
c.height=d.realRadius
}},drawChart:function(){var e=this;
AmCharts.AmRadarChart.base.drawChart.call(e);
e.realWidth=e.updateWidth();
e.realHeight=e.updateHeight();
e.centerX=e.realWidth/2;
e.centerY=e.realHeight/2;
e.realRadius=AmCharts.toCoordinate(e.radius,e.realWidth,e.realHeight);
e.updateValueAxes();e.updateGraphs();
var d=e.chartData;if(d){if(d.length>0){for(var a=0;
a<e.valueAxes.length;
a++){var c=e.valueAxes[a];
c.zoom(0,d.length-1)}for(var a=0;
a<e.graphs.length;a++){var b=e.graphs[a];
b.zoom(0,d.length-1)}}else{e.cleanChart()
}}e.bringLabelsToFront();
e.chartCreated=true;e.dispatchDataUpdatedEvent();
e.drb()},cleanChart:function(){var a=this;
a.callMethod("destroy",[a.valueAxes,a.graphs])
}});AmCharts.AxisBase=AmCharts.Class({construct:function(){this.dx=0;
this.dy=0;this.axisWidth;
this.axisThickness=1;
this.axisColor="#000000";
this.axisAlpha=1;this.tickLength=5;
this.gridCount=5;this.gridAlpha=0.2;
this.gridThickness=1;
this.gridColor="#000000";
this.dashLength=0;this.labelFrequency=1;
this.showFirstLabel=true;
this.showLastLabel=true;
this.fillColor="#FFFFFF";
this.fillAlpha=0;this.labelsEnabled=true;
this.labelRotation=0;
this.autoGridCount=false;
this.valueRollOverColor="#CC0000";
this.offset=0;this.guides=[];
this.visible=true;this.counter=0;
this.guides=[];this.inside=false
},zoom:function(b,a){this.start=b;
this.end=a;this.dataChanged=true;
this.draw()},draw:function(){this.allLabels=[];
this.counter=0;this.destroy();
this.set=this.chart.container.set();
var d=this.position;if(this.orientation=="horizontal"){if(d=="left"){d="bottom"
}if(d=="right"){d="top"
}}else{if(d=="bottom"){d="left"
}if(d=="top"){d="right"
}}this.position=d;this.axisLine=new this.axisRenderer(this);
var a=this.axisLine.axisWidth;
if(this.autoGridCount){var b;
if(this.orientation=="vertical"){b=this.height/35;
if(b<3){b=3}}else{b=this.width/75
}this.gridCount=b}this.axisWidth=a
},setOrientation:function(a){if(a){this.orientation="horizontal"
}else{this.orientation="vertical"
}},addGuide:function(a){this.guides.push(a)
},removeGuide:function(a){var c=this.guides;
for(var b=0;b<c.length;
b++){if(c[b]==a){c.splice(b,1)
}}},handleGuideOver:function(d){clearTimeout(this.chart.hoverInt);
var b=this.guides[d];
var e=b.graphics.getBBox();
var a=e.x+e.width/2;var f=e.y+e.height/2;
var c=b.fillColor;if(c==undefined){c=b.lineColor
}this.chart.showBalloon(b.balloonText,c,true,a,f)
},handleGuideOut:function(a){this.chart.hideBalloon()
},destroy:function(){AmCharts.removeSet(this.set);
if(this.axisLine){AmCharts.removeSet(this.axisLine.set)
}}});AmCharts.ValueAxis=AmCharts.Class({inherits:AmCharts.AxisBase,construct:function(){var a=this;
a.createEvents("axisChanged","logarithmicAxisFailed","axisSelfZoomed","axisZoomed");
AmCharts.ValueAxis.base.construct.call(this);
a.dataChanged=true;a.gridCount=8;
a.stackType="none";a.position="left";
a.unitPosition="right";
a.integersOnly=false;
a.includeGuidesInMinMax=false;
a.includeHidden=false;
a.recalculateToPercents=false;
a.duration;a.durationUnits={DD:"d. ",hh:":",mm:":",ss:""};
a.scrollbar=false;a.maxDecCount;
a.baseValue=0;a.radarCategoriesEnabled=true;
a.axisTitleOffset=10;
a.gridType="polygons";
a.useScientificNotation=false
},updateData:function(){var a=this;
if(a.gridCount<=0){a.gridCount=1
}a.data=a.chart.chartData;
if(a.chart.chartType!="xy"){a.stackGraphs("smoothedLine");
a.stackGraphs("line");
a.stackGraphs("column");
a.stackGraphs("step")
}if(a.recalculateToPercents){a.recalculate()
}if(a.synchronizationMultiplyer&&a.synchronizeWithAxis){a.foundGraphs=true
}else{a.foundGraphs=false;
a.getMinMax()}},draw:function(){var g=this;
AmCharts.ValueAxis.base.draw.call(g);
var C=g.set;if(g.type=="duration"){g.duration="ss"
}if(g.dataChanged==true){g.updateData();
g.dataChanged=false}if(g.logarithmic){var U=g.getMin(0,g.data.length-1);
if(U<=0||g.minimum<=0){var p="logarithmicAxisFailed";
g.fire(p,{type:p});return
}}g.grid0=null;var T=g.chart;
var w;var R;var h=T.dx;
var e=T.dy;var O=false;
var n=g.logarithmic;var D=T.chartType;
if(!isNaN(g.min)&&!isNaN(g.max)&&g.foundGraphs&&g.min!=Infinity&&g.max!=-Infinity){var G=g.labelFrequency;
var A=g.showFirstLabel;
var F=g.showLastLabel;
var b=1;var K=0;var X=Math.round((g.max-g.min)/g.step)+1;
if(n==true){var j=Math.log(g.max)*Math.LOG10E-Math.log(g.minReal)*Math.LOG10E;
g.stepWidth=g.axisWidth/j;
if(j>2){X=Math.ceil((Math.log(g.max)*Math.LOG10E))+1;
K=Math.round((Math.log(g.minReal)*Math.LOG10E));
if(X>g.gridCount){b=Math.ceil(X/g.gridCount)
}}}else{g.stepWidth=g.axisWidth/(g.max-g.min)
}var P=0;if(g.step<1&&g.step>-1){var t=g.step.toString();
if(t.indexOf("e-")!=-1){P=Number(t.split("-")[1])
}else{P=t.split(".")[1].length
}}if(g.integersOnly){P=0
}if(P>g.maxDecCount){P=g.maxDecCount
}g.max=AmCharts.roundTo(g.max,g.maxDecCount);
g.min=AmCharts.roundTo(g.min,g.maxDecCount);
var k={};k.precision=P;
k.decimalSeparator=T.numberFormatter.decimalSeparator;
k.thousandsSeparator=T.numberFormatter.thousandsSeparator;
g.numberFormatter=k;if(g.guides.length>0){var N=g.guides.length;
var x=g.fillAlpha;g.fillAlpha=0;
for(R=0;R<N;R++){var aa=g.guides[R];
var E=NaN;if(!isNaN(aa.toValue)){E=g.getCoordinate(aa.toValue);
var a=new g.axisItemRenderer(this,E,"",true,NaN,NaN,aa);
C.push(a.graphics())}var Y=NaN;
if(!isNaN(aa.value)){Y=g.getCoordinate(aa.value);
var ab=(E-Y)/2;var a=new g.axisItemRenderer(this,Y,aa.label,true,NaN,ab,aa);
C.push(a.graphics())}if(!isNaN(E-Y)){var B=new g.guideFillRenderer(this,E-Y,Y,aa);
var Q=B.graphics();C.push(Q);
aa.graphics=Q;Q.index=R;
var m=this;if(aa.balloonText){Q.mouseover(function(){m.handleGuideOver(this.index)
});Q.mouseout(function(){m.handleGuideOut(this.index)
})}}}g.fillAlpha=x}var z=false;
var J=Number.MAX_VALUE;
for(R=K;R<X;R+=b){var H=AmCharts.roundTo(g.step*R+g.min,P);
if(String(H).indexOf("e")!=-1){z=true;
var s=String(H).split("e");
var W=Number(s[1])}}if(g.duration){g.maxInterval=AmCharts.getMaxInterval(g.max,g.duration)
}for(R=K;R<X;R+=b){var L=g.step*R+g.min;
L=AmCharts.roundTo(L,g.maxDecCount+1);
if(g.integersOnly&&Math.round(L)!=L){}else{if(n==true){if(L==0){L=g.minReal
}if(j>2){L=Math.pow(10,R)
}if(String(L).indexOf("e")!=-1){z=true
}else{z=false}}var u;
if(g.useScientificNotation){z=true
}if(g.usePrefixes){z=false
}if(!z){if(n){var l=String(L).split(".");
if(l[1]){k.precision=l[1].length
}else{k.precision=-1}}if(g.usePrefixes){u=AmCharts.addPrefix(L,T.prefixesOfBigNumbers,T.prefixesOfSmallNumbers,k)
}else{u=AmCharts.formatNumber(L,k,k.precision)
}}else{if(String(L).indexOf("e")==-1){u=L.toExponential(15)
}else{u=String(L)}var f=u.split("e");
var d=Number(f[0]);var c=Number(f[1]);
if(d==10){d=1;c+=1}u=d+"e"+c;
if(L==0){u="0"}if(L==1){u="1"
}}if(g.duration){u=AmCharts.formatDuration(L,g.duration,"",g.durationUnits,g.maxInterval,k)
}if(g.recalculateToPercents){u=u+"%"
}else{var M=g.unit;if(M){if(g.unitPosition=="left"){u=M+u
}else{u=u+M}}}if(Math.round(R/G)!=R/G){u=undefined
}if((R==0&&!A)||(R==(X-1)&&!F)){u=" "
}w=g.getCoordinate(L);
var a=new g.axisItemRenderer(this,w,u);
C.push(a.graphics());
if(L==g.baseValue&&D!="radar"){var I;
var r;var S=g.visibleAxisWidth;
var V=g.visibleAxisHeight;
var q=g.visibleAxisX;
var o=g.visibleAxisY;
if(g.orientation=="horizontal"){if(w>=q&&w<=q+S+1){I=[w,w,w+h];
r=[0+V,0,e]}}else{if(w>=o&&w<=o+V+1){I=[0,S,S+h];
r=[w,w,w+e]}}if(I){g.grid0=AmCharts.line(T.container,I,r,g.gridColor,g.gridAlpha*2,1,0);
C.push(g.grid0)}}}}var y=g.baseValue;
if(g.min>g.baseValue&&g.max>g.baseValue){y=g.min
}if(g.min<g.baseValue&&g.max<g.baseValue){y=g.max
}if(n){y=g.minReal}g.baseCoord=g.getCoordinate(y);
var v="axisChanged";var Z={type:v};
if(n){Z.min=g.minReal
}else{Z.min=g.min}Z.max=g.max;
g.fire(v,Z);g.axisCreated=true
}else{O=true}if(D!="radar"){if(g.rotate){C.translate(0+","+T.marginTopReal)
}else{C.translate(T.marginLeftReal+","+0)
}}else{g.axisLine.set.toFront()
}if(!g.visible||O){C.hide();
g.axisLine.set.hide()
}else{C.show();g.axisLine.set.show()
}},stackGraphs:function(n){var k=this;
var l=k.stackType;if(l=="stacked"){l="regular"
}if(l=="line"){l="none"
}if(l=="100% stacked"){l="100%"
}k.stackType=l;var g=[];
var b=[];var q=[];var m=[];
var o;var h=k.chart.graphs;
var e;var a;var p;var r;
var d;var f;if((n=="line"||n=="step"||n=="smoothedLine")&&(l=="regular"||l=="100%")){for(d=0;
d<h.length;d++){p=h[d];
if(!p.hidden){a=p.type;
if(p.chart==k.chart&&p.valueAxis==this&&n==a&&p.stackable){if(e){p.stackGraph=e;
e=p}else{e=p}}}}}for(f=k.start;
f<=k.end;f++){for(d=0;
d<h.length;d++){p=h[d];
if(!p.hidden){a=p.type;
if(p.chart==k.chart&&p.valueAxis==this&&n==a&&p.stackable){r=k.data[f].axes[k.id].graphs[p.id];
o=r.values.value;if(!isNaN(o)){if(isNaN(m[f])){m[f]=Math.abs(o)
}else{m[f]+=Math.abs(o)
}if(l=="regular"){if(n=="line"||n=="step"||n=="smoothedLine"){if(isNaN(g[f])){g[f]=o;
r.values.close=o}else{if(isNaN(o)){r.values.close=g[f]
}else{r.values.close=o+g[f]
}g[f]=r.values.close}}if(n=="column"){if(!isNaN(o)){r.values.close=o;
if(o<0){r.values.close=o;
if(!isNaN(b[f])){r.values.close+=b[f];
r.values.open=b[f]}b[f]=r.values.close
}else{r.values.close=o;
if(!isNaN(q[f])){r.values.close+=q[f];
r.values.open=q[f]}q[f]=r.values.close
}}}}}}}}}for(f=k.start;
f<=k.end;f++){for(d=0;
d<h.length;d++){p=h[d];
if(!p.hidden){a=p.type;
if(p.chart==k.chart&&p.valueAxis==this&&n==a&&p.stackable){r=k.data[f].axes[k.id].graphs[p.id];
o=r.values.value;if(!isNaN(o)){var c=o/m[f]*100;
r.values.percents=c;r.values.total=m[f];
if(l=="100%"){if(isNaN(b[f])){b[f]=0
}if(isNaN(q[f])){q[f]=0
}if(c<0){r.values.close=c+b[f];
r.values.open=b[f];b[f]=r.values.close
}else{r.values.close=c+q[f];
r.values.open=q[f];q[f]=r.values.close
}}}}}}}},recalculate:function(){var h=this;
var l=h.chart.graphs;
for(var f=0;f<l.length;
f++){var q=l[f];if(q.valueAxis==this){var p="value";
if(q.type=="candlestick"||q.type=="ohlc"){p="open"
}var a;var r;var e=AmCharts.fitToBounds(h.end+1,0,h.data.length-1);
var b=h.start;if(b>0){b--
}for(var o=h.start;o<=e;
o++){r=h.data[o].axes[h.id].graphs[q.id];
a=r.values[p];if(!isNaN(a)){break
}}for(var g=b;g<=e;g++){r=h.data[g].axes[h.id].graphs[q.id];
r.percents={};var n=r.values;
for(var d in n){if(d!="percents"){var c=n[d];
var m=c/a*100-100;r.percents[d]=m
}else{r.percents[d]=n[d]
}}}}}},getMinMax:function(){var n=this;
var a=false;var k=n.chart;
var h=k.graphs;for(var s=0;
s<h.length;s++){var b=h[s].type;
if(b=="line"||b=="step"||b=="smoothedLine"){if(n.expandMinMax){a=true
}}}if(a){if(n.start>0){n.start--
}if(n.end<n.data.length-1){n.end++
}}if(k.chartType=="serial"){if(k.categoryAxis.parseDates==true&&!a){if(n.end<n.data.length-1){n.end++
}}}n.min=n.getMin(n.start,n.end);
n.max=n.getMax();var r=n.guides.length;
if(n.includeGuidesInMinMax&&r>0){for(var q=0;
q<r;q++){var y=n.guides[q];
if(y.toValue<n.min){n.min=y.toValue
}if(y.value<n.min){n.min=y.value
}if(y.toValue>n.max){n.max=y.toValue
}if(y.value>n.max){n.max=y.value
}}}if(!isNaN(n.minimum)){n.min=n.minimum
}if(!isNaN(n.maximum)){n.max=n.maximum
}if(n.min>n.max){var x=n.max;
n.max=n.min;n.min=x}if(!isNaN(n.minTemp)){n.min=n.minTemp
}if(!isNaN(n.maxTemp)){n.max=n.maxTemp
}n.minReal=n.min;n.maxReal=n.max;
if(n.min==0&&n.max==0){n.max=9
}if(n.min>n.max){n.min=n.max-1
}var l=n.min;var m=n.max;
var w=n.max-n.min;var d;
if(w==0){d=Math.pow(10,Math.floor(Math.log(Math.abs(n.max))*Math.LOG10E))/10
}else{d=Math.pow(10,Math.floor(Math.log(Math.abs(w))*Math.LOG10E))/10
}if(isNaN(n.maximum)&&isNaN(n.maxTemp)){n.max=Math.ceil(n.max/d)*d+d
}if(isNaN(n.minimum)&&isNaN(n.minTemp)){n.min=Math.floor(n.min/d)*d-d
}if(n.min<0&&l>=0){n.min=0
}if(n.max>0&&m<=0){n.max=0
}if(n.stackType=="100%"){if(n.min<0){n.min=-100
}else{n.min=0}if(n.max<0){n.max=0
}else{n.max=100}}w=n.max-n.min;
d=Math.pow(10,Math.floor(Math.log(Math.abs(w))*Math.LOG10E))/10;
n.step=Math.ceil((w/n.gridCount)/d)*d;
var p=Math.pow(10,Math.floor(Math.log(Math.abs(n.step))*Math.LOG10E));
var j=p.toExponential(0);
var v=j.split("e");var e=Number(v[0]);
var u=Number(v[1]);if(e==9){u++
}p=n.generateNumber(1,u);
var t=Math.ceil(n.step/p);
if(t>5){t=10}if(t<=5&&t>2){t=5
}n.step=Math.ceil(n.step/(p*t))*p*t;
if(p<1){n.maxDecCount=Math.abs(Math.log(Math.abs(p))*Math.LOG10E);
n.maxDecCount=Math.round(n.maxDecCount);
n.step=AmCharts.roundTo(n.step,n.maxDecCount+1)
}else{n.maxDecCount=0
}n.min=n.step*Math.floor(n.min/n.step);
n.max=n.step*Math.ceil(n.max/n.step);
if(n.min<0&&l>=0){n.min=0
}if(n.max>0&&m<=0){n.max=0
}if(n.minReal>1&&n.max-n.minReal>1){n.minReal=Math.floor(n.minReal)
}w=(Math.pow(10,Math.floor(Math.log(Math.abs(n.minReal))*Math.LOG10E)));
if(n.min==0){n.minReal=w
}if(n.min==0&&n.minReal>1){n.minReal=1
}if(n.min>0&&n.minReal-n.step>0){if(n.min+n.step<n.minReal){n.minReal=n.min+n.step
}else{n.minReal=n.min
}}var o=Math.log(m)*Math.LOG10E-Math.log(l)*Math.LOG10E;
if(n.logarithmic){if(o>2){n.min=Math.pow(10,Math.floor(Math.log(Math.abs(l))*Math.LOG10E));
n.minReal=n.min;n.max=Math.pow(10,Math.ceil(Math.log(Math.abs(m))*Math.LOG10E))
}else{var f=Math.pow(10,Math.floor(Math.log(Math.abs(n.min))*Math.LOG10E))/10;
var c=Math.pow(10,Math.floor(Math.log(Math.abs(l))*Math.LOG10E))/10;
if(f<c){n.min=10*c;n.minReal=n.min
}}}},generateNumber:function(a,c){var d="";
var e;if(c<0){e=Math.abs(c)-1
}else{e=Math.abs(c)}for(var b=0;
b<e;b++){d=d+"0"}if(c<0){return Number("0."+d+String(a))
}else{return Number(String(a)+d)
}},getMin:function(a,d){var h=this;
var e;for(var g=a;g<=d;
g++){var l=h.data[g].axes[h.id].graphs;
for(var f in l){var n=h.chart.getGraphById(f);
if(n.includeInMinMax){if(!n.hidden||h.includeHidden){if(isNaN(e)){e=Infinity
}h.foundGraphs=true;var m=l[f].values;
if(h.recalculateToPercents){m=l[f].percents
}var b;if(h.minMaxField){b=m[h.minMaxField];
if(b<e){e=b}}else{for(var c in m){if(c!="percents"&&c!="total"){b=m[c];
if(b<e){e=b}}}}}}}}return e
},getMax:function(){var e=this;
var g;for(var d=e.start;
d<=e.end;d++){var f=e.data[d].axes[e.id].graphs;
for(var c in f){var l=e.chart.getGraphById(c);
if(l.includeInMinMax){if(!l.hidden||e.includeHidden){if(isNaN(g)){g=-Infinity
}e.foundGraphs=true;var h=f[c].values;
if(e.recalculateToPercents){h=f[c].percents
}var a;if(e.minMaxField){a=h[e.minMaxField];
if(a>g){g=a}}else{for(var b in h){if(b!="percents"&&b!="total"){a=h[b];
if(a>g){g=a}}}}}}}}return g
},dispatchZoomEvent:function(b,a){var c={type:"axisZoomed",startValue:b,endValue:a};
this.fire(c.type,c)},zoomToValues:function(c,a){var e=this;
if(a<c){var b=a;a=c;c=b
}if(c<e.min){c=e.min}if(a>e.max){a=e.max
}var d={};d.type="axisSelfZoomed";
d.valueAxis=e;d.multiplyer=e.axisWidth/Math.abs((e.getCoordinate(a)-e.getCoordinate(c)));
if(e.orientation=="vertical"){if(e.reversed){d.position=e.getCoordinate(c)-e.y
}else{d.position=e.getCoordinate(a)-e.y
}}else{if(e.reversed){d.position=e.getCoordinate(a)-e.x
}else{d.position=e.getCoordinate(c)-e.x
}}e.fire(d.type,d)},coordinateToValue:function(d){var c=this;
if(isNaN(d)){return NaN
}var a;if(c.logarithmic==true){var b;
if(c.rotate){if(c.reversed==true){b=(c.axisWidth-d)/c.stepWidth
}else{b=d/c.stepWidth
}}else{if(c.reversed==true){b=d/c.stepWidth
}else{b=(c.axisWidth-d)/c.stepWidth
}}a=Math.pow(10,b+Math.log(c.minReal)*Math.LOG10E)
}else{if(c.reversed==true){if(c.rotate){a=c.min-(d-c.axisWidth)/c.stepWidth
}else{a=d/c.stepWidth+c.min
}}else{if(c.rotate){a=d/c.stepWidth+c.min
}else{a=c.min-(d-c.axisWidth)/c.stepWidth
}}}return a},getCoordinate:function(c){var h=this;
if(isNaN(c)){return NaN
}var a=h.rotate;var f=h.reversed;
var g;var b=h.axisWidth;
var e=h.stepWidth;if(h.logarithmic==true){var d=(Math.log(c)*Math.LOG10E)-Math.log(h.minReal)*Math.LOG10E;
if(a){if(f==true){g=b-e*d
}else{g=e*d}}else{if(f==true){g=e*d
}else{g=b-e*d}}}else{if(f==true){if(a){g=b-e*(c-h.min)
}else{g=e*(c-h.min)}}else{if(a){g=e*(c-h.min)
}else{g=b-e*(c-h.min)
}}}g=Math.round(g*10)/10;
if(h.rotate){g+=h.x}else{g+=h.y
}return g},synchronizeWithAxis:function(a){var b=this;
b.synchronizeWithAxis=a;
b.removeListener(b.synchronizeWithAxis,"axisChanged",b.handleSynchronization);
b.listenTo(b.synchronizeWithAxis,"axisChanged",b.handleSynchronization)
},handleSynchronization:function(a){var d=this;
var c=d.synchronizeWithAxis;
var g=c.min;var i=c.max;
var f=c.step;var b=d.synchronizationMultiplyer;
if(b){d.min=g*b;d.max=i*b;
d.step=f*b;var h=Math.pow(10,Math.floor(Math.log(Math.abs(d.step))*Math.LOG10E));
var e=Math.abs(Math.log(Math.abs(h))*Math.LOG10E);
e=Math.round(e);d.maxDecCount=e;
d.draw()}}});AmCharts.CategoryAxis=AmCharts.Class({inherits:AmCharts.AxisBase,construct:function(){var a=this;
AmCharts.CategoryAxis.base.construct.call(a);
a.minPeriod="DD";a.parseDates=false;
a.equalSpacing=false;
a.position="bottom";a.startOnAxis=false;
a.gridPosition="middle";
a.periods=[{period:"ss",count:1},{period:"ss",count:5},{period:"ss",count:10},{period:"ss",count:30},{period:"mm",count:1},{period:"mm",count:5},{period:"mm",count:10},{period:"mm",count:30},{period:"hh",count:1},{period:"hh",count:3},{period:"hh",count:6},{period:"hh",count:12},{period:"DD",count:1},{period:"WW",count:1},{period:"MM",count:1},{period:"MM",count:2},{period:"MM",count:3},{period:"MM",count:6},{period:"YYYY",count:1},{period:"YYYY",count:2},{period:"YYYY",count:5},{period:"YYYY",count:10},{period:"YYYY",count:50},{period:"YYYY",count:100}];
a.dateFormats=[{period:"fff",format:"JJ:NN:SS"},{period:"ss",format:"JJ:NN:SS"},{period:"mm",format:"JJ:NN"},{period:"hh",format:"JJ:NN"},{period:"DD",format:"MMM DD"},{period:"MM",format:"MMM"},{period:"YYYY",format:"YYYY"}];
a.nextPeriod={};a.nextPeriod.fff="ss";
a.nextPeriod.ss="mm";
a.nextPeriod.mm="hh";
a.nextPeriod.hh="DD";
a.nextPeriod.DD="MM";
a.nextPeriod.MM="YYYY"
},draw:function(){var d=this;
AmCharts.CategoryAxis.base.draw.call(d);
d.generateDFObject();
var U=d.chart.chartData;
d.data=U;if(U.length>0){var s=d.end;
var B=d.start;var H=d.labelFrequency;
var K=0;var l=s-B+1;var j=d.gridCount;
var x=d.showFirstLabel;
var E=d.showLastLabel;
var t;var n="";var o=AmCharts.extractPeriod(d.minPeriod);
var k=AmCharts.getPeriodDuration(o.period,o.count);
var G;var S;var D;var u;
var J;var y;var p;var f;
var B;var R;var P;var r;
var A;var b;var T=d.rotate;
var Z=U[U.length-1].time;
var g=AmCharts.resetDateToMin(new Date(Z+k),d.minPeriod,1).getTime();
if(d.endTime>g){d.endTime=g
}if(d.parseDates&&!d.equalSpacing){d.timeDifference=d.endTime-d.startTime;
G=d.choosePeriod(0);D=G.period;
S=G.count;u=AmCharts.getPeriodDuration(D,S);
if(u<k){D=o.period;S=o.count;
u=k}J=D;if(J=="WW"){J="DD"
}d.stepWidth=d.getStepWidth(d.timeDifference);
j=Math.ceil(d.timeDifference/u)+1;
y=AmCharts.resetDateToMin(new Date(d.startTime-u),D,S).getTime();
if(J==D&&S==1){p=u*d.stepWidth
}d.cellWidth=k*d.stepWidth;
f=Math.round(y/u);B=-1;
if(f/2==Math.round(f/2)){B=-2;
y-=u}if(d.gridCount>0){for(R=B;
R<=j;R++){P=y+u*1.5;P=AmCharts.resetDateToMin(new Date(P),D,S).getTime();
t=(P-d.startTime)*d.stepWidth;
if(T){t+=d.y}else{t+=d.x
}r=false;if(d.nextPeriod[J]){r=d.checkPeriodChange(d.nextPeriod[J],1,P,y)
}var c=false;if(r){A=d.dateFormatsObject[d.nextPeriod[J]];
c=true}else{A=d.dateFormatsObject[J]
}n=AmCharts.formatDate(new Date(P),A);
if((R==B&&!x)||(R==j&&!E)){n=" "
}var a=new d.axisItemRenderer(this,t,n,false,p,0,false,c);
d.set.push(a.graphics());
y=P}}}else{if(!d.parseDates){d.cellWidth=d.getStepWidth(l);
if(l<j){j=l}K+=d.start;
d.stepWidth=d.getStepWidth(l);
if(j>0){var L=Math.floor(l/j);
b=K;if(b/2==Math.round(b/2)){b--
}if(b<0){b=0}for(R=b;
R<=d.end+2;R+=L){if(R>=0&&R<d.data.length){var q=d.data[R];
n=q.category}else{n=""
}t=d.getCoordinate(R-K);
var h=0;if(d.gridPosition=="start"){t=t-d.cellWidth/2;
h=d.cellWidth/2}if((R==B&&!x)||(R==d.end&&!E)){n=" "
}if(Math.round(R/H)!=R/H){n=undefined
}var N=d.cellWidth;if(T){N=NaN
}var a=new d.axisItemRenderer(this,t,n,true,N,h,undefined,false,h);
d.set.push(a.graphics())
}}}else{if(d.parseDates&&d.equalSpacing){K=d.start;
d.startTime=d.data[d.start].time;
d.endTime=d.data[d.end].time;
d.timeDifference=d.endTime-d.startTime;
G=d.choosePeriod(0);D=G.period;
S=G.count;u=AmCharts.getPeriodDuration(D,S);
if(u<k){D=o.period;S=o.count;
u=k}J=D;if(J=="WW"){J="DD"
}d.stepWidth=d.getStepWidth(l);
j=Math.ceil(d.timeDifference/u)+1;
y=AmCharts.resetDateToMin(new Date(d.startTime-u),D,S).getTime();
d.cellWidth=d.getStepWidth(l);
f=Math.round(y/u);B=-1;
if(f/2==Math.round(f/2)){B=-2;
y-=u}var e=d.data.length;
b=d.start;if(b/2==Math.round(b/2)){b--
}if(b<0){b=0}var m=d.end+2;
if(m>=d.data.length){m=d.data.length
}var F=false;if(d.end-d.start>d.gridCount){F=true
}for(R=b;R<m;R++){P=d.data[R].time;
if(d.checkPeriodChange(D,S,P,y)){t=d.getCoordinate(R-d.start);
r=false;if(d.nextPeriod[J]){r=d.checkPeriodChange(d.nextPeriod[J],1,P,y)
}var c=false;if(r){A=d.dateFormatsObject[d.nextPeriod[J]];
c=true}else{A=d.dateFormatsObject[J]
}n=AmCharts.formatDate(new Date(P),A);
if((R==B&&!x)||(R==j&&!E)){n=" "
}if(!F){var a=new d.axisItemRenderer(this,t,n,undefined,undefined,undefined,undefined,c);
d.set.push(a.graphics())
}else{F=false}y=P}}}}}for(R=0;
R<d.data.length;R++){var v=d.data[R];
if(v){var M;if(d.parseDates&&!d.equalSpacing){var I=v.time;
M=(I-d.startTime)*d.stepWidth+d.cellWidth/2;
if(T){M+=d.y}else{M+=d.x
}}else{M=d.getCoordinate(R-K)
}v.x[d.id]=M}}}var O=d.guides.length;
for(R=0;R<O;R++){var X=d.guides[R];
var C=NaN;var V=NaN;var Y=NaN;
if(X.toCategory){var w=d.chart.getCategoryIndexByValue(X.toCategory);
if(!isNaN(w)){C=d.getCoordinate(w-K);
var a=new d.axisItemRenderer(this,C,"",true,NaN,NaN,X);
d.set.push(a.graphics())
}}if(X.category){var W=d.chart.getCategoryIndexByValue(X.category);
if(!isNaN(W)){V=d.getCoordinate(W-K);
Y=(C-V)/2;var a=new d.axisItemRenderer(this,V,X.label,true,NaN,Y,X)
}}if(X.toDate){if(d.equalSpacing){var w=d.chart.getClosestIndex(d.data,"time",X.toDate.getTime(),false,0,d.data.length-1);
if(!isNaN(w)){C=d.getCoordinate(w-K)
}}else{C=(X.toDate.getTime()-d.startTime)*d.stepWidth;
if(T){C+=d.y}else{C+=d.x
}}var a=new d.axisItemRenderer(this,C,"",true,NaN,NaN,X);
d.set.push(a.graphics())
}if(X.date){if(d.equalSpacing){var W=d.chart.getClosestIndex(d.data,"time",X.date.getTime(),false,0,d.data.length-1);
if(!isNaN(W)){V=d.getCoordinate(W-K)
}}else{V=(X.date.getTime()-d.startTime)*d.stepWidth;
if(T){V+=d.y}else{V+=d.x
}}Y=(C-V)/2;if(d.orientation=="horizontal"){var a=new d.axisItemRenderer(this,V,X.label,false,Y*2,NaN,X)
}else{var a=new d.axisItemRenderer(this,V,X.label,false,NaN,Y,X)
}}d.set.push(a.graphics());
var z=new d.guideFillRenderer(this,C-V,V,X);
var Q=z.graphics();d.set.push(Q);
X.graphics=Q;Q.index=R;
if(X.balloonText){Q.mouseover(function(){d.handleGuideOver(this.index)
});Q.mouseout(function(){d.handleGuideOut(this.index)
})}}d.axisCreated=true;
if(T){d.set.translate(d.x+","+0)
}else{d.set.translate(0+","+d.y)
}if(d.axisLine.set){d.axisLine.set.toFront()
}},choosePeriod:function(b){var d=this;
var a=AmCharts.getPeriodDuration(d.periods[b].period,d.periods[b].count);
var c=Math.ceil(d.timeDifference/a);
if(c<=d.gridCount){return d.periods[b]
}else{if(b+1<d.periods.length){return d.choosePeriod(b+1)
}else{return d.periods[b]
}}},getStepWidth:function(a){var c=this;
var b;if(c.startOnAxis){b=c.axisWidth/(a-1);
if(a==1){b=c.axisWidth
}}else{b=c.axisWidth/a
}return b},getCoordinate:function(a){var c=this;
var b=a*c.stepWidth;if(!c.startOnAxis){b+=c.stepWidth/2
}if(c.rotate){b+=c.y}else{b+=c.x
}return b},timeZoom:function(b,a){var c=this;
c.startTime=b;c.endTime=a+c.minDuration()
},minDuration:function(){var b=this;
var a=AmCharts.extractPeriod(b.minPeriod);
return AmCharts.getPeriodDuration(a.period,a.count)
},checkPeriodChange:function(h,d,f,b){var a=new Date(f);
var g=new Date(b);var e=AmCharts.resetDateToMin(a,h,d).getTime();
var c=AmCharts.resetDateToMin(g,h,d).getTime();
if(e!=c){return true}else{return false
}},generateDFObject:function(){var c=this;
c.dateFormatsObject={};
for(var a=0;a<c.dateFormats.length;
a++){var b=c.dateFormats[a];
c.dateFormatsObject[b.period]=b.format
}},xToIndex:function(g){var d=this;
var c=d.data;var f=d.chart;
var b=f.rotate;var i=d.stepWidth;
if(b){g=g-d.y}else{g=g-d.x
}var e;if(d.parseDates&&!d.equalSpacing){var a=d.startTime+Math.round(g/i)-d.minDuration()/2;
e=f.getClosestIndex(c,"time",a,false,d.start,d.end+1)
}else{if(!d.startOnAxis){g-=i/2
}e=d.start+Math.round(g/i)
}e=AmCharts.fitToBounds(e,0,c.length-1);
var h;if(c[e]){h=c[e].x[d.id]
}if(b){if(h>d.height+1+d.y){e--
}if(h<d.y){e++}}else{if(h>d.width+1+d.x){e--
}if(h<d.x){e++}}e=AmCharts.fitToBounds(e,0,c.length-1);
return e},dateToCoordinate:function(b){var c=this;
if(c.parseDates&&!c.equalSpacing){return(b.getTime()-c.startTime)*c.stepWidth
}else{if(c.parseDates&&c.equalSpacing){var a=c.chart.getClosestIndex(c.data,"time",b.getTime(),false,0,c.data.length-1);
return c.getCoordinate(a-_start)
}else{return NaN}}},categoryToCoordinate:function(b){var c=this;
if(c.chart){var a=c.chart.getCategoryIndexByValue(b);
return getCoordinate(a-c.start)
}else{return NaN}},coordinateToDate:function(b){var a=this;
return new Date(a.startTime+b/a.stepWidth)
}});AmCharts.RectangularAxisRenderer=AmCharts.Class({construct:function(b){var u=this;
var m=b.chart;var k=b.axisThickness;
var v=b.axisColor;var w=b.axisAlpha;
var s=b.tickLength;var r=b.offset;
var h=b.dx;var g=b.dy;
var p=b.visibleAxisX;
var n=b.visibleAxisY;
var z=b.visibleAxisHeight;
var q=b.visibleAxisWidth;
var e;var d;var i=m.container;
var j=i.set();u.set=j;
var f;if(b.orientation=="horizontal"){f=AmCharts.line(i,[0,q],[0,0],v,w,k);
u.axisWidth=b.width;if(b.position=="bottom"){d=k/2+r+z+n-1;
e=p}else{d=-k/2-r+n+g;
e=h+p}}else{u.axisWidth=b.height;
if(b.position=="right"){f=AmCharts.line(i,[0,0,-h],[0,z,z-g],v,w,k);
d=n+g;e=k/2+r+h+q+p-1
}else{f=AmCharts.line(i,[0,0],[0,z],v,w,k);
d=n;e=-k/2-r+p}}j.push(f);
j.translate(Math.round(e)+","+Math.round(d))
}});AmCharts.RectangularAxisItemRenderer=AmCharts.Class({construct:function(B,v,T,K,r,aj,af,e,z){var h=this;
if(T==undefined){T=""
}if(!z){z=0}if(K==undefined){K=true
}var a=B.chart.fontFamily;
var H=B.fontSize;if(H==undefined){H=B.chart.fontSize
}var G=B.color;if(G==undefined){G=B.chart.color
}var J=B.chart.container;
var F=J.set();h.set=F;
var M=3;var Z=4;var j=B.axisThickness;
var I=B.axisColor;var ab=B.axisAlpha;
var L=B.tickLength;var t=B.gridAlpha;
var ac=B.gridThickness;
var ad=B.gridColor;var n=B.dashLength;
var ag=B.fillColor;var u=B.fillAlpha;
var W=B.labelsEnabled;
var U=B.labelRotation;
var f=B.counter;var l=B.inside;
var i=B.dx;var g=B.dy;
var C=B.orientation;var Q=B.position;
var N=B.previousCoord;
var aa=B.chart.rotate;
var k=B.autoTruncate;
var p=B.visibleAxisX;
var o=B.visibleAxisY;
var y=B.visibleAxisHeight;
var q=B.visibleAxisWidth;
var P=B.offset;var d=B.x;
var c=B.y;var b;var S;
if(af){W=true;if(!isNaN(af.tickLength)){L=af.tickLength
}if(af.lineColor!=undefined){ad=af.lineColor
}if(!isNaN(af.lineAlpha)){t=af.lineAlpha
}if(!isNaN(af.dashLength)){n=af.dashLength
}if(!isNaN(af.lineThickness)){ac=af.lineThickness
}if(af.inside==true){l=true
}}else{if(!T){t=t/3;L=L/2
}}var V="start";if(r){V="middle"
}var w=U*Math.PI/180;
var A;var m;var E=0;var D=0;
var Y=0;var X=0;var O=0;
var R=0;var ai=(p+i)+","+(o+g)+","+q+","+y;
if(C=="vertical"){U=0
}if(W){var ah=AmCharts.text(J,0,0,T,{fill:G,"text-anchor":V,"font-family":a,"font-size":H,rotation:-U});
if(e==true){ah.attr({"font-weight":"bold"})
}F.push(ah);var x=ah.getBBox();
O=x.width;R=x.height}if(C=="horizontal"){if(v>=p&&v<=q+1+p){b=AmCharts.line(J,[v+z,v+z],[0,L],I,ab,ac);
F.push(b);if(v+z>q+p+1){b.remove()
}S=AmCharts.line(J,[v,v+i,v+i],[y,y+g,g],ad,t,ac,n);
F.push(S)}D=0;E=v;if(K==false){V="start";
if(!aa){if(Q=="bottom"){if(l){D+=L
}else{D-=L}}else{if(l){D-=L
}else{D+=L}}E+=3;if(r){E+=r/2;
V="middle"}}}else{V="middle"
}if(f==1&&u>0&&!af){A=v-N;
fill=AmCharts.rect(J,A,B.height,[ag],[u]);
fill.translate((v-A+i)+","+g);
fill.attr({"clip-rect":ai});
F.push(fill)}if(Q=="bottom"){D+=y+H/2+P;
if(l){D-=L+H+M+M;if(U>0){E+=(O/2)*Math.cos(w);
D-=(O/2)*Math.sin(w)-(R/2)*Math.sin(w)
}}else{D+=L+j+M+3;if(U>0){E-=(O/2)*Math.cos(w);
D+=(O/2)*Math.sin(w)-(R/2)*Math.cos(w);
if(U==90){D-=8;if(AmCharts.isNN){E+=1
}else{if(AmCharts.IEversion<9){E+=3
}else{D-=O*0.16-4}}}}}}else{D+=g+H/2-P;
E+=i;if(l){D+=L+M;if(U>0){E-=(O/2)*Math.cos(w);
D+=(O/2)*Math.sin(w)-((R/2))*Math.sin(w)+3
}}else{D-=L+H+M+j+3;if(U>0){E+=(O/2)*Math.cos(w);
D-=(O/2)*Math.sin(w)-((R/2))*Math.sin(w)+3
}}}if(Q=="bottom"){if(l){X=y-L-1
}else{X=y+j-1}X+=P}else{Y=i;
if(l){X=g}else{X=g-L-j+1
}X-=P}if(aj){E+=aj}var ae=E;
if(U>0){ae+=(O/2)*Math.cos(w)
}if(ah){if(ae>p+q+1||ae<p){ah.remove();
ah=null}}}else{if(v>=o&&v<=y+1+o){b=AmCharts.line(J,[0,L],[v+z,v+z],I,ab,ac);
F.push(b);if(v+z>y+o+1){b.remove()
}S=AmCharts.line(J,[0,i,q+i],[v,v+g,v+g],ad,t,ac,n);
F.push(S)}V="end";if((l==true&&Q=="left")||(l==false&&Q=="right")){V="start"
}D=v-H/2;if(f==1&&u>0&&!af){m=v-N;
fill=AmCharts.rect(J,B.width,m,[ag],[u]);
fill.translate(i+","+(v-m+g));
fill.attr({"clip-rect":ai});
F.push(fill)}D+=H/2;if(Q=="right"){E+=i+q+P;
D+=g;if(l==true){E-=L+Z;
if(!aj){D-=H/2+3}}else{E+=L+Z+j;
D-=2}}else{if(l==true){E+=L+Z-P;
if(!aj){D-=H/2+3}if(af){E+=i;
D+=g}}else{E+=-L-j-Z-2-P;
D-=2}}if(b){if(Q=="right"){Y+=i+P+q;
X+=g;if(l==true){Y-=j
}else{Y+=j}}else{Y-=P;
if(l==true){}else{Y-=L+j
}}}if(aj){D+=aj}var s=o-3;
if(Q=="right"){s+=g}if(ah){if(D>y+o+1||D<s){ah.remove();
ah=null}}}if(b){b.translate(Y+","+X)
}if(B.visible==false){if(b){b.remove()
}if(ah){ah.remove();ah=null
}}if(ah){ah.attr({"text-anchor":V});
ah.translate(E+","+D);
B.allLabels.push(ah)}if(f==0){B.counter=1
}else{B.counter=0}B.previousCoord=v
},graphics:function(){return this.set
}});AmCharts.RectangularAxisGuideFillRenderer=AmCharts.Class({construct:function(g,k,j,f){var i=this;
var d=g.orientation;var e=0;
var c=f.fillAlpha;var a=g.chart.container;
var n=g.dx;var m=g.dy;
if(isNaN(k)){k=4;e=2;
c=0}var b=f.fillColor;
if(b==undefined){b="#000000"
}if(k<0){if(typeof(b)=="object"){b=b.join(",").split(",").reverse()
}}if(isNaN(c)){c=0}var h=(g.visibleAxisX+n)+","+(g.visibleAxisY+m)+","+g.visibleAxisWidth+","+g.visibleAxisHeight;
var l;if(d=="vertical"){l=AmCharts.rect(a,g.width,k,b,c);
l.translate(n+","+(j-e+m))
}else{l=AmCharts.rect(a,k,g.height,b,c);
l.translate((j-e+n)+","+m)
}l.attr({"clip-rect":h});
i.fill=l},graphics:function(){return this.fill
}});AmCharts.RadarAxisRenderer=AmCharts.Class({construct:function(d){var u=this;
var p=d.chart;var o=d.axisThickness;
var D=d.axisColor;var E=d.axisAlpha;
var s=d.tickLength;var m=d.x;
var h=d.y;u.set=p.container.set();
var k=d.labelsEnabled;
var F=d.axisTitleOffset;
var C=d.radarCategoriesEnabled;
var B=d.chart.fontFamily;
var q=d.fontSize;if(q==undefined){q=d.chart.fontSize
}var v=d.color;if(v==undefined){v=d.chart.color
}if(p){u.axisWidth=d.height;
var G=p.chartData;var f=G.length;
for(var z=0;z<f;z++){var A=180-360/f*z;
var e=m+u.axisWidth*Math.sin((A)/(180)*Math.PI);
var r=h+u.axisWidth*Math.cos((A)/(180)*Math.PI);
var n=AmCharts.line(p.container,[m,e],[h,r],D,E,o);
u.set.push(n);if(C){var w="start";
var j=m+(u.axisWidth+F)*Math.sin((A)/(180)*Math.PI);
var g=h+(u.axisWidth+F)*Math.cos((A)/(180)*Math.PI);
if(A==180||A==0){w="middle";
j=j-5}if(A<0){w="end";
j=j-10}if(A==180){g-=5
}if(A==0){g+=5}var H=AmCharts.text(p.container,j+5,g,G[z].category,{fill:v,"text-anchor":w,"font-family":B,"font-size":q});
u.set.push(H);var b=H.getBBox()
}}}}});AmCharts.RadarAxisItemRenderer=AmCharts.Class({construct:function(q,o,I,A,k,Y,V){var e=this;
if(I==undefined){I=""
}var a=q.chart.fontFamily;
var u=q.fontSize;if(u==undefined){u=q.chart.fontSize
}var t=q.color;if(t==undefined){t=q.chart.color
}var z=q.chart.container;
e.set=z.set();var C=3;
var O=4;var f=q.axisThickness;
var w=q.axisColor;var R=q.axisAlpha;
var B=q.tickLength;var l=q.gridAlpha;
var T=q.gridThickness;
var U=q.gridColor;var h=q.dashLength;
var W=q.fillColor;var m=q.fillAlpha;
var N=q.labelsEnabled;
var L=q.labelRotation;
var d=q.counter;var g=q.inside;
var G=q.position;var D=q.previousCoord;
var c=q.gridType;o-=q.height;
var b;var H;var F=q.x;
var E=q.y;var s=0;var r=0;
if(V){N=true;if(!isNaN(V.tickLength)){B=V.tickLength
}if(V.lineColor!=undefined){U=V.lineColor
}if(!isNaN(V.lineAlpha)){l=V.lineAlpha
}if(!isNaN(V.dashLength)){h=V.dashLength
}if(!isNaN(V.lineThickness)){T=V.lineThickness
}if(V.inside==true){g=true
}}else{if(!I){l=l/3;B=B/2
}}var M="end";var S=-1;
if(g){M="start";S=1}if(N){var X=AmCharts.text(z,F+(B+3)*S,o,I,{fill:t,"text-anchor":M,"font-family":a,"font-size":u});
e.set.push(X);var b=AmCharts.line(z,[F,F+B*S],[o,o],w,R,T);
e.set.push(b)}var J=q.y-o;
if(c=="polygons"){var v=[];
var j=[];var K=q.data.length;
for(var P=0;P<K;P++){var p=180-360/K*P;
v.push(J*Math.sin((p)/(180)*Math.PI));
j.push(J*Math.cos((p)/(180)*Math.PI))
}v.push(v[0]);j.push(j[0]);
H=AmCharts.line(z,v,j,U,l,T,h)
}else{H=AmCharts.circle(z,J,0,0,T,U,l)
}e.set.push(H);H.translate(F+","+E);
if(d==1&&m>0&&!V){var Q=q.previousCoord;
var n;if(c=="polygons"){for(P=K;
P>=0;P--){p=180-360/K*P;
v.push(Q*Math.sin((p)/(180)*Math.PI));
j.push(Q*Math.cos((p)/(180)*Math.PI))
}n=AmCharts.polygon(z,v,j,[W],[m])
}else{n=AmCharts.wedge(z,0,0,0,-360,J,J,Q,0,{fill:W,"fill-opacity":m,stroke:0,"stroke-opacity":0,"stroke-width":0})
}e.set.push(n);n.translate(F+","+E)
}if(q.visible==false){if(b){b.hide()
}if(X){X.hide()}}if(d==0){q.counter=1
}else{q.counter=0}q.previousCoord=J
},graphics:function(){return this.set
}});AmCharts.RadarAxisGuideFillRenderer=AmCharts.Class({construct:function(g,q,p,f){var m=this;
var c=g.chart.container;
var e=f.fillAlpha;var d=f.fillColor;
var l=g.y-(p-g.height)-q;
var o=l+q;var h=-f.angle;
var b=-f.toAngle;if(isNaN(h)){h=0
}if(isNaN(b)){b=-360}m.set=c.set();
if(d==undefined){d="#000000"
}if(isNaN(e)){e=0}if(g.gridType=="polygons"){var a=[];
var n=[];var k=g.data.length;
for(var j=0;j<k;j++){var h=180-360/k*j;
a.push(l*Math.sin((h)/(180)*Math.PI));
n.push(l*Math.cos((h)/(180)*Math.PI))
}a.push(a[0]);n.push(n[0]);
for(j=k;j>=0;j--){h=180-360/k*j;
a.push(o*Math.sin((h)/(180)*Math.PI));
n.push(o*Math.cos((h)/(180)*Math.PI))
}m.fill=AmCharts.polygon(c,a,n,[d],[e])
}else{var o=l-Math.abs(q);
m.fill=AmCharts.wedge(c,0,0,h,(b-h),l,l,o,0,{fill:d,"fill-opacity":e,stroke:0,"stroke-opacity":0,"stroke-width":0})
}m.set.push(m.fill);m.fill.translate(g.x+","+g.y)
},graphics:function(){return this.fill
}});AmCharts.AmGraph=AmCharts.Class({construct:function(){var a=this;
a.createEvents("rollOverGraphItem","rollOutGraphItem","clickGraphItem","doubleClickGraphItem");
a.type="line";a.stackable=true;
a.columnCount=1;a.columnIndex=0;
a.showBalloon=true;a.centerCustomBullets=true;
a.maxBulletSize=50;a.balloonText="[[value]]";
a.animationPlayed=false;
a.scrollbar=false;a.hidden=false;
a.columnWidth=0.8;a.pointPosition="middle";
a.depthCount=1;a.includeInMinMax=true;
a.negativeBase=0;a.visibleInLegend=true;
a.showAllValueLabels=false;
a.showBalloonAt="close";
a.lineThickness=1;a.dashLength=0;
a.connect=true;a.lineAlpha=1;
a.bullet="none";a.bulletBorderThickness=2;
a.bulletBorderAlpha=1;
a.bulletAlpha=1;a.bulletSize=8;
a.bulletOffset=0;a.hideBulletsCount=0;
a.labelPosition="top";
a.cornerRadiusTop=0;a.cursorBulletAlpha=1;
a.gradientOrientation="vertical";
a.dx=0;a.dy=0},draw:function(){var b=this;
b.container=b.chart.container;
b.destroy();b.set=b.container.set();
b.ownColumns=[];b.allBullets=[];
b.objectsToAddListeners=[];
if(b.data){if(b.data.length>0){var a=false;
if(b.chartType=="xy"){if(b.xAxis.axisCreated&&b.yAxis.axisCreated){a=true
}}else{if(b.valueAxis.axisCreated){b.columnsArray=[];
a=true}}if(!b.hidden&&a){b.createGraph()
}}}},createGraph:function(){var a=this;
if(a.labelPosition=="inside"){a.labelPosition="bottom"
}a.sDur=a.chart.startDuration;
a.sEff=a.chart.startEffect;
a.startAlpha=a.chart.startAlpha;
a.seqAn=a.chart.sequencedAnimation;
a.baseCoord=a.valueAxis.baseCoord;
if(!a.fillColors){a.fillColors=[a.lineColor]
}if(a.fillAlphas==undefined){a.fillAlphas=0
}if(a.bulletColor==undefined){a.bulletColor=a.lineColor;
a.bulletColorNegative=a.negativeLineColor
}if(a.bulletAlpha==undefined){a.bulletAlpha=a.lineAlpha
}if(!a.bulletBorderColor){a.bulletBorderAlpha=0
}if(!isNaN(a.valueAxis.min)&&!isNaN(a.valueAxis.max)){a.positiveObjectsToClip=[];
a.negativeObjectsToClip=[];
a.animationArray=[];switch(a.chartType){case"serial":a.createSerialGraph();
break;case"radar":a.createRadarGraph();
break;case"xy":a.createXYGraph();
break}a.animationPlayed=true
}},createXYGraph:function(){var g=this;
var f=g.labelPosition;
var a=[];var j=[];g.pmh=g.yAxis.visibleAxisHeight+1;
g.pmw=g.xAxis.visibleAxisWidth+1;
g.pmx=g.yAxis.visibleAxisX;
g.pmy=g.yAxis.visibleAxisY;
for(var e=g.start;e<=g.end;
e++){var h=g.data[e];
var o=h.axes[g.xAxis.id].graphs[g.id];
var d=o.values.x;var k=o.values.y;
var l=o.values.value;
var b=g.xAxis.getCoordinate(d);
var n=g.yAxis.getCoordinate(k);
if(!isNaN(d)&&!isNaN(k)){a.push(b);
j.push(n);var c=g.createBullet(o,b,n,e);
if(!c){c=0}if(g.labelText){var m=g.createLabel(o,b,n);
g.positionLabel(m,g.labelPosition,c)
}}}g.drawLineGraph(a,j);
g.launchAnimation()},createRadarGraph:function(){var p=this;
var f=p.valueAxis.stackType;
var b=[];var k=[];var e;
var d;for(var r=p.start;
r<=p.end;r++){var u=p.data[r];
var s=u.axes[p.valueAxis.id].graphs[p.id];
var h;if(f=="none"||f=="3d"){h=s.values.value
}else{h=s.values.close
}if(isNaN(h)){p.drawLineGraph(b,k);
b=[];k=[]}else{var n=p.y-(p.valueAxis.getCoordinate(h)-p.height);
var t=180-360/(p.end-p.start+1)*r;
var m=(n*Math.sin((t)/(180)*Math.PI));
var g=(n*Math.cos((t)/(180)*Math.PI));
b.push(m);k.push(g);var a=p.createBullet(s,m,g,r);
if(!a){a=0}if(p.labelText){var c=p.createLabel(s,m,g);
p.positionLabel(c,p.labelPosition,a)
}if(isNaN(e)){e=m}if(isNaN(d)){d=g
}}}b.push(e);k.push(d);
p.drawLineGraph(b,k);
p.set.translate(p.x+","+p.y);
p.launchAnimation();var l=p.objectsToAddListeners;
if(l){for(var q=0;q<l.length;
q++){p.addHoverListeners(l[q])
}}},positionLabel:function(c,g,b){var f=this;
var a=0;var e=0;var d=c.getBBox();
switch(g){case"left":a=-((d.width+b)/2+5);
break;case"top":e=-((b+d.height)/2+3);
break;case"right":a=(d.width+b)/2+5;
break;case"bottom":e=(b+d.height)/2+3;
break}c.translate(a+","+e)
},createSerialGraph:function(){var F=this;
var am=F.id;var bd=F.index;
var K=F.data;var aT=F.chart.container;
var a2=F.valueAxis;var au=F.type;
var aC=F.columnWidth;
var a0=F.width;var D=F.height;
var X=F.x;var W=F.y;var ar=F.rotate;
var a7=F.columnCount;
var aZ=AmCharts.toCoordinate(F.cornerRadiusTop,aC/2);
var bg=F.connect;var ba=[];
var aU=[];var be;var h;
var aK=F.chart.graphs.length;
var bb;var p=F.dx/F.depthCount;
var n=F.dy/F.depthCount;
var aV=a2.stackType;var ae=F.labelPosition;
var C=F.start;var ap=F.end;
var g=F.scrollbar;var q=F.categoryAxis;
var t=F.baseCoord;var U=F.negativeBase;
var aN=F.columnIndex;
var ay=F.lineThickness;
var Z=F.lineAlpha;var bo=F.lineColor;
var aD=F.dashLength;var ad=F.set;
if(ae=="above"){ae="top"
}if(ae=="below"){ae="bottom"
}var aM;var z=270;if(F.gradientOrientation=="horizontal"){z=0
}var aQ=F.chart.columnSpacing;
var bn=q.cellWidth;var af=(bn*aC-a7)/a7;
if(aQ>af){aQ=af}var aA;
var aO;var ac;var L=D+1;
var v=a0+1;var u=X;var r=W;
var T;var H;var G;var E;
var f=F.fillColors;var aF=F.negativeFillColors;
var av=F.negativeLineColor;
var ah=F.fillAlphas;var bc=F.negativeFillAlphas;
if(typeof(ah)=="object"){ah=ah[0]
}if(typeof(bc)=="object"){bc=bc[0]
}var e=a2.getCoordinate(a2.min);
if(a2.logarithmic){e=a2.getCoordinate(a2.minReal)
}F.minCoord=e;if(F.resetBullet){F.bullet="none"
}if(!g&&(au=="line"||au=="smoothedLine"||au=="step")){if(K.length==1&&au!="step"&&F.bullet=="none"){F.bullet="round";
F.resetBullet=true}if(aF||av!=undefined){var aX=U;
if(aX>a2.max){aX=a2.max
}if(aX<a2.min){aX=a2.min
}if(a2.logarithmic){aX=a2.minReal
}var k=a2.getCoordinate(aX);
var a=a2.getCoordinate(a2.max);
if(ar){L=D;v=Math.abs(a-k);
T=D;H=Math.abs(e-k);G=W;
E=W;if(a2.reversed){u=X;
G=k}else{u=k;G=X}}else{v=a0;
L=Math.abs(a-k);H=a0;
T=Math.abs(e-k);u=X;G=X;
if(a2.reversed){E=W;r=k
}else{E=k}}}}F.pmx=u;
F.pmy=r;F.pmh=L;F.pmw=v;
F.nmx=G;F.nmy=E;F.nmh=T;
F.nmw=H;if(au=="column"){aC=(bn*aC-(aQ*(a7-1)))/a7
}else{aC=bn*aC}if(aC<1){aC=1
}var ao;if(au=="line"||au=="step"||au=="smoothedLine"){if(C>0){for(ao=C-1;
ao>-1;ao--){aA=K[ao];
aO=aA.axes[a2.id].graphs[am];
ac=aO.values.value;if(ac){C=ao;
break}}}if(ap<K.length-1){for(ao=ap+1;
ao<K.length;ao++){aA=K[ao];
aO=aA.axes[a2.id].graphs[am];
ac=aO.values.value;if(ac){ap=ao;
break}}}}if(ap<K.length-1){ap++
}for(ao=C;ao<=ap;ao++){aA=K[ao];
aO=aA.axes[a2.id].graphs[am];
aO.index=ao;var Q="";
if(aO.url){Q="pointer"
}var aL;var aW;var m=NaN;
var N=NaN;var ag=NaN;
var bj=NaN;var aY=NaN;
var l=NaN;var o=NaN;var aP=NaN;
var at=NaN;var bl=NaN;
var bi=NaN;var aS=NaN;
var aR=NaN;var aq=NaN;
var a9=undefined;var aI=f;
var aH=ah;var b=bo;if(aO.color!=undefined){aI=[aO.color]
}if(aO.fillColors){aI=aO.fillColors
}if(!isNaN(aO.alpha)){aH=[aO.alpha]
}var O=aO.values;if(a2.recalculateToPercents){O=aO.percents
}if(!F.stackable||aV=="none"||aV=="3d"){aq=O.value
}else{aq=O.close}if(au=="candlestick"||au=="ohlc"){aq=O.close;
var bk=O.low;o=a2.getCoordinate(bk);
var s=O.high;at=a2.getCoordinate(s)
}var aj=O.open;ag=a2.getCoordinate(aq);
if(!isNaN(aj)){aY=a2.getCoordinate(aj)
}if(!g){switch(F.showBalloonAt){case"close":aO.y=ag;
break;case"open":aO.y=aY;
break;case"high":aO.y=at;
break;case"low":aO.y=o;
break}}m=aA.x[q.id];var al=bn/2;
var ak=bn/2;if(F.pointPosition=="start"){m-=bn/2;
al=0;ak=bn}if(!g){aO.x=m
}if(ar){N=ag;bj=aY;ag=m;
aY=m;if(isNaN(aj)){bj=t
}l=o;aP=at}else{N=m;bj=m;
if(isNaN(aj)){aY=t}}switch(au){case"line":if(!isNaN(aq)){if(aq<U){aO.isNegative=true
}ba.push(N);aU.push(ag);
bl=N;bi=ag;aS=N;aR=ag
}else{if(!bg){F.drawLineGraph(ba,aU);
ba=[];aU=[]}}break;case"smoothedLine":if(!isNaN(aq)){if(aq<U){aO.isNegative=true
}ba.push(N);aU.push(ag);
bl=N;bi=ag;aS=N;aR=ag
}else{if(!bg){F.drawSmoothedGraph(ba,aU);
ba=[];aU=[]}}break;case"step":if(!isNaN(aq)){if(aq<U){aO.isNegative=true
}if(ar){if(be&&bg){ba.push(be);
aU.push(ag-al)}aU.push(ag-al);
ba.push(N);aU.push(ag+ak);
ba.push(N)}else{if(h&&bg){aU.push(h);
ba.push(N-al)}ba.push(N-al);
aU.push(ag);ba.push(N+ak);
aU.push(ag)}be=N;h=ag;
bl=N;bi=ag;aS=N;aR=ag
}else{if(!bg){F.drawLineGraph(ba,aU);
ba=[];aU=[]}}break;case"column":var a9;
if(!isNaN(aq)){if(aq<U){aO.isNegative=true;
if(aF){aI=aF}if(av!=undefined){b=av
}}var bh=a2.min;var B=a2.max;
if((aq<bh&&(aj<bh||aj==undefined))||(aq>B&&aj>B)){}else{if(ar){if(aV=="3d"){var P=ag-0.5*(aC+aQ)+aQ/2+n*aN;
var R=bj+p*aN}else{var P=ag-(a7/2-aN)*(aC+aQ)+aQ/2;
var R=bj}var S=aC;bl=N;
bi=P+aC/2;aS=N;aR=P+aC/2;
if(P+S>W+D){S=W+D-P}if(P<W){S-=W-P;
P=W}var ab=N-bj;var a1=R;
R=AmCharts.fitToBounds(R,X,X+a0);
ab=ab+(a1-R);ab=AmCharts.fitToBounds(ab,X-R,X+a0-R);
if(P<W+D&&S>0){a9=new AmCharts.Cuboid(aT,ab,S,p,n,aI,ah,ay,b,Z,z,aZ);
a9.y(P);a9.x(R);if(ae!="bottom"){ae="right";
if(aq<0){ae="left"}else{bl+=F.dx;
if(aV!="regular"&&aV!="100%"){bi+=F.dy
}}}}}else{ae="top";if(aV=="3d"){var R=N-0.5*(aC+aQ)+aQ/2+p*aN;
var P=aY+n*aN}else{var R=N-(a7/2-aN)*(aC+aQ)+aQ/2;
var P=aY}var S=aC;bl=R+aC/2;
bi=ag;aS=R+aC/2;aR=ag;
if(R+S>X+a0+aN*p){S=X+a0-R+aN*p
}if(R<X){S-=X-R;R=X}var ab=ag-aY;
var aJ=P;P=AmCharts.fitToBounds(P,W,W+D);
ab=ab+(aJ-P);ab=AmCharts.fitToBounds(ab,W-P,W+D-P);
if(R<X+a0+aN*p&&S>0){a9=new AmCharts.Cuboid(aT,S,ab,p,n,aI,ah,ay,b,F.lineAlpha,z,aZ);
a9.y(P);a9.x(R);if(aq<0){ae="bottom"
}else{if(aV!="regular"&&aV!="100%"){bl+=F.dx
}bi+=F.dy}}}}if(a9){if(!g){if(aV=="none"){if(ar){bb=(F.end+1-ao)*aK-bd
}else{bb=aK*ao+bd}}if(aV=="3d"){bb=(aK-bd)*(ao+1);
if(ar){bi=P+aC/2;bi+=n*F.columnIndex
}else{bl+=p*F.columnIndex;
bi+=n*F.columnIndex}}if(aV=="regular"||aV=="100%"){ae="middle";
if(ar){if(O.value>0){bb=(F.end+1-ao)*aK+bd
}else{bb=(F.end+1-ao)*aK-bd
}}else{if(O.value>0){bb=(aK*ao)+bd
}else{bb=aK*ao-bd}}}F.columnsArray.push({column:a9,depth:bb});
if(ar){aO.x=a9.getY()+S/2
}else{aO.x=a9.getX()+S/2
}F.ownColumns.push(a9);
if(F.dx==0&&F.dy==0){if(F.sDur>0&&!F.animationPlayed){var aL;
var aB;var d;if(ar){aL=N-bj;
aB=N;d=bj}else{aL=ag-aY;
aB=ag;d=aY}if(F.seqAn){a9.set.hide();
F.animationArray.push({obj:a9.set,fh:aL,ip:d,fp:aB});
aM=setTimeout(function(){F.animate.call(F)
},F.sDur/(F.end-F.start+1)*(ao-F.start)*1000);
F.timeOuts.push(aM)}else{F.animate(a9.set,aL,d,aB)
}}}var bm=a9.set;for(var an=0;
an<bm.length;an++){bm[an].dItem=aO;
bm[an].attr({cursor:Q})
}F.objectsToAddListeners.push(a9.set)
}ad.push(a9.set);aO.columnSprite=bm
}}break;case"candlestick":if(!isNaN(aj)&&!isNaN(s)&&!isNaN(bk)&&!isNaN(aq)){var ax;
var a8;if(aq<aj){aO.isNegative=true;
if(aF){aI=aF}if(bc){aH=bc
}if(av!=undefined){b=av
}}if(ar){var P=ag-aC/2;
var R=bj;var S=aC;if(P+S>W+D){S=W+D-P
}if(P<W){S-=W-P;P=W}if(P<W+D&&S>0){var ai;
var a6;if(aq>aj){ai=[N,aP];
a6=[bj,l]}else{ai=[bj,aP];
a6=[N,l]}if(ag<W+D&&ag>W){ax=AmCharts.line(aT,ai,[ag,ag],b,Z,ay);
a8=AmCharts.line(aT,a6,[ag,ag],b,Z,ay)
}if(Math.abs(N-bj)<1){a9=new AmCharts.line(aT,[0,0],[0,S],b,Z,1);
a9.translate(R+","+P)
}else{a9=new AmCharts.Cuboid(aT,N-bj,S,p,n,aI,ah,ay,b,Z,z,aZ);
a9.y(P);a9.x(R)}}}else{var R=N-aC/2;
var P=aY+ay/2;var S=aC;
if(R+S>X+a0){S=X+a0-R
}if(R<X){S-=X-R;R=X}if(R<X+a0&&S>0){if(Math.abs(ag-aY)<1){a9=new AmCharts.line(aT,[0,S],[0,0],b,Z,1);
a9.translate(R+","+P)
}else{a9=new AmCharts.Cuboid(aT,S,ag-aY,p,n,aI,aH,ay,b,Z,z,aZ);
a9.x(R);a9.y(P)}var bf;
var V;if(aq>aj){bf=[ag,at];
V=[aY,o]}else{bf=[aY,at];
V=[ag,o]}if(N<X+a0&&N>X){ax=AmCharts.line(aT,[N,N],bf,b,Z,ay);
a8=AmCharts.line(aT,[N,N],V,b,Z,ay)
}}}if(a9){if(a9.set){ad.push(a9.set)
}else{ad.push(a9)}if(ax){ad.push(ax);
ad.push(a8)}bl=N;bi=ag;
aS=N;aR=ag;if(!g){if(a9.getX){var az=a9.getX();
var aw=a9.getY()}else{var az=R;
var aw=P}if(ar){aO.x=aw+S/2
}else{aO.x=az+S/2}if(F.dx==0&&F.dy==0){if(F.sDur>0&&!F.animationPlayed){if(ar){aL=N-bj;
aB=N;d=bj}else{aL=ag-aY;
aB=ag;d=aY}if(F.seqAn){a9.set.show();
F.animationArray.push({obj:a9.set,fh:aL,ip:d,fp:aB});
aM=setTimeout(function(){F.animate.call(F)
},F.sDur/(F.end-F.start+1)*(ao-F.start)*1000);
F.timeOuts.push(aM)}else{F.animate(a9.set,aL,d,aB)
}}}if(a9.set){var bm=a9.set;
for(var an=0;an<bm.length;
an++){bm[an].dItem=aO;
bm[an].attr({cursor:Q})
}F.objectsToAddListeners.push(a9.set)
}}}}break;case"ohlc":if(!isNaN(aj)&&!isNaN(s)&&!isNaN(bk)&&!isNaN(aq)){if(aq<aj){aO.isNegative=true;
if(av!=undefined){b=av
}}var J;var w;var I;if(ar){w=AmCharts.line(aT,[bj,bj],[ag-aC/2,ag],b,Z,ay,aD);
J=AmCharts.line(aT,[l,aP],[ag,ag],b,Z,ay,aD);
I=AmCharts.line(aT,[N,N],[ag,ag+aC/2],b,Z,ay,aD)
}else{w=AmCharts.line(aT,[N-aC/2,N],[aY,aY],b,Z,ay,aD);
J=AmCharts.line(aT,[N,N],[o,at],b,Z,ay,aD);
I=AmCharts.line(aT,[N,N+aC/2],[ag,ag],b,Z,ay,aD)
}ad.push(w);ad.push(J);
ad.push(I);bl=N;bi=ag;
aS=N;aR=ag}break}if(!g&&!isNaN(aq)){var aG=F.hideBulletsCount;
if(F.end-F.start<=aG||aG==0){var A=F.createBullet(aO,aS,aR,ao);
if(!A){A=0}if(F.labelText){var aE=F.createLabel(aO,bl,bi);
if(au=="column"){if(ar){if(ae=="right"||ae=="bottom"){aE.attr({width:a0})
}else{aE.attr({width:N-bj})
}}else{aE.attr({width:bn})
}}var a5=0;var a3=0;var aa=NaN;
var Y=NaN;var a4=aE.getBBox();
var c=a4.width;var M=a4.height;
switch(ae){case"left":a5=-(c/2+A/2+3);
break;case"top":a3=-(M/2+A/2+3);
break;case"right":a5=A/2+2+c/2;
break;case"bottom":if(ar&&au=="column"){if(aq<0){aa=bj-c/2-7
}else{aa=bj+6+c/2}}else{a3=A/2+M/2;
aE.x=-(c/2+2)}break;case"middle":if(au=="column"){if(ar){aa=(N-bj)/2+bj;
if(Math.abs(N-bj)<c){if(!F.showAllValueLabels){aE.remove()
}}}else{Y=(ag-aY)/2+aY+1;
if(Math.abs(ag-aY)<M){if(!F.showAllValueLabels){aE.remove()
}}}}break}if(!isNaN(aa)){aE.attr({x:aa})
}if(!isNaN(Y)){aE.attr({y:Y})
}aE.translate(a5+","+a3);
a4=aE.getBBox();if(a4.x<X||a4.y<W||a4.x+a4.width>X+a0||a4.y+a4.height>W+D){aE.remove()
}}}}}if(au=="line"||au=="step"||au=="smoothedLine"){if(au=="smoothedLine"){F.drawSmoothedGraph(ba,aU)
}else{F.drawLineGraph(ba,aU)
}if(!g){F.launchAnimation()
}}},createLabel:function(i,f,e){var c=this;
var d=c.chart;var a=c.numberFormatter;
if(!a){a=d.numberFormatter
}var b=c.color;if(b==undefined){b=d.color
}var j=c.fontSize;if(j==undefined){j=d.fontSize
}var h=d.formatString(c.labelText,i,this);
var g=AmCharts.text(c.container,f,e,h,{fill:b,"font-family":d.fontFamily,"font-size":j});
c.set.push(g);c.allBullets.push(g);
return g},setPositiveClipRect:function(a){var b=this;
a.attr({"clip-rect":b.pmx+","+b.pmy+","+b.pmw+","+b.pmh})
},setNegativeClipRect:function(a){var b=this;
a.attr({"clip-rect":b.nmx+","+b.nmy+","+b.nmw+","+b.nmh})
},drawLineGraph:function(a,f){var e=this;
if(a.length>1){var k=AmCharts.line(e.container,a,f,e.lineColor,e.lineAlpha,e.lineThickness,e.dashLength);
e.positiveObjectsToClip.push(k);
e.set.push(k);if(e.negativeLineColor!=undefined){var d=AmCharts.line(e.container,a,f,e.negativeLineColor,e.lineAlpha,e.lineThickness,e.dashLength);
e.negativeObjectsToClip.push(d);
e.set.push(d)}if(e.fillAlphas!=undefined&&e.fillAlphas!=0){var b=a.join(";").split(";");
var i=f.join(";").split(";");
if(e.chartType=="serial"){if(e.rotate){i.push(i[i.length-1]);
b.push(e.baseCoord);i.push(i[0]);
b.push(e.baseCoord);i.push(i[0]);
b.push(b[0])}else{b.push(b[b.length-1]);
i.push(e.baseCoord);b.push(b[0]);
i.push(e.baseCoord);b.push(a[0]);
i.push(i[0])}}var j=AmCharts.polygon(e.container,b,i,e.fillColors,e.fillAlphas);
e.set.push(j);e.positiveObjectsToClip.push(j);
if(e.negativeFillColors||e.negativeLineColor!=undefined){var h=e.fillAlphas;
if(e.negativeFillAlphas){h=e.negativeFillAlphas
}var g=e.negativeLineColor;
if(e.negativeFillColors){g=e.negativeFillColors
}var c=AmCharts.polygon(e.container,b,i,g,h);
e.set.push(c);e.negativeObjectsToClip.push(c)
}}}},drawSmoothedGraph:function(a,e){var d=this;
if(a.length>1){var j=new AmCharts.Bezier(d.container,a,e,d.lineColor,d.lineAlpha,d.lineThickness,NaN,NaN,d.dashLength);
d.positiveObjectsToClip.push(j.path);
d.set.push(j.path);if(d.negativeLineColor!=undefined){var c=new AmCharts.Bezier(d.container,a,e,d.negativeLineColor,d.lineAlpha,d.lineThickness,NaN,NaN,d.dashLength);
d.set.push(c.path);d.negativeObjectsToClip.push(c.path)
}if(d.fillAlphas>0){var f=[];
if(d.rotate){f.push("L",d.baseCoord,e[e.length-1]);
f.push("L",d.baseCoord,e[0]);
f.push("L",a[0],e[0])
}else{f.push("L",a[a.length-1],d.baseCoord);
f.push("L",a[0],d.baseCoord);
f.push("L",a[0],e[0])
}var i=new AmCharts.Bezier(d.container,a,e,NaN,NaN,0,d.fillColors,d.fillAlphas,d.dashLength,f);
d.positiveObjectsToClip.push(i.path);
d.set.push(i.path);if(d.negativeFillColors||d.negativeLineColor!=undefined){var h=d.fillAlphas;
if(d.negativeFillAlphas){h=d.negativeFillAlphas
}var g=d.negativeLineColor;
if(d.negativeFillColors){g=d.negativeFillColors
}var b=new AmCharts.Bezier(d.container,a,e,NaN,NaN,0,g,h,d.dashLength,f);
d.negativeObjectsToClip.push(b.path);
d.set.push(b.path)}}}},launchAnimation:function(){var c=this;
if(c.sDur>0&&!c.animationPlayed){var b=c.set;
b.attr({opacity:c.startAlpha});
if(c.rotate){b.translate((-1000)+","+0)
}else{b.translate(0+","+(-1000))
}if(c.seqAn){var a=setTimeout(function(){c.animateGraphs.call(c)
},c.index*c.sDur*1000);
c.timeOuts.push(a)}else{c.animateGraphs()
}}},animateGraphs:function(){var a=this;
if(a.set.length>0){if(a.rotate){a.set.animate({opacity:1,translation:(1000+","+0)},a.sDur*1000,a.sEff)
}else{a.set.animate({opacity:1,translation:(0+","+1000)},a.sDur*1000,a.sEff)
}}},animate:function(d,a,e,b){var f=this;
var c=f.animationArray;
if(!d&&c.length>0){d=c[0].obj;
a=c[0].fh;e=c[0].ip;b=c[0].fp;
c.shift()}d.show();if(f.rotate){if(a>0){d.attr({"fill-opacity":f.startAlpha,width:1});
d.animate({"fill-opacity":f.fillAlphas,width:Math.abs(a)},f.sDur*1000,f.sEff)
}else{if(a<0){d.attr({"fill-opacity":f.startAlpha,width:1,x:e});
d.animate({"fill-opacity":f.fillAlphas,width:Math.abs(a),x:b},f.sDur*1000,f.sEff)
}}}else{if(a>0){d.attr({"fill-opacity":f.startAlpha,height:0.1});
d.animate({"fill-opacity":f.fillAlphas,height:Math.abs(a)},f.sDur*1000,f.sEff)
}else{if(a<0){d.attr({"fill-opacity":f.startAlpha,height:0.1,y:e});
d.animate({"fill-opacity":f.fillAlphas,height:Math.abs(a),y:b},f.sDur*1000,f.sEff)
}}}},legendKeyColor:function(){var d=this;
var a=d.legendColor;var b=d.lineAlpha;
if(a==undefined){a=d.lineColor;
if(b==0){var c=d.fillColors;
if(c){if(typeof(c)=="object"){a=c[0]
}else{a=c}}}}return a
},legendKeyAlpha:function(){var b=this;
var a=b.legendAlpha;if(a==undefined){a=b.lineAlpha;
if(a==0){if(b.fillAlphas){a=b.fillAlphas
}}}return a},createBullet:function(q,p,o,f){var n=this;
var i="";if(q.url){i="pointer"
}var k=n.bulletOffset;
var b=n.bulletSize;if(!isNaN(q.bulletSize)){b=q.bulletSize
}if(!isNaN(n.maxValue)){var m=q.values.value;
if(!isNaN(m)){b=m/n.maxValue*n.maxBulletSize
}}var r;if(n.bullet=="none"&&!q.bullet){}else{var g=n.bulletColor;
if(q.isNegative&&n.bulletColorNegative!=undefined){g=n.bulletColorNegative
}if(q.color!=undefined){g=q.color
}var l=n.bullet;if(q.bullet){l=q.bullet
}var c=n.bulletBorderThickness;
var h=n.bulletBorderColor;
var j=n.bulletBorderAlpha;
var d=g;var e=n.bulletAlpha;
switch(l){case"round":r=AmCharts.circle(n.container,b/2,d,e,c,h,j);
break;case"square":r=AmCharts.rect(n.container,b,b,d,e,c,h,j);
r.translate(-b/2+","+(-b/2));
break;case"triangleUp":r=AmCharts.triangle(n.container,b,0,d,e,c,h,j);
break;case"triangleDown":r=AmCharts.triangle(n.container,b,180,d,e,c,h,j);
break;case"triangleLeft":r=AmCharts.triangle(n.container,b,270,d,e,c,h,j);
break;case"triangleRight":r=AmCharts.triangle(n.container,b,90,d,e,c,h,j);
break;case"bubble":r=AmCharts.circle(n.container,b/2,d,e,c,h,j,true);
break}if(r){r.translate(p+","+o)
}}if(n.customBullet||q.customBullet){var a=n.customBullet;
if(q.customBullet){a=q.customBullet
}if(a){if(n.chart.path){a=n.chart.path+a
}r=n.container.image(a,p,o,b,b).attr({preserveAspectRatio:true});
if(n.centerCustomBullets){r.translate(-b/2+","+(-b/2))
}}}if(r){r.attr({cursor:i});
if(n.rotate){r.translate(k+","+0)
}else{r.translate(0+","+(-k))
}n.allBullets.push(r);
n.set.push(r);if(n.chartType=="serial"){if(p<n.x||p>n.x+n.width||o<n.y||o>n.y+n.height){r.remove();
r=null}}if(r){r.dItem=q;
n.objectsToAddListeners.push(r)
}}return b},showBullets:function(){var b=this;
for(var a=0;a<b.allBullets.length;
a++){b.allBullets[a].show()
}},hideBullets:function(){var b=this;
for(var a=0;a<b.allBullets.length;
a++){b.allBullets[a].hide()
}},addHoverListeners:function(a){var b=this;
a.mouseover(function(){b.handleRollOver.call(b,this.dItem)
}).mouseout(function(){b.handleRollOut.call(b,this.dItem)
})},addClickListeners:function(a){var b=this;
if(b.chart.touchEventsEnabled){a.touchstart(function(){b.handleRollOver(this.dItem)
}).touchend(function(){b.handleClick(this.dItem)
})}a.click(function(){b.handleClick.call(b,this.dItem)
}).dblclick(function(){b.handleDoubleClick.call(b,this.dItem)
})},handleRollOver:function(f){var g=this;
if(f){var c=g.chart;var b="rollOverGraphItem";
var d={type:b,item:f,index:f.index,graph:this};
g.fire(b,d);c.fire(b,d);
clearTimeout(c.hoverInt);
if(c.chartCursor&&g.chartType=="serial"){}else{var e=c.formatString(g.balloonText,f,this);
var a=c.getBalloonColor(this,f);
c.balloon.showBullet=false;
c.balloon.pointerOrientation="vertical";
c.showBalloon(e,a,true)
}}},handleRollOut:function(c){var d=this;
if(c){var a="rollOutGraphItem";
var b={type:a,item:c,index:c.index,graph:this};
d.fire(a,b);d.chart.fire(a,b);
d.chart.hideBalloon()
}},handleClick:function(e){var f=this;
if(e){var c="clickGraphItem";
var d={type:c,item:e,index:e.index,graph:this};
f.fire(c,d);f.chart.fire(c,d);
var b=e.url;var a=f.urlTarget;
if(b){if(a=="_self"||!a){window.location.href=b
}else{window.open(b)}}}},handleDoubleClick:function(c){var d=this;
if(c){var a="doubleClickGraphItem";
var b={type:a,item:c,index:c.index,graph:this};
d.fire(a,b);d.chart.fire(a,b)
}},zoom:function(c,a){var b=this;
b.start=c;b.end=a;b.draw()
},changeOpacity:function(b){var c=this;
if(c.set){c.set.attr({opacity:b})
}},destroy:function(){var c=this;
AmCharts.removeSet(c.set);
var b=c.timeOuts;if(b){for(var a=0;
a<b.length;a++){clearTimeout(b[a])
}}c.timeOuts=[]}});AmCharts.ChartCursor=AmCharts.Class({construct:function(){var a=this;
a.createEvents("changed","zoomed");
a.cursorAlpha=1;a.selectionAlpha=0.2;
a.cursorColor="#CC0000";
a.categoryBalloonAlpha=1;
a.color="#FFFFFF";a.type="cursor";
a.zoomed=false;a.zoomable=true;
a.pan=false;a.animate=true;
a.categoryBalloonDateFormat="MMM DD, YYYY";
a.valueBalloonsEnabled=true;
a.categoryBalloonEnabled=true;
a.rolledOver=false;a.cursorPosition="middle";
a.skipZoomDispatch=false;
a.bulletsEnabled=false;
a.bulletSize=8},draw:function(){var e=this;
e.destroy();var b=e.chart;
var a=b.container;e.rotate=b.rotate;
e.container=a;e.set=a.set();
e.allBullets=a.set();
var d=new AmCharts.AmBalloon();
e.categoryBalloon=d;d.cornerRadius=0;
d.borderWidth=1;d.borderAlpha=0;
d.chart=b;var c=e.categoryBalloonColor;
if(c==undefined){c=e.cursorColor
}d.fillColor=c;d.fillAlpha=e.categoryBalloonAlpha;
d.borderColor=c;d.color=e.color;
if(e.rotate){d.pointerOrientation="horizontal"
}if(e.type=="cursor"){e.createCursor()
}else{e.createCrosshair()
}e.interval=setInterval(function(){e.detectMovement.call(e)
},20)},updateData:function(){var b=this;
var a=b.chart.chartData;
b.data=a;if(a){if(a.length>0){if(a){b.firstTime=a[0].time;
b.lastTime=a[a.length-1].time
}}}},createCursor:function(){var p=this;
var k=p.chart;var j=p.cursorAlpha;
var l=k.categoryAxis;
var r=l.position;var s=l.inside;
var b=l.axisThickness;
var a=p.categoryBalloon;
var d;var n;var f=p.x;
var e=p.y;var i=k.dx;
var h=k.dy;var o=p.width;
var m=p.height;var q=k.rotate;
var c=l.tickLength;a.pointerWidth=c;
if(q){d=[0,o,o+i];n=[0,0,h]
}else{d=[i,0,0];n=[h,0,m]
}var g=AmCharts.line(p.container,d,n,p.cursorColor,j,1);
p.line=g;g.translate(f+","+e);
p.set.push(g);if(q){if(s){a.pointerWidth=0
}if(r=="right"){if(s){a.setBounds(f,e+h,f+o+i,e+m+h)
}else{a.setBounds(f+o+i+b,e+h,f+o+1000,e+m+h)
}}else{if(s){a.setBounds(f,e,o+f,m+e)
}else{a.setBounds(-1000,-1000,f-c-b,e+m+15)
}}}else{a.maxWidth=o;
if(l.parseDates){c=0;
a.pointerWidth=0}if(r=="top"){if(s){a.setBounds(f+i,e+h,o+i+f,m+e)
}else{a.setBounds(f+i,-1000,o+i+f,e+h-c-b)
}}else{if(s){a.setBounds(f,e,o+f,m+e-c)
}else{a.setBounds(f,e+m+c+b-1,f+o,e+m+c+b)
}}}p.hideCursor()},createCrosshair:function(){var e=this;
var c=e.cursorAlpha;var a=e.container;
var b=AmCharts.line(a,[0,0],[0,e.height],e.cursorColor,c,1);
var d=AmCharts.line(a,[0,e.width],[0,0],e.cursorColor,c,1);
b.translate(e.x+","+e.y);
d.translate(e.x+","+e.y);
e.set.push(b);e.set.push(d);
e.vLine=b;e.hLine=d;e.selection=AmCharts.rect(a,1,[e.cursorColor],[e.selectionAlpha]);
e.selection.hide();e.hideCursor()
},detectMovement:function(){var e=this;
var c=e.chart.mouseX;
var b=e.chart.mouseY;
var a=e.x;var d=e.y;if(c>a&&c<a+e.width&&b>d&&b<e.height+d){if(e.pan){if(!e.rolledOver){e.chart.setMouseCursor("move")
}}e.rolledOver=true;e.setPosition()
}else{if(e.rolledOver){e.handleMouseOut();
e.rolledOver=false}}},getMousePosition:function(){var f=this;
var c;var b=f.x;var e=f.y;
var d=f.width;var a=f.height;
if(f.rotate){c=f.chart.mouseY;
if(c<e){c=e}if(c>a+e){c=a+e
}}else{c=f.chart.mouseX;
if(c<b){c=b}if(c>d+b){c=d+b
}}return c},updateCrosshair:function(){var e=this;
var d=e.chart.mouseX;
var b=e.chart.mouseY;
var h=e.x;var g=e.y;var a=e.vLine;
var c=e.hLine;d=AmCharts.fitToBounds(d,h,h+e.width);
b=AmCharts.fitToBounds(b,g,g+e.height);
if(e.cursorAlpha>0){var f=a.getBBox();
var i=c.getBBox();a.show();
c.show();a.translate(Math.round(d-f.x-2)+","+0);
c.translate(0+","+Math.round(b-i.y-2))
}if(e.zooming){e.updateSelectionSize(d,b)
}if(!e.chart.mouseIsOver&&!e.zooming){e.hideCursor()
}},updateSelectionSize:function(a,f){var e=this;
if(e.selection){e.selection.remove()
}var h=e.x;var g=e.y;
var b=e.width;var i=e.height;
var d=e.selectionPosX;
var c=e.selectionPosY;
if(!isNaN(a)){if(d>a){h=a;
b=d-a}if(d<a){h=d;b=a-d
}if(d==a){h=a;b=0}}if(!isNaN(f)){var g=e.y;
if(c>f){g=f;i=c-f}if(c<f){g=c;
i=f-c}if(c==f){g=f;i=0
}}if(b>0&&i>0){e.selection=AmCharts.rect(e.container,b,i,[e.cursorColor],[e.selectionAlpha]);
e.selection.translate(h+","+g);
e.set.push(e.selection)
}},arrangeBalloons:function(){var g=this;
var a=g.x;var f=g.y;var c=g.valueBalloons;
var b=f+g.height;c.sort(g.compareY);
for(var d=0;d<c.length;
d++){var e=c[d].balloon;
e.setBounds(a,f,a+g.width,b);
e.draw();b=e.yPos-3}g.arrangeBalloons2()
},compareY:function(d,c){if(d.yy<c.yy){return 1
}else{return -1}},arrangeBalloons2:function(){var h=this;
var c=h.valueBalloons;
c.reverse();var a;var f;
for(var e=0;e<c.length;
e++){var g=c[e].balloon;
a=g.bottom;var d=g.bottom-g.yPos;
if(e>0){if(a-d<f+3){g.setBounds(h.x,f+3,h.x+h.width,f+d+3);
g.draw()}}if(g.set){g.set.show()
}f=g.bottomCoordinate
}},showBullets:function(){var e=this;
e.allBullets.remove();
var f=e.chart.graphs;
for(var d=0;d<f.length;
d++){var j=f[d];if(j.showBalloon&&!j.hidden&&j.balloonText){var g=e.data[e.index];
var l=g.axes[j.valueAxis.id].graphs[j.id];
var h=l.y;if(!isNaN(h)){var b;
var c;var k;b=l.x;if(e.rotate){c=h;
k=b}else{c=b;k=h}var a=AmCharts.circle(e.container,e.bulletSize/2,e.chart.getBalloonColor(j,l),j.cursorBulletAlpha);
a.translate(c+","+k);
e.allBullets.push(a);
e.set.push(a)}}}},destroy:function(){var b=this;
b.clear();var a=b.categoryBalloon;
if(a){a.destroy()}b.destroyValueBalloons();
AmCharts.removeSet(b.set)
},clear:function(){var a=this;
clearInterval(a.interval)
},destroyValueBalloons:function(){var c=this;
var a=c.valueBalloons;
if(a){for(var b=0;b<a.length;
b++){a[b].balloon.destroy()
}}},zoom:function(b,e,c,h){var i=this;
var j=i.chart;i.destroyValueBalloons();
i.zooming=false;var k;
if(i.rotate){k=j.mouseY;
i.selectionPosY=k}else{k=j.mouseX;
i.selectionPosX=k}i.start=b;
i.end=e;i.startTime=c;
i.endTime=h;i.zoomed=true;
var g=j.categoryAxis;
var f=i.rotate;var a=i.width;
var l=i.height;var m;
if(g.parseDates&&!g.equalSpacing){var d=h-c+g.minDuration();
if(f){m=l/d}else{m=a/d
}}else{if(f){m=l/(e-b)
}else{m=a/(e-b)}}i.stepWidth=m;
i.setPosition();i.hideCursor()
},hideCursor:function(a){var b=this;
if(b.set){b.set.hide()
}b.categoryBalloon.hide();
b.destroyValueBalloons();
b.allBullets.remove();
b.previousIndex=NaN},setPosition:function(a,c){var d=this;
if(c==undefined){c=true
}if(d.type=="cursor"){if(d.data.length>0){if(!a){a=d.getMousePosition()
}if(a!=d.previousMousePosition||d.zoomed==true){if(!isNaN(a)){var b=d.chart.categoryAxis.xToIndex(a);
if(b!=d.previousIndex||d.zoomed||d.cursorPosition=="mouse"){d.updateCursor(b,c);
d.zoomed=false}}}d.previousMousePosition=a
}}else{d.updateCrosshair()
}},updateCursor:function(D,Q){var f=this;
if(Q==undefined){Q=true
}f.index=D;var ab=f.chart;
var j=ab.categoryAxis;
var O=f.x;var M=f.y;var g=ab.dx;
var e=ab.dy;var b=f.width;
var c=f.height;var v=f.data[D];
var B=v.x[j.id];var aa=ab.rotate;
var w=j.inside;var K=ab.mouseX;
var J=ab.mouseY;var Z=f.stepWidth;
var V=f.categoryBalloon;
var q=f.firstTime;var ad=f.lastTime;
var L=f.cursorPosition;
var z=j.position;var R=f.zooming;
var p=f.panning;var G=ab.graphs;
var C=ab.touchEventsEnabled;
var h=j.axisThickness;
if(p){var a;var P=f.panClickPos;
var o=f.panClickEndTime;
var S=f.panClickStartTime;
var A=f.panClickEnd;var I=f.panClickStart;
if(aa){a=P-J}else{a=P-K
}var d=a/Z;if(!j.parseDates||j.equalSpacing){d=Math.round(d)
}if(d!=0){if(j.parseDates&&!j.equalSpacing){if(o+d>ad){d=ad-o
}if(S+d<q){d=q-S}var ac={};
ac.type="zoomed";ac.start=S+d;
ac.end=o+d;f.fire("zoomed",ac)
}else{if(A+d>=f.data.length||I+d<0){}else{var ac={};
ac.type="zoomed";ac.start=I+d;
ac.end=A+d;f.fire(ac.type,ac)
}}}}else{if(L=="start"){B-=j.cellWidth/2
}if(L=="mouse"){if(aa){B=J-2
}else{B=K-2}}if(aa){if(B<M){if(R){B=M
}else{f.hideCursor();
return}}if(B>c+1+M){if(R){B=c+1+M
}else{f.hideCursor();
return}}}else{if(B<O){if(R){B=O
}else{f.hideCursor();
return}}if(B>b+O){if(R){B=b+O
}else{f.hideCursor();
return}}}if(f.cursorAlpha>0){var l=f.line;
var u=l.getBBox();if(aa){l.translate(0+","+Math.round((B-u.y+e)))
}else{l.translate(Math.round((B-u.x))+","+0)
}l.show()}if(aa){f.linePos=B+e
}else{f.linePos=B}if(R){if(aa){f.updateSelectionSize(NaN,B)
}else{f.updateSelectionSize(B,NaN)
}}var t=true;if(C&&R){t=false
}if(f.categoryBalloonEnabled&&t){if(aa){if(w){if(z=="right"){V.setBounds(O,M+e,O+b+g,O+B+e)
}else{V.setBounds(O,M+e,O+b+g,O+B)
}}if(z=="right"){if(w){V.setPosition(O+b+g,B+e)
}else{V.setPosition(O+b+g+h,B+e)
}}else{if(w){V.setPosition(O,B)
}else{V.setPosition(O-h,B)
}}}else{if(z=="top"){if(w){V.setPosition(B+g,M+e)
}else{V.setPosition(B+g,M+e-h+1)
}}else{if(w){V.setPosition(B,M+c)
}else{V.setPosition(B,M+c+h-1)
}}}if(j.parseDates){var U=AmCharts.formatDate(v.category,f.categoryBalloonDateFormat);
if(U.indexOf("fff")!=-1){U=AmCharts.formatMilliseconds(U,v.category)
}V.showBalloon(U)}else{V.showBalloon(v.category)
}}else{V.hide()}if(G&&f.bulletsEnabled){f.showBullets()
}f.destroyValueBalloons();
if(G&&f.valueBalloonsEnabled&&t&&ab.balloon.enabled){var F=[];
f.valueBalloons=F;for(var Y=0;
Y<G.length;Y++){var N=G[Y];
if(N.showBalloon&&!N.hidden&&N.balloonText){var T=v.axes[N.valueAxis.id].graphs[N.id];
var n=T.y;if(!isNaN(n)){var H;
var m;var X;H=T.x;var k=true;
if(aa){m=n;X=H;if(X<M||X>M+c){k=false
}}else{m=H;X=n;if(m<O||m>O+b){k=false
}}if(k){var E=ab.getBalloonColor(N,T);
var W=new AmCharts.AmBalloon();
W.chart=ab;AmCharts.copyProperties(ab.balloon,W,["fillColor","fillAlpha","borderThickness","borderColor","borderAlpha","cornerRadius","maximumWidth","horizontalPadding","verticalPadding","pointerWidth","color","fontSize","showBullet","textShadowColor","adjustBorderColor"]);
W.setBounds(O,M,O+b,M+c);
W.pointerOrientation="horizontal";
W.changeColor(E);if(N.balloonAlpha!=undefined){W.fillAlpha=N.balloonAlpha
}if(N.balloonTextColor!=undefined){W.color=N.balloonTextColor
}W.setPosition(m,X);var r=ab.formatString(N.balloonText,T,N);
if(r!=""){W.showBalloon(r)
}if(!aa&&W.set){W.set.hide()
}F.push({yy:n,balloon:W})
}}}}if(!aa){f.arrangeBalloons()
}}if(Q){var s="changed";
var ac={type:s};ac.index=D;
ac.zooming=R;if(aa){ac.position=J
}else{ac.position=K}f.fire(s,ac);
ab.fire(s,ac);f.skipZoomDispatch=false
}else{f.skipZoomDispatch=true
}f.previousIndex=D}if(!ab.mouseIsOver&&!R&&!p){f.hideCursor()
}},isZooming:function(a){var b=this;
if(a&&a!=b.zooming){b.handleMouseDown("fake")
}if(!a&&a!=b.zooming){b.handleMouseUp()
}},handleMouseOut:function(){var c=this;
if(c.zooming){c.setPosition()
}else{c.index=undefined;
var a={};var b="changed";
a.type=b;a.index=undefined;
c.fire(b,a);c.hideCursor()
}},handleReleaseOutside:function(){this.handleMouseUp()
},handleMouseUp:function(){var f=this;
if(f.pan){f.rolledOver=false
}else{if(f.zoomable){var i=f.chart;
var c=i.mouseX;var b=i.mouseY;
if(f.zooming){var l;if(f.type=="cursor"){var j;
if(f.rotate){j=b;f.selectionPosY=j
}else{j=c;f.selectionPosX=j
}if(Math.abs(j-f.initialMouse)<2&&f.fromIndex==f.index){}else{l={type:"zoomed"};
if(f.index<f.fromIndex){l.end=f.fromIndex;
l.start=f.index}else{l.end=f.index;
l.start=f.fromIndex}var e=f.chart.categoryAxis;
if(e.parseDates&&!e.equalSpacing){l.start=f.data[l.start].time;
l.end=f.data[l.end].time
}f.allBullets.remove();
if(!f.skipZoomDispatch){f.fire("zoomed",l)
}}}else{var g=b;var h=c;
if(Math.abs(h-f.initialMouseX)<3&&Math.abs(g-f.initialMouseY)<3){}else{var a="zoomed";
var d=f.selection.getBBox();
l={type:a};l.selectionHeight=d.height;
l.selectionWidth=d.width;
l.selectionY=d.y-f.y;
l.selectionX=d.x-f.x;
if(!f.skipZoomDispatch){f.fire(a,l)
}}}var k=f.selection;
if(k){k.remove()}}}}f.skipZoomDispatch=false;
f.zooming=false;f.panning=false
},handleMouseDown:function(e){var f=this;
if(f.zoomable||f.pan){var b=f.rotate;
var d=f.chart;var c=d.mouseX;
var a=d.mouseY;if((c>f.x&&c<f.x+f.width&&a>f.y&&a<f.height+f.y)||e=="fake"){f.setPosition();
if(f.pan){f.zoomable=false;
d.setMouseCursor("move");
f.panning=true;f.hideCursor(true);
if(b){f.panClickPos=a
}else{f.panClickPos=c
}f.panClickStart=f.start;
f.panClickEnd=f.end;f.panClickStartTime=f.startTime;
f.panClickEndTime=f.endTime
}if(f.zoomable){if(f.type=="cursor"){f.fromIndex=f.index;
if(b){f.initialMouse=a;
f.selectionPosY=f.linePos
}else{f.initialMouse=c;
f.selectionPosX=f.linePos
}}else{f.initialMouseX=c;
f.initialMouseY=a;f.selectionPosX=c;
f.selectionPosY=a}f.zooming=true
}}}}});AmCharts.SimpleChartScrollbar=AmCharts.Class({construct:function(){var a=this;
a.createEvents("zoomed");
a.backgroundColor="#D4D4D4";
a.backgroundAlpha=1;a.selectedBackgroundColor="#EFEFEF";
a.selectedBackgroundAlpha=1;
a.scrollDuration=2;a.resizeEnabled=true;
a.hideResizeGrips=true;
a.scrollbarHeight=20;
a.updateOnReleaseOnly=false;
a.dragIconWidth=11;a.dragIconHeight=18
},draw:function(){var k=this;
k.destroy();k.interval=setInterval(function(){k.updateScrollbar.call(k)
},20);var b=k.chart.container;
var g=k.rotate;var l=k.chart;
var o=b.set();k.set=o;
if(l.touchEventsEnabled){k.updateOnReleaseOnly=true
}var d;var p;if(g){d=k.scrollbarHeight;
p=k.chart.plotAreaHeight
}else{p=k.scrollbarHeight;
d=k.chart.plotAreaWidth
}k.width=d;k.height=p;
if(p&&d){var i=AmCharts.rect(b,d,p,[k.backgroundColor],[k.backgroundAlpha]);
o.push(i);if(l.touchEventsEnabled){i.touchend(function(){k.handleBackgroundClick()
})}i.click(function(){k.handleBackgroundClick()
}).mouseover(function(){k.handleMouseOver()
}).mouseout(function(){k.handleMouseOut()
});var a=AmCharts.rect(b,d,p,[k.selectedBackgroundColor],[k.selectedBackgroundAlpha]);
k.selectedBG=a;o.push(a);
var c=AmCharts.rect(b,d,p,["#000"],[0]);
k.dragger=c;o.push(c);
if(l.touchEventsEnabled){c.touchstart(function(q){k.handleDragStart(q)
}).touchend(function(){k.handleDragStop()
})}c.mousedown(function(q){k.handleDragStart(q)
}).mouseup(function(){k.handleDragStop()
}).mouseover(function(){k.handleDraggerOver()
}).mouseout(function(){k.handleMouseOut()
});var m=k.dragIconWidth;
var e=k.dragIconHeight;
var f=l.pathToImages;
var n=b.image(l.pathToImages+"dragIcon.gif",0,0,m,e);
k.dragIconLeft=n;o.push(k.dragIconLeft);
var h=b.image(l.pathToImages+"dragIcon.gif",0,0,m,e);
k.dragIconRight=h;o.push(h);
var j;if(g){j=Math.round(k.width/2-m/2);
n.attr("x",j);h.attr("x",j);
h.attr("rotation",90);
n.attr("rotation",90)
}else{j=Math.round(k.height/2-e/2)+AmCharts.ddd;
n.attr("y",j);h.attr("y",j)
}k.iconPosition=j;n.mousedown(function(){k.handleLeftIconDragStart()
}).mouseup(function(){k.handleLeftIconDragStop()
}).mouseover(function(){k.handleIconRollOver()
}).mouseout(function(){k.handleIconRollOut()
});h.mousedown(function(){k.handleRightIconDragStart()
}).mouseup(function(){k.handleRightIconDragStop()
}).mouseover(function(){k.handleIconRollOver()
}).mouseout(function(){k.handleIconRollOut()
});if(l.chartData.length>0){o.show()
}else{o.hide()}if(k.hideResizeGrips){n.hide();
h.hide()}}o.translate(k.x+","+k.y)
},updateScrollbarSize:function(h,g){var f=this;
var a=f.dragger;var c;
var b;var d;var i;var e;
if(f.rotate){c=f.x;b=h;
d=f.width;i=g-h;e=g-h;
a.attr("height",e);a.attr("y",b)
}else{c=h;b=f.y;d=g-h;
i=f.height;e=g-h;a.attr("width",e);
a.attr("x",c)}f.clipAndUpdate(c,b,d,i)
},updateScrollbar:function(){var t=this;
var g;var e=false;var d;
var v;var c=t.dragger;
var a=c.getBBox();var m=a.x;
var k=a.y;var b=a.width;
var B=a.height;var w=t.rotate;
var n=t.chart;var r=t.width;
var p=t.height;var i=n.mouseX;
var h=n.mouseY;var l=t.x;
var j=t.y;var q=t.initialMouseCoordinate;
if(n.mouseIsOver){if(t.dragging){var o=t.initialDragCoordinate;
if(w){var z=o+(h-q);if(z<j){z=j
}var s=j+p-B;if(z>s){z=s
}c.attr({y:z})}else{var A=o+(i-q);
if(A<l){A=l}var u=l+r-b;
if(A>u){A=u}c.attr({x:A})
}}if(t.resizingRight){if(w){g=h-k;
if(g+k>p+j){g=p-k+j}if(g<0){t.resizingRight=false;
t.resizingLeft=true;e=true
}else{if(g==0){g=0.1}c.attr("height",g)
}}else{g=i-m;if(g+m>r+l){g=r-m+l
}if(g<0){t.resizingRight=false;
t.resizingLeft=true;e=true
}else{if(g==0){g=0.1}c.attr("width",g)
}}}if(t.resizingLeft){if(w){d=k;
v=h;if(v<j){v=j}if(v>p+j){v=p+j
}if(e==true){g=d-v}else{g=B+d-v
}if(g<0){t.resizingRight=true;
t.resizingLeft=false;
c.attr("y",d+B)}else{if(g==0){g=0.1
}c.attr("y",v);c.attr("height",g)
}}else{d=m;v=i;if(v<l){v=l
}if(v>r+l){v=r+l}if(e==true){g=d-v
}else{g=b+d-v}if(g<0){t.resizingRight=true;
t.resizingLeft=false;
c.attr("x",d+b)}else{if(g==0){g=0.1
}c.attr("x",v);c.attr("width",g)
}}}a=c.getBBox();m=a.x;
k=a.y;b=a.width;B=a.height;
var f=false;if(w){if(t.clipY!=k||t.clipH!=B){f=true
}}else{if(t.clipX!=m||t.clipW!=b){f=true
}}if(f){t.clipAndUpdate(m,k,b,B);
if(!t.updateOnReleaseOnly){t.dispatchScrollbarEvent()
}}}},maskGraphs:function(){},clipAndUpdate:function(a,f,b,d){var e=this;
e.clipX=a;e.clipY=f;e.clipW=b;
e.clipH=d;var c=a+","+f+","+b+","+d;
e.clipRect=c;e.selectedBG.attr({"clip-rect":c});
e.updateDragIconPositions();
e.maskGraphs(c)},dispatchScrollbarEvent:function(){var g=this;
if(g.skipEvent){g.skipEvent=false
}else{g.chart.hideBalloon();
var h=g.dragger.getBBox();
var a=h.x-g.x;var i=h.y-g.y;
var f=h.width;var c=h.height;
var j;var d;var e;if(g.rotate){j=i;
d=c;e=g.height/c}else{j=a;
d=f;e=g.width/f}var b={type:"zoomed",position:j,multiplyer:e};
g.fire(b.type,b)}},updateDragIconPositions:function(){var h=this;
var f=h.dragger.getBBox();
var e=f.x;var g=f.y;var d=h.dragIconHeight;
var c=h.dragIconWidth;
var a=h.dragIconLeft;
var b=h.dragIconRight;
if(h.rotate){a.attr("y",Math.round(g-d/2));
b.attr("y",Math.round(g+f.height-d/2))
}else{a.attr("x",Math.round(e-c/2));
b.attr("x",Math.round(e-c/2+f.width))
}},showDragIcons:function(){var a=this;
if(a.resizeEnabled){a.dragIconLeft.show();
a.dragIconRight.show()
}},hideDragIcons:function(){var a=this;
if(!a.resizingLeft&&!a.resizingRight&&!a.dragging){if(a.hideResizeGrips){a.dragIconLeft.hide();
a.dragIconRight.hide()
}a.removeCursors()}},removeCursors:function(){this.chart.setMouseCursor("auto")
},relativeZoom:function(b,a){var e=this;
e.multiplyer=b;e.position=a;
var d=a;var c;if(e.rotate){d+=e.y;
c=d+e.height/b}else{d+=e.x;
c=d+e.width/b}e.updateScrollbarSize(d,c)
},destroy:function(){var a=this;
a.clear();AmCharts.removeSet(a.set)
},clear:function(){var a=this;
clearInterval(a.interval)
},handleDragStart:function(a){var c=this;
if(a){a.preventDefault()
}c.removeCursors();c.dragging=true;
var b=c.dragger.getBBox();
if(c.rotate){c.initialDragCoordinate=b.y;
c.initialMouseCoordinate=c.chart.mouseY
}else{c.initialDragCoordinate=b.x;
c.initialMouseCoordinate=c.chart.mouseX
}},handleDragStop:function(a){var b=this;
if(b.updateOnReleaseOnly){b.updateScrollbar();
b.skipEvent=false;b.dispatchScrollbarEvent()
}b.dragging=false;if(b.mouseIsOver){b.removeCursors()
}b.updateScrollbar()},handleDraggerOver:function(a){this.handleMouseOver()
},handleLeftIconDragStart:function(a){this.resizingLeft=true
},handleLeftIconDragStop:function(a){var b=this;
b.resizingLeft=false;
if(!b.mouseIsOver){b.removeCursors()
}},handleRightIconDragStart:function(a){this.resizingRight=true
},handleRightIconDragStop:function(a){var b=this;
b.resizingRight=false;
if(!b.mouseIsOver){b.removeCursors()
}},handleIconRollOut:function(){this.removeCursors()
},handleIconRollOver:function(a){var b=this;
if(b.rotate){b.chart.setMouseCursor("n-resize")
}else{b.chart.setMouseCursor("e-resize")
}b.handleMouseOver()},handleBackgroundClick:function(a){var g=this;
if(!g.resizingRight&&!g.resizingLeft){g.zooming=true;
var l;var b;var e;var d=g.scrollDuration;
var c=g.dragger;var m=g.dragger.getBBox();
var n=m.height;var j=m.width;
var h=g.chart;var i=g.y;
var k=g.x;var f=g.rotate;
if(f){l="y";b=m.y;e=h.mouseY-n/2;
e=AmCharts.fitToBounds(e,i,i+g.height-n)
}else{l="x";b=m.x;e=h.mouseX-j/2;
e=AmCharts.fitToBounds(e,k,k+g.width-j)
}if(g.updateOnReleaseOnly){g.skipEvent=false;
c.attr(l,e);g.dispatchScrollbarEvent()
}else{if(f){c.animate({translation:0+","+(e-m.y)},d*1000,">")
}else{c.animate({translation:(e-m.x)+","+0},d*1000,">")
}}}},handleReleaseOutside:function(){var a=this;
if(a.set){if(a.resizingLeft||a.resizingRight||a.dragging){if(a.updateOnReleaseOnly){a.updateScrollbar();
a.skipEvent=false;a.dispatchScrollbarEvent()
}}a.resizingLeft=false;
a.resizingRight=false;
a.dragging=false;a.mouseIsOver=false;
a.removeCursors();if(a.hideResizeGrips){a.dragIconLeft.hide();
a.dragIconRight.hide()
}a.updateScrollbar()}},handleMouseOver:function(a){var b=this;
b.mouseIsOver=true;b.showDragIcons()
},handleMouseOut:function(a){var b=this;
b.mouseIsOver=false;b.hideDragIcons()
}});AmCharts.ChartScrollbar=AmCharts.Class({inherits:AmCharts.SimpleChartScrollbar,construct:function(){var a=this;
AmCharts.ChartScrollbar.base.construct.call(a);
a.graphLineColor="#000000";
a.graphLineAlpha=0;a.graphFillColor="#000000";
a.graphFillAlpha=0.1;
a.selectedGraphLineColor="#000000";
a.selectedGraphLineAlpha=0;
a.selectedGraphFillColor="#000000";
a.selectedGraphFillAlpha=0.5;
a.gridCount=0;a.gridColor="#FFFFFF";
a.gridAlpha=0.7;a.autoGridCount=false;
a.skipEvent=false;a.scrollbarCreated=false
},init:function(){var f=this;
var d=f.categoryAxis;
var c=f.chart;if(!d){d=new AmCharts.CategoryAxis();
f.categoryAxis=d}d.chart=c;
d.id="scrollbar";d.dateFormats=c.categoryAxis.dateFormats;
d.axisItemRenderer=AmCharts.RectangularAxisItemRenderer;
d.axisRenderer=AmCharts.RectangularAxisRenderer;
d.guideFillRenderer=AmCharts.RectangularAxisGuideFillRenderer;
d.inside=true;d.tickLength=0;
d.axisAlpha=0;if(f.graph){var e=f.valueAxis;
if(!e){e=new AmCharts.ValueAxis();
f.valueAxis=e;e.visible=false;
e.scrollbar=true;e.axisItemRenderer=AmCharts.RectangularAxisItemRenderer;
e.axisRenderer=AmCharts.RectangularAxisRenderer;
e.guideFillRenderer=AmCharts.RectangularAxisGuideFillRenderer;
e.chart=c}var b=f.selectedGraph;
if(!b){b=new AmCharts.AmGraph();
b.scrollbar=true;f.selectedGraph=b
}var a=f.unselectedGraph;
if(!a){a=new AmCharts.AmGraph();
a.scrollbar=true;f.unselectedGraph=a
}}f.scrollbarCreated=true
},draw:function(){var q=this;
AmCharts.ChartScrollbar.base.draw.call(q);
if(!q.scrollbarCreated){q.init()
}var h=q.chart;var z=h.chartData;
var j=q.categoryAxis;
var t=q.rotate;var f=q.x;
var e=q.y;var n=q.width;
var l=q.height;var A=h.categoryAxis;
j.setOrientation(!t);
j.parseDates=A.parseDates;
j.rotate=t;j.equalSpacing=A.equalSpacing;
j.minPeriod=A.minPeriod;
j.startOnAxis=A.startOnAxis;
j.x=f;j.y=e;j.visibleAxisWidth=n;
j.visibleAxisHeight=l;
j.visibleAxisX=f;j.visibleAxisY=e;
j.width=n;j.height=l;
j.gridCount=q.gridCount;
j.gridColor=q.gridColor;
j.gridAlpha=q.gridAlpha;
j.color=q.color;j.autoGridCount=q.autoGridCount;
if(j.parseDates&&!j.equalSpacing){j.timeZoom(z[0].time,z[z.length-1].time)
}j.zoom(0,z.length-1);
var b=q.graph;if(b){var u=q.valueAxis;
var g=b.valueAxis;u.id=g.id;
u.rotate=t;u.setOrientation(t);
u.x=f;u.y=e;u.width=n;
u.height=l;u.visibleAxisX=f;
u.visibleAxisY=e;u.visibleAxisWidth=n;
u.visibleAxisHeight=l;
u.dataProvider=z;u.reversed=g.reversed;
u.logarithmic=g.logarithmic;
var o=Infinity;var r=-Infinity;
for(var s=0;s<z.length;
s++){var c=z[s].axes[g.id].graphs[b.id].values;
for(var p in c){if(p!="percents"&&p!="total"){var B=c[p];
if(B<o){o=B}if(B>r){r=B
}}}}if(o!=Infinity){u.minimum=o
}if(r!=-Infinity){u.maximum=r+(r-o)*0.1
}if(o==r){u.minimum-=1;
u.maximum+=1}u.zoom(0,z.length-1);
var m=q.unselectedGraph;
m.id=b.id;m.rotate=t;
m.chart=h;m.chartType=h.chartType;
m.data=z;m.valueAxis=u;
m.chart=b.chart;m.categoryAxis=q.categoryAxis;
m.valueField=b.valueField;
m.openField=b.openField;
m.closeField=b.closeField;
m.highField=b.highField;
m.lowField=b.lowField;
m.lineAlpha=q.graphLineAlpha;
m.lineColor=q.graphLineColor;
m.fillAlphas=[q.graphFillAlpha];
m.fillColors=[q.graphFillColor];
m.connect=b.connect;m.hidden=b.hidden;
m.width=n;m.height=l;
m.x=f;m.y=e;var w=q.selectedGraph;
w.id=b.id;w.rotate=t;
w.chart=h;w.chartType=h.chartType;
w.data=z;w.valueAxis=u;
w.chart=b.chart;w.categoryAxis=j;
w.valueField=b.valueField;
w.openField=b.openField;
w.closeField=b.closeField;
w.highField=b.highField;
w.lowField=b.lowField;
w.lineAlpha=q.selectedGraphLineAlpha;
w.lineColor=q.selectedGraphLineColor;
w.fillAlphas=[q.selectedGraphFillAlpha];
w.fillColors=[q.selectedGraphFillColor];
w.connect=b.connect;w.hidden=b.hidden;
w.width=n;w.height=l;
w.x=f;w.y=e;var a=q.graphType;
if(!a){a=b.type}m.type=a;
w.type=a;var v=z.length-1;
m.zoom(0,v);w.zoom(0,v);
var d=q.dragger;w.set.insertBefore(d);
m.set.insertBefore(d);
w.set.click(function(){q.handleBackgroundClick()
}).mouseover(function(){q.handleMouseOver()
}).mouseout(function(){q.handleMouseOut()
});m.set.click(function(){q.handleBackgroundClick()
}).mouseover(function(){q.handleMouseOver()
}).mouseout(function(){q.handleMouseOut()
})}},timeZoom:function(b,a){var c=this;
c.startTime=b;c.endTime=a;
c.timeDifference=a-b;
c.skipEvent=true;c.zoomScrollbar()
},zoom:function(c,a){var b=this;
b.start=c;b.end=a;b.skipEvent=true;
b.zoomScrollbar()},dispatchScrollbarEvent:function(){var o=this;
if(o.skipEvent){o.skipEvent=false
}else{var s=o.chart.chartData;
var k;var q;var e=o.dragger.getBBox();
var f=e.x;var n=e.y;var t=e.width;
var b=e.height;if(o.rotate){k=n;
q=b}else{k=f;q=t}var p;
var l=o.categoryAxis;
var i=o.stepWidth;if(l.parseDates&&!l.equalSpacing){var r=s[s.length-1].time;
var h=s[0].time;if(o.rotate){k-=o.y
}else{k-=o.x}var g=l.minDuration();
var m=Math.round(k/i)+h;
var c;if(!o.dragging){c=Math.round((k+q)/i)+h-g
}else{c=m+o.timeDifference
}if(m>c){m=c}if(m!=o.startTime||c!=o.endTime){o.startTime=m;
o.endTime=c;p={type:"zoomed",start:m,end:c,startDate:new Date(m),endDate:new Date(c)};
o.fire(p.type,p)}}else{if(!l.startOnAxis){var j=i/2;
k+=j}q-=o.stepWidth/2;
var d=l.xToIndex(k);var a=l.xToIndex(k+q);
if(d!=o.start||o.end!=a){if(l.startOnAxis){if(o.resizingRight&&d==a){a++
}if(o.resizingLeft&&d==a){if(d>0){d--
}else{a=1}}}o.start=d;
if(!o.dragging){o.end=a
}else{o.end=o.start+o.difference
}p={type:"zoomed",start:o.start,end:o.end};
if(l.parseDates){if(s[o.start]){p.startDate=new Date(s[o.start].time)
}if(s[o.end]){p.endDate=new Date(s[o.end].time)
}}o.fire(p.type,p)}}}},zoomScrollbar:function(){var h=this;
var c;var a;var d=h.chart.chartData;
var f=h.categoryAxis;
var e;if(f.parseDates&&!f.equalSpacing){e=f.stepWidth;
var g=d[0].time;c=e*(h.startTime-g);
a=e*(h.endTime-g+f.minDuration());
if(h.rotate){c+=h.y;a+=h.y
}else{c+=h.x;a+=h.x}}else{c=d[h.start].x[f.id];
a=d[h.end].x[f.id];e=f.stepWidth;
if(!f.startOnAxis){var b=e/2;
c-=b;a+=b}}h.stepWidth=e;
h.updateScrollbarSize(c,a)
},maskGraphs:function(c){var e=this;
var b=e.selectedGraph;
if(b){var d=b.set;for(var a=0;
a<d.length;a++){d[a].attr({"clip-rect":c})
}}},handleDragStart:function(){var a=this;
AmCharts.ChartScrollbar.base.handleDragStart.call(a);
a.difference=a.end-a.start;
a.timeDifference=a.endTime-a.startTime;
if(a.timeDifference<0){a.timeDifference=0
}},handleBackgroundClick:function(){var a=this;
AmCharts.ChartScrollbar.base.handleBackgroundClick.call(a);
if(!a.dragging){a.difference=a.end-a.start;
a.timeDifference=a.endTime-a.startTime;
if(a.timeDifference<0){a.timeDifference=0
}}}});AmCharts.circle=function(b,a,e,d,c,f,g,i){if(c==undefined||c==0){c=1
}if(f==undefined){f="#000000"
}if(g==undefined){g=0
}if(i){e="r"+e+"-"+AmCharts.adjustLuminosity(e,-0.6)
}var h={fill:e,stroke:f,"fill-opacity":d,"stroke-width":c,"stroke-opacity":g};
return b.circle(0,0,a).attr(h)
};AmCharts.text=function(d,c,f,e,b){var a=d.text(c,f,e).attr(b);
if(!AmCharts.isNN&&AmCharts.IEversion<9){a.translate(0+","+3)
}if(window.opera){a.translate(0+","+(-2))
}return a};AmCharts.polygon=function(c,o,l,a,b,d,f,g,n){if(typeof(b)=="object"){b=b[0]
}if(d==undefined||d==0){d=1
}if(f==undefined){f="#000000"
}if(g==undefined){g=0
}if(n==undefined){n=270
}var m=AmCharts.generateGradient(a,n);
var k={fill:String(m),stroke:f,"fill-opacity":b,"stroke-width":d,"stroke-opacity":g};
var j=AmCharts.ddd;var h=["M",Math.round(o[0])+j,Math.round(l[0])+j];
for(var e=1;e<o.length;
e++){h.push("L");h.push(Math.round(o[e])+j);
h.push(Math.round(l[e])+j)
}h.push("Z");return c.path(h).attr(k)
};AmCharts.rect=function(c,o,i,a,b,d,f,g,e,n){if(d==undefined||d==0){d=1
}if(f==undefined){f="#000000"
}if(g==undefined){g=0
}if(e==undefined){e=0
}if(n==undefined){n=270
}if(typeof(b)=="object"){b=b[0]
}if(b==undefined){b=0
}o=Math.round(o);i=Math.round(i);
var m=0;var k=0;if(o<0){o=Math.abs(o);
m=-o}if(i<0){i=Math.abs(i);
k=-i}m+=AmCharts.ddd;
k+=AmCharts.ddd;var l=AmCharts.generateGradient(a,n);
if(!l){l="#FFFFFF"}var j={fill:String(l),stroke:f,"fill-opacity":b,"stroke-width":d,"stroke-opacity":g};
return c.rect(m,k,o,i,e).attr(j)
};AmCharts.triangle=function(a,i,j,d,c,b,e,f){if(b==undefined||b==0){b=1
}if(e==undefined){e="#000000"
}if(f==undefined){f=0
}var h={fill:d,stroke:e,"fill-opacity":c,"stroke-width":b,"stroke-opacity":f};
var k=["M",-i/2,i/2,"L",0,-i/2,"L",i/2,i/2,"Z",-i/2,i/2];
var g=a.path(k).attr(h);
g.attr({rotation:j});
return g};AmCharts.line=function(a,o,m,e,d,n,b,k){var l="";
if(b==1){l=". "}if(b>1){l="- "
}var j={stroke:e,"stroke-dasharray":l,"stroke-opacity":d,"stroke-width":n};
var c="L";var h=AmCharts.ddd;
var g=["M",Math.round(o[0])+h,Math.round(m[0])+h];
for(var f=1;f<o.length;
f++){g.push(c);g.push(Math.round(o[f])+h);
g.push(Math.round(m[f])+h)
}return a.path(g).attr(j)
};AmCharts.wedge=function(s,p,n,C,l,f,j,k,E,m){var H=Math.PI/180;
var B=(j/f)*k;if(l<=-359.99){l=-359.99
}var t=p+Math.cos(C/180*Math.PI)*k;
var o=n+Math.sin(-C/180*Math.PI)*B;
var F=p+Math.cos(C/180*Math.PI)*f;
var D=n+Math.sin(-C/180*Math.PI)*j;
var d=p+Math.cos((C+l)/180*Math.PI)*f;
var b=n+Math.sin((-C-l)/180*Math.PI)*j;
var u=p+Math.cos((C+l)/180*Math.PI)*k;
var r=n+Math.sin((-C-l)/180*Math.PI)*B;
var z=AmCharts.adjustLuminosity(m.fill,-0.2);
var e=m["fill-opacity"];
var a={fill:z,"fill-opacity":e,stroke:z,"stroke-width":0.000001,"stroke-opacity":0.00001};
var v=0;var I=1;if(Math.abs(l)>180){v=1
}var A=s.set();if(E>0){if(k>0){var G=s.path(["M",t,o+E,"L",F,D+E,"A",f,j,0,v,I,d,b+E,"L",u,r+E,"A",k,B,0,v,0,t,o+E,"z"]).attr(a)
}else{var G=s.path(["M",t,o+E,"L",F,D+E,"A",f,j,0,v,I,d,b+E,"L",u,r+E,"Z"]).attr(a)
}A.push(G);var i=s.path(["M",t,o,"L",t,o+E,"L",F,D+E,"L",F,D,"L",t,o,"z"]).attr(a);
var g=s.path(["M",d,b,"L",d,b+E,"L",u,r+E,"L",u,r,"L",d,b,"z"]).attr(a);
A.push(i);A.push(g)}if(m.gradient){m.fill=null
}if(k>0){var q=s.path(["M",t,o,"L",F,D,"A",f,j,0,v,I,d,b,"L",u,r,"A",k,B,0,v,0,t,o,"Z"]).attr(m)
}else{var q=s.path(["M",t,o,"L",F,D,"A",f,j,0,v,I,d,b,"L",u,r,"Z"]).attr(m)
}A.push(q);return A};
AmCharts.adjustLuminosity=function(e,b){var d=Raphael.rgb2hsb(e);
var a=d.toString().split(",");
var c=a[2];c=Number(c.substr(0,c.length-1));
c=c+c*b;return(a[0]+","+a[1]+","+c+")")
};AmCharts.putSetToFront=function(b){for(var a=b.length-1;
a<=0;a++){b[a].toFront()
}},AmCharts.putSetToBack=function(b){for(var a=0;
a<b.length-1;a++){b[a].toBack()
}};AmCharts.AmPieChart=AmCharts.Class({inherits:AmCharts.AmChart,construct:function(){var a=this;
a.createEvents("rollOverSlice","rollOutSlice","clickSlice","pullOutSlice","pullInSlice");
AmCharts.AmPieChart.base.construct.call(a);
a.colors=["#FF0F00","#FF6600","#FF9E01","#FCD202","#F8FF01","#B0DE09","#04D215","#0D8ECF","#0D52D1","#2A0CD0","#8A0CCF","#CD0D74","#754DEB","#DDDDDD","#999999","#333333","#000000","#57032A","#CA9726","#990000","#4B0C25"];
a.pieAlpha=1;a.pieBaseColor;
a.pieBrightnessStep=30;
a.groupPercent=0;a.groupedTitle="Other";
a.groupedPulled=false;
a.groupedAlpha=1;a.marginLeft=0;
a.marginTop=10;a.marginBottom=10;
a.marginRight=0;a.minRadius=10;
a.hoverAlpha=1;a.depth3D=0;
a.startAngle=90;a.innerRadius=0;
a.angle=0;a.outlineColor="#FFFFFF";
a.outlineAlpha=0;a.outlineThickness=1;
a.startRadius="500%";
a.startAlpha=0;a.startDuration=1;
a.startEffect="bounce";
a.sequencedAnimation=false;
a.pullOutRadius="20%";
a.pullOutDuration=1;a.pullOutEffect="bounce";
a.pullOutOnlyOne=false;
a.pullOnHover=false;a.labelsEnabled=true;
a.labelRadius=30;a.labelTickColor="#000000";
a.labelTickAlpha=0.2;
a.labelText="[[title]]: [[percents]]%";
a.hideLabelsPercent=0;
a.balloonText="[[title]]: [[percents]]% ([[value]])\n[[description]]";
a.dataProvider;a.urlTarget="_self";
a.previousScale=1},initChart:function(){var a=this;
AmCharts.AmPieChart.base.initChart.call(a);
if(a.dataChanged){a.parseData();
a.dispatchDataUpdated=true;
a.dataChanged=false;if(a.legend){a.legend.setData(a.chartData)
}}a.drawChart()},handleLegendEvent:function(b){var e=this;
var a=b.type;var d=b.dataItem;
if(d){var c=d.hidden;
switch(a){case"clickMarker":if(!c){e.clickSlice(d)
}break;case"clickLabel":if(!c){e.clickSlice(d)
}break;case"rollOverItem":if(!c){e.rollOverSlice(d,false)
}break;case"rollOutItem":if(!c){e.rollOutSlice(d)
}break;case"hideItem":e.hideSlice(d);
break;case"showItem":e.showSlice(d);
break}}},invalidateVisibility:function(){var b=this;
b.recalculatePercents();
b.drawChart();var a=b.legend;
if(a){a.invalidateSize()
}},drawChart:function(){var f=this;
AmCharts.AmPieChart.base.drawChart.call(f);
var p=f.chartData;if(p){if(p.length>0){var n=f.updateWidth();
f.realWidth=n;var e=f.updateHeight();
f.realHeight=e;var b=AmCharts.toCoordinate;
var m=b(f.marginLeft,n);
var P=b(f.marginRight,n);
var D=b(f.marginTop,e);
var A=b(f.marginBottom,e);
f.chartDataLabels=[];
f.ticks=[];var O;var N;
var G;var v=AmCharts.toNumber(f.labelRadius);
var k=f.measureMaxLabel();
if(!f.labelText||!f.labelsEnabled){k=0;
v=0}if(f.pieX==undefined){O=(n-m-P)/2+m
}else{O=b(f.pieX,f.realWidth)
}if(f.pieY==undefined){N=(e-D-A)/2+D
}else{N=b(f.pieY,e)}G=b(f.radius,n,e);
f.pullOutRadiusReal=AmCharts.toCoordinate(f.pullOutRadius,G);
if(!G){var d;if(v>=0){d=n-m-P-k*2
}else{d=n-m-P}var H=e-D-A;
G=Math.min(d,H);if(H<d){G=G/(1-f.angle/90);
if(G>d){G=d}}f.pullOutRadiusReal=AmCharts.toCoordinate(f.pullOutRadius,G);
if(v>=0){G-=(v+f.pullOutRadiusReal)*1.8
}else{G-=f.pullOutRadiusReal*1.8
}G=G/2}if(G<f.minRadius){G=f.minRadius
}f.pullOutRadiusReal=b(f.pullOutRadius,G);
var a=b(f.innerRadius,G);
if(a>=G){a=G-1}var l=AmCharts.fitToBounds(f.startAngle,0,360);
if(f.depth3D>0){if(l>=270){l=270
}else{l=90}}var z=G-G*f.angle/90;
for(var M=0;M<p.length;
M++){var c=p[M];if(c.hidden!=true&&c.percents>0){var u=-c.percents*360/100;
var r=Math.cos((l+u/2)/180*Math.PI);
var q=Math.sin((-l-u/2)/180*Math.PI)*(z/G);
var s;if(c.url){s="pointer"
}else{s=""}var E={fill:c.color,"fill-opacity":f.startAlpha,stroke:f.outlineColor,"stroke-opacity":f.outlineAlpha,"stroke-width":f.outlineThickness,"stroke-linecap":"round",cursor:s};
var y=O;var o=N;if(f.chartCreated){E["fill-opacity"]=c.alpha
}var C=AmCharts.wedge(f.container,y,o,l,u,G,z,a,f.depth3D,E);
p[M].wedge=C;if((l<=90&&l>=0)||(l<=360&&l>270)){AmCharts.putSetToFront(C)
}else{if((l<=270&&l>180)||(l<=180&&l>90)){AmCharts.putSetToBack(C)
}}c.ix=r;c.iy=q;c.wedge=C;
c.index=M;if(f.labelsEnabled&&f.labelText&&c.percents>=f.hideLabelsPercent){var x=l+u/2;
if(x<=0){x=x+360}var L=O+r*(G+v);
var J=N+q*(G+v);var I;
var g=0;if(v>=0){var B;
if(x<=90&&x>=0){B=0;I="start";
g=8}else{if(x<=360&&x>270){B=1;
I="start";g=8}else{if(x<=270&&x>180){B=2;
I="end";g=-8}else{if((x<=180&&x>90)){B=3;
I="end";g=-8}}}}c.labelQuarter=B
}else{I="middle"}var w=AmCharts.formatString(f.labelText,c,f.numberFormatter,f.percentFormatter);
var h=AmCharts.text(f.container,L+g*1.5,J,w,{fill:f.color,"text-anchor":I,"font-family":f.fontFamily,"font-size":f.fontSize});
var F=setTimeout(function(){f.showLabels.call(f)
},f.startDuration*1000);
f.timeOuts.push(F);if(f.touchEventsEnabled){C.touchend(function(){handleTouchEnd(f.chartData[this.index])
});C.touchstart(function(i){handleTouchStart(f.chartData[this.index])
})}C.push(h);c.labelObject=h;
f.chartDataLabels[M]=h;
h.cornerx=L;h.cornery=J;
h.cornerx2=L+g}for(var K=0;
K<C.length;K++){C[K].index=M
}C.hover(function(){f.rollOverSlice(f.chartData[this.index],true)
},function(){f.rollOutSlice(f.chartData[this.index])
}).click(function(){f.clickSlice(f.chartData[this.index])
});f.set.push(C);if(c.alpha==0){C.hide()
}l-=c.percents*360/100;
if(l<=0){l=l+360}}}if(v>0){f.arrangeLabels()
}for(var M=0;M<f.chartDataLabels.length;
M++){if(f.chartDataLabels[M]){f.chartDataLabels[M].toFront()
}}f.pieXReal=O;f.pieYReal=N;
f.radiusReal=G;f.innerRadiusReal=a;
if(v>0){f.drawTicks()
}var f=this;if(f.chartCreated){f.pullSlices(true)
}else{var F=setTimeout(function(){f.pullSlices.call(f)
},f.startDuration*1200);
f.timeOuts.push(F)}if(!f.chartCreated){f.startSlices()
}f.bringLabelsToFront();
f.chartCreated=true;f.dispatchDataUpdatedEvent()
}}if(f.bgImg){f.bgImg.toBack()
}if(f.background){f.background.toBack()
}f.drb()},drawTicks:function(){var f=this;
for(var d=0;d<f.chartData.length;
d++){if(f.chartDataLabels[d]){var g=f.chartData[d];
var c=g.ix;var b=g.iy;
var j=f.chartDataLabels[d];
var k=j.cornerx;var a=j.cornerx2;
var h=j.cornery;var e=f.container.path(["M",f.pieXReal+c*f.radiusReal,f.pieYReal+b*f.radiusReal,"L",k,h,"L",a,h]).attr({stroke:f.labelTickColor,"stroke-opacity":f.labelTickAlpha,"stroke-width":1,"stroke-linecap":"round"});
g.wedge.push(e);if(!f.chartCreated){g.wedge.hide()
}f.ticks[d]=e}}},arrangeLabels:function(){var f=this;
var g;var k=0;var a=0;
var c=f.chartData;var h=c.length;
var b=f.chartDataLabels;
var j;for(var d=h-1;d>=0;
d--){j=c[d];if(j.labelQuarter==0&&!j.hidden&&b[d]){var e=j.index;
f.checkOverlapping(e,0,true,0)
}}g=NaN;for(d=0;d<h;d++){j=c[d];
if(j.labelQuarter==1&&!j.hidden&&b[d]){var e=j.index;
f.checkOverlapping(e,1,false,0)
}}g=NaN;for(d=h-1;d>=0;
d--){j=c[d];if(j.labelQuarter==2&&!j.hidden&&b[d]){var e=j.index;
f.checkOverlapping(e,2,true,0)
}}g=NaN;for(d=0;d<h;d++){j=c[d];
if(j.labelQuarter==3&&!j.hidden&&b[d]){var e=j.index;
f.checkOverlapping(e,3,false,0)
}}},checkOverlapping:function(h,c,k,g){var f=this;
var a;var e;var j;var d=f.chartData;
var b=f.chartDataLabels;
if(k==true){for(e=h+1;
e<d.length;e++){j=d[e];
if(j.labelQuarter==c&&!j.hidden&&b[e]){if(AmCharts.hitTest(b[h].getBBox(),b[e].getBBox())==true){a=true
}}}}else{for(e=h-1;e>=0;
e--){j=d[e];if(j.labelQuarter==c&&!j.hidden&&b[e]){if(AmCharts.hitTest(b[h].getBBox(),b[e].getBBox())==true){a=true
}}}}var l=b[h].getBBox();
b[h].cornery=l.y+=l.height/2;
if(a==true&&g<100){j=d[h];
b[h].translate(0+","+(j.iy*3));
f.checkOverlapping(h,c,k,g+1)
}},startSlices:function(){var d=this;
var a=d.startDuration/d.chartData.length*500;
for(var c=0;c<d.chartData.length;
c++){if(d.startDuration>0&&d.sequencedAnimation){var b=setTimeout(function(){d.startSequenced.call(d)
},a*c);d.timeOuts.push(b)
}else{d.startSlice(d.chartData[c])
}}},pullSlices:function(a){var d=this;
var c=d.chartData;for(var b=0;
b<c.length;b++){if(c[b].pulled){d.pullSlice(c[b],1,a)
}}},startSequenced:function(){var d=this;
var b=d.chartData;for(var a=0;
a<b.length;a++){if(!b[a].started){var c=d.chartData[a];
d.startSlice(c);break
}}},startSlice:function(c){var d=this;
c.started=true;var a=c.wedge;
if(a){if(c.alpha>0){a.show()
}var b=AmCharts.toCoordinate(d.startRadius,d.radiusReal);
a.translate((c.ix*b)+","+(c.iy*b));
a.animate({"fill-opacity":c.alpha,translation:((-c.ix*b)+","+(-c.iy*b))},d.startDuration*1000,d.startEffect)
}},showLabels:function(){var f=this;
var d=f.chartData;for(var c=0;
c<d.length;c++){var e=d[c];
if(e.alpha>0){var a=f.chartDataLabels[c];
if(a){a.show()}var b=f.ticks[c];
if(b){b.show()}}}},showSlice:function(a){var b=this;
if(isNaN(a)){a.hidden=false
}else{b.chartData[a].hidden=false
}b.hideBalloon();b.invalidateVisibility()
},hideSlice:function(a){var b=this;
if(isNaN(a)){a.hidden=true
}else{b.chartData[a].hidden=true
}b.hideBalloon();b.invalidateVisibility()
},rollOverSlice:function(d,a){var c=this;
if(!isNaN(d)){d=c.chartData[d]
}clearTimeout(c.hoverInt);
if(c.pullOnHover){c.pullSlice(d,1)
}var e=c.innerRadiusReal+(c.radiusReal-c.innerRadiusReal)/2;
if(d.pulled){e+=c.pullOutRadiusReal
}if(c.hoverAlpha<1){d.wedge.attr({"fill-opacity":c.hoverAlpha})
}var g=d.ix*e+c.pieXReal;
var f=d.iy*e+c.pieYReal;
var i=AmCharts.formatString(c.balloonText,d,c.numberFormatter,c.percentFormatter);
var b=AmCharts.adjustLuminosity(d.color,-0.15);
c.showBalloon(i,b,a,g,f);
var h={type:"rollOverSlice",dataItem:d};
c.fire(h.type,h)},rollOutSlice:function(b){var c=this;
if(!isNaN(b)){b=c.chartData[b]
}b.wedge.attr({"fill-opacity":b.alpha});
c.hideBalloon();var a={type:"rollOutSlice",dataItem:b};
c.fire(a.type,a)},clickSlice:function(d){var e=this;
if(!isNaN(d)){d=e.chartData[d]
}e.hideBalloon();if(d.pulled){e.pullSlice(d,-1)
}else{e.pullSlice(d,1)
}var c=d.url;var b=e.urlTarget;
if(c){if(b=="_self"||!b){window.location.href=c
}else{window.open(c)}}var a={type:"clickSlice",dataItem:d};
e.fire(a.type,a)},pullSlice:function(f,c,b){var h=this;
var g=f.ix;var e=f.iy;
var d=h.pullOutDuration*1000;
if(b===true){d=0}f.wedge.animate({translation:(c*g*h.pullOutRadiusReal)+","+(c*e*h.pullOutRadiusReal)},d,h.pullOutEffect);
if(c==1){f.pulled=true;
if(h.pullOutOnlyOne){h.pullInAll(f.index)
}var a={type:"pullOutSlice",dataItem:f};
h.fire(a.type,a)}else{f.pulled=false;
var a={type:"pullInSlice",dataItem:f};
h.fire(a.type,a)}},pullInAll:function(b){var d=this;
var c=d.chartData;for(var a=0;
a<d.chartData.length;
a++){if(a!=b){if(c[a].pulled){d.pullSlice(c[a],-1)
}}}},pullOutAll:function(c){var d=this;
var b=d.chartData;for(var a=0;
a<b.length;a++){if(!b[a].pulled){d.pullSlice(b[a],1)
}}},parseData:function(){var k=this;
var h=[];k.chartData=h;
var g=k.dataProvider;
if(g!=undefined){var c=g.length;
var l=0;for(var j=0;j<c;
j++){var b={};var a=g[j];
b.dataContext=a;b.value=Number(a[k.valueField]);
var m=a[k.titleField];
if(!m){m=""}b.title=m;
b.pulled=AmCharts.toBoolean(a[k.pulledField],false);
var p=a[k.descriptionField];
if(!p){p=""}b.description=p;
b.url=a[k.urlField];b.visibleInLegend=AmCharts.toBoolean(a[k.pulledField],true);
var f=a[k.alphaField];
if(f!=undefined){b.alpha=Number(f)
}else{b.alpha=k.pieAlpha
}var e=a[k.colorField];
if(e!=undefined){b.color=AmCharts.toColor(e)
}l+=b.value;b.hidden=false;
h[j]=b}var o=0;for(var j=0;
j<c;j++){var b=h[j];b.percents=b.value/l*100;
if(b.percents<k.groupPercent){o++
}}if(o>1){k.groupValue=0;
k.removeSmallSlices();
var n=k.groupValue;var d=k.groupValue/l*100;
h.push({title:k.groupedTitle,value:n,percents:d,pulled:k.groupedPulled,color:k.groupedColor,url:k.groupedUrl,description:k.groupedDescription,alpha:k.groupedAlpha})
}for(var j=0;j<h.length;
j++){var e;if(k.pieBaseColor){e=AmCharts.adjustLuminosity(k.pieBaseColor,j*k.pieBrightnessStep/100)
}else{e=k.colors[j];if(e==undefined){e=AmCharts.randomColor()
}}if(h[j].color==undefined){h[j].color=e
}}k.recalculatePercents()
}},recalculatePercents:function(){var e=this;
var c=e.chartData;var b=0;
for(var a=0;a<c.length;
a++){var d=c[a];if(!d.hidden&&d.value>0){b+=d.value
}}for(a=0;a<c.length;
a++){d=e.chartData[a];
if(!d.hidden&&d.value>0){d.percents=d.value*100/b
}else{d.percents=0}}},handleTouchStart:function(b,a){var c=this;
AmCharts.AmPieChart.base.handleTouchStart.call(c);
if(!b.pulled){c.rolledOveredSlice=b;
c.pullTimeOut=setTimeout(function(){c.padRollOver.call(c)
},100)}else{c.rolledOveredSlice=undefined;
c.hideBalloon()}},padRollOver:function(){var a=this;
a.rollOverSlice(a.rolledOveredSlice,false)
},handleTouchEnd:function(a){var b=this;
AmCharts.AmPieChart.base.handleTouchEnd.call(b);
if(a.pulled){b.pullSlice(a,-1)
}else{b.pullSlice(a,1)
}},removeSmallSlices:function(){var c=this;
var b=c.chartData;for(var a=b.length-1;
a>=0;a--){if(b[a].percents<c.groupPercent){c.groupValue+=b[a].value;
b.splice(a,1)}}},measureMaxLabel:function(){var h=this;
var e=h.chartData;var d=0;
for(var c=0;c<e.length;
c++){var g=e[c];var f=AmCharts.formatString(h.labelText,g,h.numberFormatter,h.percentFormatter);
var a=AmCharts.text(h.container,0,0,f,{fill:h.color,"font-family":h.fontFamily,"font-size":h.fontSize});
var b=a.getBBox().width;
if(b>d){d=b}a.remove()
}return d}});AmCharts.AmXYChart=AmCharts.Class({inherits:AmCharts.AmRectangularChart,construct:function(){var a=this;
AmCharts.AmXYChart.base.construct.call(a);
a.createEvents("zoomed");
a.xAxes;a.yAxes;a.scrollbarVertical;
a.scrollbarHorizontal;
a.maxZoomFactor=20;a.skipFix;
a.chartType="xy"},initChart:function(){var a=this;
AmCharts.AmXYChart.base.initChart.call(a);
if(a.dataChanged){a.updateData();
a.dataChanged=false;a.dispatchDataUpdated=true
}a.updateScrollbar=true;
a.drawChart()},createValueAxes:function(){var g=this;
var h=new Array();var d=new Array();
g.xAxes=h;g.yAxes=d;var b=g.valueAxes;
for(var c=0;c<b.length;
c++){var j=b[c];var e=j.position;
if(e=="top"||e=="bottom"){j.rotate=true
}j.setOrientation(j.rotate);
var a=j.orientation;if(a=="vertical"){d.push(j)
}if(a=="horizontal"){h.push(j)
}}if(d.length==0){j=new AmCharts.ValueAxis();
j.rotate=false;j.setOrientation(false);
b.push(j);d.push(j)}if(h.length==0){j=new AmCharts.ValueAxis();
j.rotate=true;j.setOrientation(true);
b.push(j);h.push(j)}for(c=0;
c<b.length;c++){g.processValueAxis(b[c],c)
}var f=g.graphs;for(c=0;
c<f.length;c++){g.processGraph(f[c],c)
}},drawChart:function(){var b=this;
AmCharts.AmXYChart.base.drawChart.call(b);
var a=b.chartData;if(a){if(a.length>0){if(b.chartScrollbar){b.updateScrollbars();
b.scrollbarHorizontal.draw();
b.scrollbarVertical.draw()
}b.zoomChart()}else{b.cleanChart()
}}b.bringLabelsToFront();
b.chartCreated=true;b.dispatchDataUpdatedEvent()
},cleanChart:function(){var a=this;
AmCharts.callMethod("destroy",[a.valueAxes,a.graphs,a.scrollbarVertical,a.scrollbarHorizontal,a.chartCursor])
},zoomChart:function(){var a=this;
a.toggleZoomOutButton();
if(!a.skipFix){a.fixMinMax()
}else{a.skipFix=false
}a.zoomObjects(a.valueAxes);
a.zoomObjects(a.graphs);
a.dispatchAxisZoom();
a.updateDepths()},toggleZoomOutButton:function(){var a=this;
if(a.heightMultiplyer==1&&a.widthMultiplyer==1){a.hideZoomOutButton()
}else{a.showZoomOutButton()
}},dispatchAxisZoom:function(){var g=this;
var d=g.valueAxes;for(var e=0;
e<d.length;e++){var f=d[e];
if(!isNaN(f.min)&&!isNaN(f.max)){var c;
var a;if(f.orientation=="vertical"){c=f.coordinateToValue(-g.verticalPosition);
a=f.coordinateToValue(-g.verticalPosition+g.plotAreaHeight)
}else{c=f.coordinateToValue(-g.horizontalPosition);
a=f.coordinateToValue(-g.horizontalPosition+g.plotAreaWidth)
}if(!isNaN(c)&&!isNaN(a)){if(c>a){var b=a;
a=c;c=b}f.dispatchZoomEvent(c,a)
}}}},zoomObjects:function(c){var e=this;
var b=c.length;for(var a=0;
a<b;a++){var d=c[a];e.updateObjectSize(d);
d.zoom(0,e.chartData.length-1)
}},updateData:function(){var h=this;
h.parseData();var c=h.chartData;
var f=c.length-1;var k=h.graphs;
var d=h.dataProvider;
for(var e=0;e<k.length;
e++){var m=k[e];m.data=c;
m.zoom(0,f);var n=m.valueField;
var g=0;if(n){for(var b=0;
b<d.length;b++){var l=d[b][n];
if(l>g){g=l}}}m.maxValue=g
}h.skipFix=true;var a=h.chartCursor;
if(a){a.updateData();
a.type="crosshair";a.valueBalloonsEnabled=false
}},zoomOut:function(){var a=this;
a.horizontalPosition=0;
a.verticalPosition=0;
a.widthMultiplyer=1;a.heightMultiplyer=1;
a.zoomChart();a.zoomScrollbars()
},updateDepths:function(){var g=this;
var k=g.container.rect(0,0,10,10);
var d=g.chartCursor;if(d){d.set.insertBefore(k)
}var h=g.graphs;for(var f=0;
f<h.length;f++){var l=h[f];
if(l.allBullets){for(var e=0;
e<l.allBullets.length;
e++){l.allBullets[e].insertBefore(k);
l.setPositiveClipRect(l.allBullets[e])
}}if(l.positiveObjectsToClip){for(var e=0;
e<l.positiveObjectsToClip.length;
e++){l.setPositiveClipRect(l.positiveObjectsToClip[e])
}}var c=l.objectsToAddListeners;
if(c){for(var e=0;e<c.length;
e++){l.addClickListeners(c[e]);
l.addHoverListeners(c[e])
}}}var m=g.zoomOutButtonSet;
if(m){m.insertBefore(k)
}k.remove();var b=g.bgImg;
if(b){b.toBack()}var a=g.background;
if(a){a.toBack()}g.drb()
},processValueAxis:function(b){var a=this;
b.chart=this;if(b.orientation=="horizontal"){b.minMaxField="x"
}else{b.minMaxField="y"
}b.minTemp=NaN;b.maxTemp=NaN;
a.listenTo(b,"axisSelfZoomed",a.handleAxisSelfZoom)
},processGraph:function(a){var b=this;
if(!a.xAxis){a.xAxis=b.xAxes[0]
}if(!a.yAxis){a.yAxis=b.yAxes[0]
}},parseData:function(){var m=this;
AmCharts.AmXYChart.base.parseData.call(m);
m.chartData=[];var g=m.dataProvider;
var d=m.valueAxes;var n=m.graphs;
for(var l=0;l<g.length;
l++){var o={};o.axes={};
o.x={};o.y={};var f=g[l];
for(var h=0;h<d.length;
h++){var c=d[h].id;o.axes[c]={};
o.axes[c].graphs={};for(var e=0;
e<n.length;e++){var q=n[e];
var a=q.id;if(q.xAxis.id==c||q.yAxis.id==c){var r={};
r.serialDataItem=o;r.index=l;
var p={};var b=Number(f[q.valueField]);
if(!isNaN(b)){p.value=b
}var b=Number(f[q.xField]);
if(!isNaN(b)){p.x=b}var b=Number(f[q.yField]);
if(!isNaN(b)){p.y=b}r.values=p;
m.processFields(q,r,f);
r.category=String(o.category);
r.serialDataItem=o;r.graphTitle=q.title;
o.axes[c].graphs[a]=r
}}}m.chartData[l]=o}},addChartScrollbar:function(c){var e=this;
AmCharts.callMethod("destroy",[e.chartScrollbar,e.scrollbarHorizontal,e.scrollbarVertical]);
if(c){var d=new AmCharts.SimpleChartScrollbar();
var b=new AmCharts.SimpleChartScrollbar();
d.skipEvent=true;b.skipEvent=true;
d.chart=this;b.chart=this;
e.listenTo(d,"zoomed",e.handleVSBZoom);
e.listenTo(b,"zoomed",e.handleHSBZoom);
var a=["backgroundColor","backgroundAlpha","selectedBackgroundColor","selectedBackgroundAlpha","scrollDuration","resizeEnabled","hideResizeGrips","scrollbarHeight","updateOnReleaseOnly"];
AmCharts.copyProperties(c,d,a);
AmCharts.copyProperties(c,b,a);
d.rotate=true;b.rotate=false;
e.scrollbarHeight=c.scrollbarHeight;
e.scrollbarHorizontal=b;
e.scrollbarVertical=d;
e.chartScrollbar=c}},fixMinMax:function(){var d=this;
var a=d.valueAxes;for(var b=0;
b<a.length;b++){var c=a[b];
if(c.logarithmic==true){if(!isNaN(c.minReal)){c.minTemp=c.minReal
}}else{if(!isNaN(c.min)){c.minTemp=c.min
}}if(!isNaN(c.max)){c.maxTemp=c.max
}}d.skipFix=false},updateMargins:function(){var c=this;
AmCharts.AmXYChart.base.updateMargins.call(c);
var b=c.scrollbarVertical;
if(b){c.getScrollbarPosition(b,true,c.yAxes[0].position);
c.adjustMargins(b,true)
}var a=c.scrollbarHorizontal;
if(a){c.getScrollbarPosition(a,false,c.xAxes[0].position);
c.adjustMargins(a,false)
}},updateScrollbars:function(){var a=this;
a.updateChartScrollbar(a.scrollbarVertical,true);
a.updateChartScrollbar(a.scrollbarHorizontal,false)
},zoomScrollbars:function(){var c=this;
var a=c.scrollbarHorizontal;
if(a){a.relativeZoom(c.widthMultiplyer,-c.horizontalPosition/c.widthMultiplyer)
}var b=c.scrollbarVertical;
if(b){b.relativeZoom(c.heightMultiplyer,-c.verticalPosition/c.heightMultiplyer)
}},fitMultiplyer:function(a){var b=this;
if(a>b.maxZoomFactor){a=b.maxZoomFactor
}return a},handleHSBZoom:function(d){var e=this;
var b=e.fitMultiplyer(d.multiplyer);
var c=-d.position*b;var a=-(e.plotAreaWidth*b-e.plotAreaWidth);
if(c<a){c=a}e.widthMultiplyer=b;
e.horizontalPosition=c;
e.zoomChart()},handleVSBZoom:function(d){var e=this;
var c=e.fitMultiplyer(d.multiplyer);
var a=-d.position*c;var b=-(e.plotAreaHeight*c-e.plotAreaHeight);
if(a<b){a=b}e.heightMultiplyer=c;
e.verticalPosition=a;
e.zoomChart()},handleCursorZoom:function(c){var d=this;
var b=(d.widthMultiplyer*d.plotAreaWidth)/c.selectionWidth;
var a=(d.heightMultiplyer*d.plotAreaHeight)/c.selectionHeight;
b=d.fitMultiplyer(b);
a=d.fitMultiplyer(a);
d.horizontalPosition=(d.horizontalPosition-c.selectionX)*b/d.widthMultiplyer;
d.verticalPosition=(d.verticalPosition-c.selectionY)*a/d.heightMultiplyer;
d.widthMultiplyer=b;d.heightMultiplyer=a;
d.zoomChart();d.zoomScrollbars()
},handleAxisSelfZoom:function(a){var e=this;
var i=a.valueAxis;if(i.orientation=="horizontal"){var b=e.fitMultiplyer(a.multiplyer);
var c=-a.position/e.widthMultiplyer*b;
var h=-(e.plotAreaWidth*b-e.plotAreaWidth);
if(c<h){c=h}e.horizontalPosition=c;
e.widthMultiplyer=b;e.zoomChart()
}else{var g=e.fitMultiplyer(a.multiplyer);
var f=-a.position/e.heightMultiplyer*g;
var d=-(e.plotAreaHeight*g-e.plotAreaHeight);
if(f<d){f=d}e.verticalPosition=f;
e.heightMultiplyer=g;
e.zoomChart()}e.zoomScrollbars()
},removeChartScrollbar:function(){var a=this;
AmCharts.callMethod("destroy",[a.scrollbarHorizontal,a.scrollbarVertical]);
a.scrollbarHorizontal=null;
a.scrollbarVertical=null
},handleReleaseOutside:function(a){var b=this;
AmCharts.AmXYChart.base.handleReleaseOutside.call(b,a);
AmCharts.callMethod("handleReleaseOutside",[b.scrollbarHorizontal,b.scrollbarVertical])
}});