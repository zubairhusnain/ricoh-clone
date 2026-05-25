/////////////////////////////////////////////////////////////////////////////////////////////////////
//
// fs_ricoh.js
//
// DATE   : 2014-12-04
// DESC   :
// LIB    : jQuery(v1.11.0)
//
/////////////////////////////////////////////////////////////////////////////////////////////////////
;(function($) {
    //===================================== init var
  var $window;

  //===================================== document ready
  $(function() {
    brdCrmbCrohn();
    localNav();
    gnavCurrent();
    accordionAutoOpen();
  });
  $(window).load(function(){
    fadeMainImg();
  });
})(jQuery);

// パンくずコピー
function brdCrmbCrohn() {
  var $base = $('.hd_brd_crmb ul');
  var $clone = $('ul.ft_brd_crmb_lst');
  var base_elm = $base.html()
  $clone.append(base_elm);
}


// localnav判定
function localNav() {
  var this_url =  location.pathname;
  var target_arr = []
      ,localnavtarget_arr = []
      ;
  var $target = $('.ft_lc_nv ul.ft_lc_nv_lst.tileSame')
      ,$localnavtarget = $('.contents_nv_addact ul')
      ;

  var len = $target.find('li').length;

  var this_index;

  if($target.size() > 0){
      for(var i = 0; i < len; i++){
        var temp = $target.find('li').eq(i).find('a').attr('href');
        target_arr.push(temp);

        if(this_url.indexOf(temp) != -1){
          this_index = i;
        }
      }
      if(this_index != -1){
        $target.find('li').eq(this_index).addClass('act');
      }
   }

   //  20170626 localnav_add
  $localnavtarget.each(function(){
      $(this).find('li a').each(function(){
        var temp = $(this).attr('href');

        // temp の index.htmlをトル
        // this_url のindex.htmlをトル
        var pattern = 'index.html';

        if((temp.lastIndexOf(pattern)+pattern.length===temp.length)&&(pattern.length<=temp.length)){
          temp = temp.replace(new RegExp(pattern + "$"), "");
        }
        if((this_url.lastIndexOf(pattern)+pattern.length===this_url.length)&&(pattern.length<=this_url.length)){
          this_url = this_url.replace(new RegExp(pattern + "$"), "");
        }

        if( $(this).hasClass('multiMenu') ){
          if(this_url.indexOf(temp) != -1){
           $(this).addClass('act');
          }
        }else{
          if(this_url === temp){
             $(this).addClass('act');
          }
        }

      });
   });
}


// グローバルナビカレント
function gnavCurrent() {
  var $base = $('#gl_header');
  var current_arr = ['act_solution', 'act_product', 'act_support', 'act_event', 'act_about'];
  var this_nav = $base.attr('class').split(' ');
  var len = current_arr.length;
  for(var i = 0; i < len; i++){
    for(var j = 0; j < this_nav.length; j++){
        if(current_arr[i].indexOf(this_nav[j]) != -1){

          $base.find('.gl_nv_lst li').eq(i).find('a').addClass('act');

        }
    }
  }
}

function accordionAutoOpen(){

		if($("#contents .acd_transition").length <= 0) return;

    var
        arg = new Object
        ,pair=location.search.substring(1).split('&')
        ,addOpenClassName = ''
        ,scrollPosClassName = ''
        ;

    for(var i=0;pair[i];i++) {
        var kv = pair[i].split('=');
        arg[kv[0]]=kv[1];
    }

    for(key in arg){
      if(key === 'openaccordion') addOpenClassName = arg[key];
      if(key === 'scrolltgt')  scrollPosClassName = arg[key];
    }

		var activeClassName = ('open');
		var $targetObj = $('.' + addOpenClassName); //対象クラス名指定
		var $content = $targetObj.parent().find('.accordionDetail');

		if( !$content.hasClass(activeClassName) ){
			$targetObj.addClass(activeClassName);
		}

		$content.not(':animated').slideToggle(0, function(e){
			var $this = $(this);

			$this.addClass(activeClassName);

			if( $this.css('display') === 'block' ){
				alignHeightLocal();
			}else{
				$targetObj.removeClass(activeClassName);
			}
		});

    $("html,body").animate({'scrollTop':$('.' + scrollPosClassName).offset().top});

}

function scrollToWithjQueryObjectLocal( $obj ){
  var offsetTop = $obj.offset().top;
  var slideDurationSP = 400;

  $('body, html').animate(
    { 'scrollTop':  offsetTop}, slideDurationSP
  );
}

/**
   * required jquery.tile.js
   */
function alignHeightLocal() {
  var $tileSameRoots = $('.tileSame');

  if( $tileSameRoots[0] === undefined ){
    return;
  }

  for( var i = 0, l = $tileSameRoots.length; i < l; i++ ){
    var $tileSameRoot = $tileSameRoots.eq(i);
    var $cells = $tileSameRoot.find('[class*="tlSame"]');

    if( $cells[0] === undefined || $tileSameRoot.css('height').replace('px', '') <= 0 || $tileSameRoot.css('display') === 'none' ){
      continue;
    }

    var tiledClassNameArray = [];
    for( var j = 0, jl = $cells.length; j < jl; j++ ){
      if( $cells.eq(j).css('height').replace('px', '') <= 0 || $cells.eq(j).css('display') === 'none' ){
        continue;
      }

      var className = $cells.eq(j).attr('class');
      var startIndex = className.indexOf('tlSame');

      var noPrefixHl = className.substring(startIndex, className.length);

      var endIndex = noPrefixHl.search(/ /);

      var hlClassName;
      if( endIndex !== -1 ){
        hlClassName = noPrefixHl.substring(0, endIndex);
      }else{
        hlClassName = noPrefixHl;
      }

      if( $.inArray(hlClassName, tiledClassNameArray) === -1 ){
        var $groupCells = $tileSameRoot.find('.' + hlClassName);
        var firstOffset = $groupCells.eq(0).offset().top;
        var numberOfColumn = 0;
        for( var k = 0, kl = $groupCells.length; k < kl; k++  ){
          if( firstOffset !== $groupCells.eq(k).offset().top ){
            numberOfColumn = k;
            break;
          }
        }

        $groupCells.tile(numberOfColumn);
        tiledClassNameArray[tiledClassNameArray.length] = hlClassName;
      }
    }
  }
}



//----------------------------------------------------------------------
// console.log
//----------------------------------------------------------------------
function trace(_){
  if (typeof window.console === "undefined") {
       window.console = {}
  }
  if (typeof window.console.log !== "function") {
       window.console.log = function () {}
  }else{
      console.log(_);
  }
}
