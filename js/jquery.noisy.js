(function(f){f.fn.noisy=function(a){function m(a){return(a=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(a))?{r:parseInt(a[1],16),g:parseInt(a[2],16),b:parseInt(a[3],16)}:null}a=f.extend({},f.fn.noisy.defaults,a);"undefined"!==typeof a.color&&(a.randomColors=!1);var g,k,b=!1;try{k=!0,localStorage.setItem("test",""),localStorage.removeItem("test"),b=localStorage.getItem(window.JSON.stringify(a))}catch(q){k=!1}if(b&&!a.disableCache)g=b;else{b=document.createElement("canvas");if(b.getContext){b.width=
b.height=a.size;for(var l=b.getContext("2d"),d=l.createImageData(b.width,b.height),h=Math.round(a.intensity*Math.pow(a.size,2)),n=255*a.opacity;h--;){var e=~~(Math.random()*b.width),c=~~(Math.random()*b.height),e=4*(e+c*d.width);a.randomColors?(c=h%255,a.colorChannels===parseInt(a.colorChannels)?c=h%a.colorChannels:f.isArray(a.colorChannels)&&(c=a.colorChannels[0]+h%(a.colorChannels[1]-a.colorChannels[0])),d.data[e]=c,d.data[e+1]=a.monochrome?c:~~(255*Math.random()),d.data[e+2]=a.monochrome?c:~~(255*
Math.random())):(c=m(a.color),d.data[e]=c.r,d.data[e+1]=c.g,d.data[e+2]=c.b);d.data[e+3]=~~(Math.random()*n)}l.putImageData(d,0,0);g=b.toDataURL("image/png");0!=g.indexOf("data:image/png")&&(g=a.fallback)}else g=a.fallback;if(window.JSON&&k&&!a.disableCache)try{localStorage.setItem(window.JSON.stringify(a),g)}catch(p){console.warn(p.message)}}return this.each(function(){f(this).css("background-image","url('"+g+"'),"+f(this).css("background-image"))})};f.fn.noisy.defaults={intensity:0.9,size:200,opacity:0.08,
fallback:"",monochrome:!1,colorChannels:255,randomColors:!0,disableCache:!1}})(jQuery);