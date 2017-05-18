function downloadJSAtOnload(){
  scripts.map(function(script) {
    var el = document.createElement("script");
    el.src = script.src;
    el.async = !!script.async;
    el.defer = !!script.defer;
    document.body.appendChild(el);
  })
}
if (window.addEventListener) {
  window.addEventListener("load", downloadJSAtOnload, false);
} else if (window.attachEvent) {
  window.attachEvent("onload", downloadJSAtOnload);
} else {
  window.onload = downloadJSAtOnload;
}
