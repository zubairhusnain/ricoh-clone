;(function($) {
  var $window;
  $(function() {
    brdCrmbCrohn();
  });
  $(window).load(function() {
  });
})(jQuery);
function brdCrmbCrohn() {
  var $base = $('#id_HeaderBreadcrumbsList');
  var $clone = $('#id_FooterBreadcrumbsList');
  var base_elm = $base.html();
  $clone.append(base_elm);
}
