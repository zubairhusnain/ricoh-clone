$(function () {
  // mv動画のコントロール
  var video = document.querySelector('#video');
  var is_playing = false;
  $(document).ready(function () {
    var video = document.querySelector('#video');
    var is_playing = false;
    $('[data-play-button]').click(function () {
      if (!is_playing) {
        video.pause();
        is_playing = true;
        $('[data-play-button]').toggleClass('play pause');
        $('[data-play-button]').attr('aria-label', 'Play video');
      } else {
        video.play();
        is_playing = false;
        $('[data-play-button]').toggleClass('play pause');
        $('[data-play-button]').attr('aria-label', 'Pause video');
      }
    });
  });

  // ストーリーズ記事データの取得・出力
  var rhAssetRoot = (function () {
    var scripts = document.getElementsByTagName('script');
    for (var i = 0; i < scripts.length; i++) {
      var src = scripts[i].src || '';
      var match = src.match(/^(.*)\/assets\/www\.ricoh\.com\//);
      if (match) {
        return match[1];
      }
    }
    return '';
  })();

$.ajax({
  type: 'GET',
  url: rhAssetRoot + '/assets/www.ricoh.com/-/Media/Ricoh/Sites/com/news/stories/articles/article.json?',
  dataType: 'json'
})
.then(
  // 取得成功時
  function (json) {
    const link_card_link = document.querySelector('.c-linkCard__item.c-linkCard__item--stories .c-linkCard__link');
    const link_card_img = document.querySelector('.c-linkCard__item.c-linkCard__item--stories .c-linkCard__image.index_section_topics_img img');
    const link_card_date = document.querySelector('.c-linkCard__item.c-linkCard__item--stories .c-linkCard__text .index_section_topics_date');
    const link_card_title = document.querySelector('.c-linkCard__item.c-linkCard__item--stories .c-linkCard__text .index_section_topics_title');

    link_card_link.href = json[0][0].url;
    if (json[0][0].blank == "true") {
      link_card_link.target = '_blank';
      link_card_link.className = 'c-linkCard__link c-linkCard__link--blank';
    }
    link_card_img.src = json[0][0].image;
    link_card_img.alt = json[0][0].title;
    link_card_title.textContent = json[0][0].title;

    let date = json[0][0].date.split('.');
    let link_card_date_text =  date[0] + '-' + date[1] + '-' + date[2];
    link_card_date_text = link_card_date_text.replaceAll('-0', '-');
    link_card_date.textContent =  link_card_date_text;

  },
);

const latest_news_tag = document.querySelectorAll('.cp-NewsList .news_sct dl .news_lst_cate .lb_ico');
for (let i = 0; i < latest_news_tag.length; i += 1) {
  if(latest_news_tag[i].textContent == 'info') {
    latest_news_tag[i].textContent = 'Info';
  }
  if(latest_news_tag[i].textContent == 'release') {
    latest_news_tag[i].textContent = 'Release';
  }
}

});
