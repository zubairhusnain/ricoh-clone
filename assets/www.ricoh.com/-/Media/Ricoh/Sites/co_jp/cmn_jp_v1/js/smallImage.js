/**
 * common.js
 */

;(function($) {
	//===================================== init var
	var $window;

	//===================================== document ready
	$(function() {
		$window = $(window);

		var breakPoint = 640;

		/**
		 * initialize smallImage
		 */
		function initSmallImage(){
			var smallImageClassName = 'smallImage'; //class 名の指定
			var $originsmallImages = $('.' + smallImageClassName);
			var hiddenOriginImageClassName = 'hidden';

			if( $originsmallImages[0] === undefined ){
				return;
			}

			var $triggers = $('.smallImageTrigger');

			var $contentsWrapper = $('body').wrapInner('<div class="largeContentsWrapper"></div>').find('div.largeContentsWrapper');
			var $smallImageWrapper = $('<div class="img_wrapper smallImageWrapper" />').append('<p class="img_btn backBtn"><a class="ico_nml_lf left_lf" href="javascript:void(0)">Back</a></p>');
			var $backBtn = $smallImageWrapper.find('.backBtn');
			var originScrollTop = 0;

			$contentsWrapper.before($smallImageWrapper);
			changeLayout();

			$triggers.each(function(index){
				var $trigger = $(this);

				$trigger.data('triggerIndex', index);

				$trigger.click(function(e){
					var index = $trigger.data('triggerIndex');
					var $originsmallImage = $originsmallImages.eq(index);
					var $smallImage = $originsmallImage.clone(true).css('display', 'block');

					originScrollTop = $window.scrollTop();

					$smallImageWrapper.append($smallImage);
					$contentsWrapper.css('display', 'none');
					$smallImageWrapper.css('display', 'block');

					$("meta[name='viewport']").attr('content', 'width=1200, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes');
					

					$('body, html').scrollTop(0);
				});
			});

			$backBtn.find('a').click(function(e){
				hidesmallImage();
				$smallImageWrapper.find('.' + smallImageClassName).remove();

				$("meta[name='viewport']").attr('content', 'width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no');
				

				e.preventDefault();
				return false;
			});

			$window.resize(function(e){
				changeLayout();
			});

			$window.scroll(function(e){
				var offsetLeft = $(this).scrollLeft();

				$backBtn.css('margin-left', offsetLeft2);
			});

			/**
			 * show small Image
			*/
			function showsmallImage(){
				$contentsWrapper.css('display', 'none');
				$smallImageWrapper.css('display', 'block');
			}

			/**
			 * hide small Image
			*/
			function hidesmallImage(){
				$contentsWrapper.css('display', 'block');
				$('body, html').scrollTop(originScrollTop);
				$smallImageWrapper.css('display', 'none');
			}

			/**
			 *  change layout
			 */
			function changeLayout() {
				if( getViewport().width > breakPoint ){
					$originsmallImages.removeClass(hiddenOriginImageClassName);

					if( $smallImageWrapper.find('.' + smallImageClassName)[0] ){
						hidesmallImage();

					}
				}else{
					$originsmallImages.addClass(hiddenOriginImageClassName);

					if( $smallImageWrapper.find('.' + smallImageClassName)[0] ){
						showsmallImage();

					}
				}
			}
		}
		initSmallImage();
		// initialize smallImage --------------------------
		
		/**
		 *  get viewport
		 */
		function getViewport() {
			var e = window, a = 'inner';
			if (!('innerWidth' in window )) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
		}

		
	});
})(jQuery);
