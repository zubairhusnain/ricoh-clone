$(function(){

  $(document).ready(function () {
  function ListSet(callback) {
    // .jsonファイルのパス
    var local = '/-/Media/Ricoh/Sites/com/technology/tech/';
    var path = local + 'js/tech.json';
    // .jsonから取得するカテゴリーを取得
    var category = $('.tabList').attr('data-category');

    $.getJSON(path, function (data) {
      $(data.item).each(function () {
        // var fields = this[category].split(',').map(function (field) {
        //   return field.trim();
        // }).join(',');
        var contentHtml = '';
        var page = this.e_url;
        if((page != '') && ((this.P4a != '') || (this.P4b != ''))){

          if(page.indexOf('http') != -1 ) {
            var url = page;
            var window = 'ricoh';
          } else if(page.indexOf('/technology/institute/') != -1 ) {
            var url = page;
            var window = '_self';
          } else {
            var url = '/technology/tech/' + page;
            var window = '_self';
          }

          // HTMLコンテンツを作成
          contentHtml = '<a href="' + url + '" class="c-linkTile__item icon-hidden" data-group="' + this.P4a + ' '+ this.P4b + '" target="' + window + '">' +
          '<div class="c-linkTile__content">' +
            '<div class="c-linkTile__heading">' +
              '<div class="c-text-a c-text-a--thin">' +
                  '<span class="c-text-a__text u-font-bold">' + this.e_title + '</span>' +
              '</div>' +
            '</div>' +
            '<div class="c-text-b">' +
              '<p class="c-text-b__text">' + this.e_lead + '</p>' +
            '</div>' +
          '</div>' +
          '<div class="c-linkTile__image">' +
            '<img src="/-/Media/Ricoh/Sites/com/technology/tech/img/' + this.e_img + '" alt="">' +
          '</div>' + 
          '<img src="/-/Media/Ricoh/Common/cmn_v3/img/svg/arrow-right-primary-color.svg" alt="">' +
          '</a>';
        }
          // .c-linkTile__innerの中にcontentHtmlを追加
          $('#content .c-linkTile__inner').append(contentHtml);
      });

      // データの取得が完了したらコールバックを呼び出す
      if (callback) {
        callback();
      }
    });
  }

  function updateContentDisplay() {
    // .tabListDetail.is-activeのdata-groupを取得
    var activeGroup = $('.tabListDetail.is-active').data('group');

    // [data-group]を非表示
    $('#content [data-group]').hide();

    // activeGroupがあれば、それぞれのgroupを表示
    if (activeGroup) {
      var activeGroups = activeGroup.split(' ').map(function (group) {
        return group.trim();
      });

      $('#content [data-group]').each(function () {
        var elementGroups = $(this).data('group').split(' ').map(function (group) {
          return group.trim();
        });

        var shouldShow = activeGroups.some(function (activeGroup) {
          return elementGroups.includes(activeGroup);
        });

        if (shouldShow) {
          $(this).show();
        }
      });
    }
  }

  // URLのハッシュを取得して初期アクティブタブを設定する関数
  function setInitialTabAndDisplay() {
    var hash = window.location.hash; // 例: "#P3a" を取得
    
    if (hash) {
      // "#" を取り除く（例: "P3a"）
      var targetId = hash.replace('#', '');
      
      // 【修正箇所】IDではなく、data-group属性を使って要素を取得する
      var $targetTab = $('.tabListDetail[data-group="' + targetId + '"]');
      
      // 該当する属性を持つタブが存在するか確認
      if ($targetTab.length > 0) {
        // 全てのタブから is-active を外し、対象のタブに付与する
        $('.tabListDetail').removeClass('is-active');
        $targetTab.addClass('is-active');
      }
    }
    
    // 最終的にコンテンツの表示設定を更新する
    updateContentDisplay();
  }

  // ページ読み込み時に ListSet を実行し、完了後に setInitialTabAndDisplay を呼び出す
  ListSet(setInitialTabAndDisplay);

  // クリックイベントで表示を更新
  $('.tabListDetail').on('click', function () {
    // .tabListDetail の is-active クラスを削除
    $('.tabListDetail').removeClass('is-active');
    // クリックされた要素に is-active クラスを追加
    $(this).addClass('is-active');
    // 表示を更新
    updateContentDisplay();
  });
});

});