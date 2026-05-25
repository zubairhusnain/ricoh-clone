((win, doc) => {
  function loadTemplateBase() {
    var loader = doc.querySelector('script[src*="cmn_g_header_footer/js/template.js"]');
    var s = doc.createElement('script');
    s.type = 'module';
    s.src = loader && loader.getAttribute('src')
      ? loader.getAttribute('src').replace(/template\.js(\?.*)?$/, 'templateBase.js')
      : '/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_g_header_footer/js/templateBase.js';
    doc.body.appendChild(s);
  }
  if (doc.body) loadTemplateBase();
  else doc.addEventListener('DOMContentLoaded', loadTemplateBase);
})(window, document);
