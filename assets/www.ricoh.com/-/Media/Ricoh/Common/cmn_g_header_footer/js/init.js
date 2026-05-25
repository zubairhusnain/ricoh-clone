((win, doc) => {
  var loader = doc.querySelector('script[src*="cmn_g_header_footer/js/init.js"]');
  var src = loader && loader.getAttribute('src')
    ? loader.getAttribute('src').replace(/init\.js(\?.*)?$/, 'initBase.js')
    : '/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_g_header_footer/js/initBase.js';
  var s = doc.createElement('script');
  s.src = src;
  s.async = false;
  function run() {
    doc.body.appendChild(s);
  }
  if (doc.body) run();
  else doc.addEventListener('DOMContentLoaded', run);
})(window, document);
