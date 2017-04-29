function downloadJSAtOnload(){
  urls.map(function(url) {
    var el = document.createElement("script");
    el.src = url;
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
