((win, doc) => {
  var loader = doc.querySelector('script[src*="cmn_g_header_footer/js/template.js"]');
  var template = doc.createElement('script');
  template.type = 'module';
  if (loader && loader.getAttribute('src')) {
    template.src = loader.getAttribute('src').replace(/template\.js(\?.*)?$/, 'templateBase.js');
  } else {
    template.src = '/-/Media/Ricoh/Common/cmn_g_header_footer/js/templateBase';
  }
  function append() {
    doc.body.appendChild(template);
  }
  if (doc.body) append();
  else doc.addEventListener('DOMContentLoaded', append);
})(window, document);
