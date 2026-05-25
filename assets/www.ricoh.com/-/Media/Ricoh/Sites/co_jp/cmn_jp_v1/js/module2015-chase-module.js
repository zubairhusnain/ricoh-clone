$(function() {

/* ===============================================
# テーブルの横スクロール
=============================================== */
$(window).on('load',function(){
    var android2_3 = navigator.userAgent.match(/Android 2.3/);//Androis2.3かどうか判定

    //ここからタッチ操作の本挙動
    $('table.nml_scroll').not('table table').each(function() {
        var $thisDom = $(this);
        var $root = $(this);

        $thisDom.wrap('<div class="nml_scrollAddWrap"></div>');
        if($thisDom.get(0).tagName === 'TABLE'){
            $root = $thisDom.closest('.nml_scrollAddWrap');
        }

        //「結合されたセル」全てにwhite-space：nowrap;を付与(Android2.3で表組みの一部が欠けるバグ対策)
        if(android2_3){
            $thisDom.find('th, td').each(function() {
                var $target = $(this);
                if($target.attr('colspan') || $target.attr('rowspan')){
                  $target.css({'white-space':'nowrap'});
                }
            });
        }

        var $touchContents = $root.find('>table'),//タッチする要素（はみ出している部分を取得したい）
        pageX  = 0,//横位置を取得するために必要
        startX = 0,//起点
        moveX = 0,//差
        posX = 0,　//さっきまでいた位置　現在位置
        moveP = 0,//実際に動かす大きさ
        ratioBar = 0, //Barを動かす際の比率
        ratioChange = 0,//縦横切り替え時の比率計算に使用
        limitR = 0,//要素が左に行き過ぎないように
        $scrollBar = '',
        $scrollBarC = '',
        moveFlag = false,//tableの幅に応じて変化
        createBar = true,
        resizeFlag = false;//bar を出すかどうか

        var moveAction = function(){
            //タッチ開始
            $touchContents.on('touchstart', function(e) {
                pageX= event.changedTouches[0].pageX;
                startX = pageX;
            });

            // 移動中
            $touchContents.on('touchmove', function (e) {
                moveX = event.changedTouches[0].pageX - startX;//起点から今の指の位置との差　どれだけどの方向に動いたか
                if(moveFlag && Math.abs(moveX) > 20){//もしフラグがtrueならば、スクロールをさせない　=　横スライドの判定がしやすくなる。
                    e.preventDefault();
                }
                moveP = moveX + posX;
                 if(moveP> 0) { moveP = 0};//要素が右に行き過ぎないように
                 if(Math.abs(moveP) > limitR) { moveP = -limitR;};//要素が左に行き過ぎないように
                $touchContents.css('margin-left',moveP);
                $scrollBarC.css('left',-(moveP*ratioBar));

                //機種によりスクロールバーが後わずか残ってしまうのを回避
                //移動した距離が移動できる距離-1pxをこえると発動
                if(-(moveP*ratioBar)>(limitR*ratioBar-0.2)){
                    $scrollBarC.css('left',-(moveP*ratioBar-1))//(Math.ceil(-moveP*ratioBar-1)));
                }
            });

            // タッチ終了
            $touchContents.on('touchend', function (e) {
                posX = moveP;
                ratioChange = posX / limitR;
            });
        }

        //スクロールバーを出す+取得+長さ＋比率　作成
        var createScrollBarDom = function(){
            var scrollBarDom = '<div class="scrollBar">';
                scrollBarDom += '<div class="scrollBarC">';
                scrollBarDom += '&nbsp;';
                scrollBarDom += '</div>';
                scrollBarDom += '</div>';
            $root.after(scrollBarDom);//作る
            createBar = false;
        }
        //スクロールバーの取得+長さ＋比率　作成
        var getScrollBarDom = function(){
            $scrollBar = $root.next('.scrollBar'),//取得
            $scrollBarC = $scrollBar.children();//取得
            //バー本体は、整数にするさい、切り上げる。
            $scrollBarC.width(Math.ceil(Math.pow($root.width(),2)/$touchContents.width()));
            ratioBar = $scrollBarC.width() /$root.width();
        }

        var judgeAction = function(){
            limitR = $touchContents.width() - $root.width();
            moveFlag = true;

            if($touchContents.width()>$root.width()){//tableが横長い場合、実行
                if(createBar){
                    createScrollBarDom();
                }
                getScrollBarDom();
                $scrollBar.removeClass('noVisual');
                moveAction();
            }else{
                $root.nextAll('.scrollBar').addClass('noVisual');
                moveFlag = false;
            }
        }
        //tableの幅を測定後、その大きさに大応じて、スクロールバーを出すなどを行い、見た目の準備を行う。
        //見た目作成後、moveActionを実行

        judgeAction();

        //画面のサイズ変更時 3gsの処理能力のため、時間差を設ける。
        var windowRecalculation = function(){
            setTimeout(function(){
                judgeAction();
                setTimeout(function(){
                    if(isNaN(ratioChange)){
                        ratioChange = 0;
                    }
                    moveP = ratioChange * limitR;

                    $touchContents.css('margin-left',moveP);
                    if($scrollBarC){
                    $scrollBarC.css('left', -(moveP*ratioBar));
                    }
                    posX = moveP;
                },1200);
            },800);
        }

        //リサイズ
        $(window).on( 'resize', function(e){
            windowRecalculation();
        });

        //タブクリック
        $('.tab_ttl').find('a').click(function(e){
            windowRecalculation();
        });
        $('.acd_ttl').find('a').click(function(e){
            windowRecalculation();
        });
    });
});


});