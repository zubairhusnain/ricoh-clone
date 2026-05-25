(function($){
$(function() {
/* ===============================================
# fancyboxを使用したモーダル表示
=============================================== */
$('#contents').find('.newWinModalImg').each(function(){
    var $modalImg = $(this).children('img');
    var hrefString = $modalImg.attr('src');
    $(this).attr('href', hrefString);
});

newWinModal();
$(window).resize(function(){ newWinModal(); });

function newWinModal() {
  var w = $('#contents').width();
  if (w <= 640) {
    newWinModalImg640 ();
  } else {
    newWinModalImg640Else ();
  }
}

function newWinModalImg640 () {
    $('.newWinModalImg').removeClass('newWinModalImg_on');
    $('.newWinModalImg').addClass('newWinModalImg_off');
    $(".newWinModalImg_off").off('click').on('click',function(event){
        event.preventDefault();
        return false;
    });
}
function newWinModalImg640Else () {
    $('.newWinModalImg').removeClass('newWinModalImg_off');
    $('.newWinModalImg').addClass('newWinModalImg_on');
    $(".newWinModalImg_on").fancybox();
}

$(".newWinModalYoutube").fancybox({
    width : '80%',
    height : '80%'
});
});
})(jQuery);