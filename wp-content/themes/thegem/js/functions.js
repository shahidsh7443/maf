(function($) {
	$.fn.thegemPreloader = function(callback) {
		$(this).each(function() {
			var $el = $(this);
			if(!$el.prev('.preloader').length) {
				$('<div class="preloader">').insertBefore($el);
			}
			$el.data('thegemPreloader', $('img, iframe', $el).add($el.filter('img, iframe')).length);
			if($el.data('thegemPreloader') == 0) {
				$el.prev('.preloader').remove();
				callback();
				$el.trigger('thegem-preloader-loaded');
				return;
			}
			$('img, iframe', $el).add($el.filter('img, iframe')).each(function() {
				var $obj = $('<img>');
				if($(this).prop('tagName').toLowerCase() == 'iframe') {
					$obj = $(this);
				}
				$obj.attr('src', $(this).attr('src'));
				$obj.on('load error', function() {
					$el.data('thegemPreloader', $el.data('thegemPreloader')-1);
					if($el.data('thegemPreloader') == 0) {
						$el.prev('.preloader').remove();
						callback();
						$el.trigger('thegem-preloader-loaded');
					}
				});
			});
		});
	}
})(jQuery);

(function($) {

	var oWidth=$.fn.width;
	$.fn.width=function(argument) {
		if (arguments.length==0 && this.length==1 && this[0]===window) {
			if (window.gemOptions.innerWidth != -1) {
				return window.gemOptions.innerWidth;
			}
			var width = oWidth.apply(this,arguments);
			window.updateGemInnerSize(width);
			return width;
		}

		return oWidth.apply(this,arguments);
	};

	var $page = $('#page');

	$(window).load(function() {
		var $preloader = $('#page-preloader');
		if ($preloader.length && !$preloader.hasClass('preloader-loaded')) {
			$preloader.addClass('preloader-loaded');
		}
	});

	$('#site-header.animated-header').headerAnimation();

	$.fn.updateTabs = function() {

		jQuery('.gem-tabs', this).each(function(index) {
			var $tabs = $(this);
			$tabs.thegemPreloader(function() {
				$tabs.easyResponsiveTabs({
					type: 'default',
					width: 'auto',
					fit: false,
					activate: function(currentTab, e) {
						var $tab = $(currentTab.target);
						var controls = $tab.attr('aria-controls');
						$tab.closest('.ui-tabs').find('.gem_tab[aria-labelledby="' + controls + '"]').trigger('tab-update');
					}
				});
			});
		});

		jQuery('.gem-tour', this).each(function(index) {
			var $tabs = $(this);
			$tabs.thegemPreloader(function() {
				$tabs.easyResponsiveTabs({
					type: 'vertical',
					width: 'auto',
					fit: false,
					activate: function(currentTab, e) {
						var $tab = $(currentTab.target);
						var controls = $tab.attr('aria-controls');
						$tab.closest('.ui-tabs').find('.gem_tab[aria-labelledby="' + controls + '"]').trigger('tab-update');
					}
				});
			});
		});

	};

	function fullwidth_block_after_update($item) {
		$item.trigger('updateTestimonialsCarousel');
		$item.trigger('updateClientsCarousel');
		$item.trigger('fullwidthUpdate');
	}

	function fullwidth_block_update($item, pageOffset, pagePaddingLeft, pageWidth,skipTrigger) {
	    var $prevElement = $item.prev(),
			extra_padding = 0;
	    if ($prevElement.length == 0 || $prevElement.hasClass('fullwidth-block')) {
	        $prevElement = $item.parent();
			extra_padding = parseInt($prevElement.css('padding-left'));
	    }

	    var offsetKey = window.gemSettings.isRTL ? 'right' : 'left';
	    var cssData = {
	        width: pageWidth
	    };
	    cssData[offsetKey] = pageOffset.left - ($prevElement.length ? $prevElement.offset().left : 0) + parseInt(pagePaddingLeft) - extra_padding;

	    $item.css(cssData);

	    if (!skipTrigger) {
	        fullwidth_block_after_update($item);
	    }
	}

	var inlineFullwidths = [],
		notInlineFullwidths = [];

	$('.fullwidth-block').each(function() {
		var $item = $(this),
			$parents = $item.parents('.vc_row'),
			fullw = {
				isInline: false
			};

		$parents.each(function() {
			if (this.hasAttribute('data-vc-full-width')) {
				fullw.isInline = true;
				return false;
				}
		});

		if (fullw.isInline) {
			inlineFullwidths.push(this);
		} else {
			notInlineFullwidths.push(this);
			}
		});

	function update_fullwidths(inline, init) {
		var $needUpdate = [];

		(inline ? inlineFullwidths : notInlineFullwidths).forEach(function(item) {
			$needUpdate.push(item);
		});

		if ($needUpdate.length > 0) {
			var pageOffset = $page.offset(),
				pagePaddingLeft = $page.css('padding-left'),
				pageWidth = $page.width();

			$needUpdate.forEach(function(item) {
				fullwidth_block_update($(item), pageOffset, pagePaddingLeft, pageWidth);
				});
		}
	}

	if (!window.disableGemSlideshowPreloaderHandle) {
		jQuery('.gem-slideshow').each(function() {
			var $slideshow = $(this);
			$slideshow.thegemPreloader(function() {});
		});
	}

	$(function() {
		$('#gem-icons-loading-hide').remove();
		$('#thegem-preloader-inline-css').remove();

		jQuery('iframe').not('.gem-video-background iframe').each(function() {
			$(this).thegemPreloader(function() {});
		});

		jQuery('.gem-video-background').each(function() {
			var $videoBG = $(this);
			var $videoContainer = $('.gem-video-background-inner', this);
			var ratio = $videoBG.data('aspect-ratio') ? $videoBG.data('aspect-ratio') : '16:9';
			var regexp = /(\d+):(\d+)/;
			var $fullwidth = $videoBG.closest('.fullwidth-block');
			ratio = regexp.exec(ratio);
			if(!ratio || parseInt(ratio[1]) == 0 || parseInt(ratio[2]) == 0) {
				ratio = 16/9;
			} else {
				ratio = parseInt(ratio[1])/parseInt(ratio[2]);
			}

			function gemVideoUpdate()  {
				$videoContainer.removeAttr('style');
				if($videoContainer.width() / $videoContainer.height() > ratio) {
					$videoContainer.css({
						height: ($videoContainer.width() / ratio) + 'px',
						marginTop: -($videoContainer.width() / ratio - $videoBG.height()) / 2 + 'px'
					});
				} else {
					$videoContainer.css({
						width: ($videoContainer.height() * ratio) + 'px',
						marginLeft: -($videoContainer.height() * ratio - $videoBG.width()) / 2 + 'px'
					});
				}
			}

			if ($videoBG.closest('.page-title-block').length > 0) {
				gemVideoUpdate();
			}

			if ($fullwidth.length) {
				$fullwidth.on('fullwidthUpdate', gemVideoUpdate);
			} else {
				$(window).resize(gemVideoUpdate);
				}
			});

		update_fullwidths(false, true);

		if (!window.gemSettings.parallaxDisabled) {
			$('.fullwidth-block').each(function() {
				var $item = $(this),
					mobile_enabled = $item.data('mobile-parallax-enable') || '0';

				if (!window.gemSettings.isTouch || mobile_enabled == '1') {
					if ($item.hasClass('fullwidth-block-parallax-vertical')) {
						$('.fullwidth-block-background', $item).parallaxVertical('50%');
					} else if ($item.hasClass('fullwidth-block-parallax-horizontal')) {
						$('.fullwidth-block-background', $item).parallaxHorizontal();
					}
				} else {
					$('.fullwidth-block-background', $item).css({
						backgroundAttachment: 'scroll'
					});
				}
			});
		}

		$(window).resize(function() {
			update_fullwidths(false, false);
		});

		jQuery('select.gem-combobox, .gem-combobox select, .widget_archive select, .widget_product_categories select, .widget_layered_nav select, .widget_categories select').each(function(index) {
			$(this).combobox();
		});

		jQuery('input.gem-checkbox, .gem-checkbox input').checkbox();

		if (typeof($.fn.ReStable) == "function") {
			jQuery('.gem-table-responsive').each(function(index) {
				$('> table', this).ReStable({
					maxWidth: 768,
					rowHeaders : $(this).hasClass('row-headers')
				});
			});
		}

		jQuery('.fancybox').each(function() {
			$(this).fancybox();
		});

		function init_odometer(el) {
			if (jQuery('.gem-counter-odometer', el).size() == 0)
				return;
			var odometer = jQuery('.gem-counter-odometer', el).get(0);
			var format = jQuery(el).closest('.gem-counter-box').data('number-format');
			format = format ? format : '(ddd).ddd';
			var od = new Odometer({
				el: odometer,
				value: $(odometer).text(),
				format: format
			});
			od.update($(odometer).data('to'));
		}
		window['thegem_init_odometer'] = init_odometer;

		jQuery('.gem-counter').each(function(index) {
			if (jQuery(this).closest('.gem-counter-box').size() > 0 && jQuery(this).closest('.gem-counter-box').hasClass('lazy-loading') && !window.gemSettings.lasyDisabled) {
				jQuery(this).addClass('lazy-loading-item').data('ll-effect', 'action').data('item-delay', '0').data('ll-action-func', 'thegem_init_odometer');
				jQuery('.gem-icon', this).addClass('lazy-loading-item').data('ll-effect', 'fading').data('item-delay', '0');
				jQuery('.gem-counter-text', this).addClass('lazy-loading-item').data('ll-effect', 'fading').data('item-delay', '0');
				return;
			}
			init_odometer(this);
		});

		jQuery('.panel-sidebar-sticky > .sidebar').scSticky();

		jQuery('iframe + .map-locker').each(function() {
			var $locker = $(this);
			$locker.click(function(e) {
				e.preventDefault();
				if($locker.hasClass('disabled')) {
					$locker.prev('iframe').css({ 'pointer-events' : 'none' });
				} else {
					$locker.prev('iframe').css({ 'pointer-events' : 'auto' });
				}
				$locker.toggleClass('disabled');
			});
		});

		$('.primary-navigation a.mega-no-link').closest('li').removeClass('menu-item-active current-menu-item');

		var $anhorsElements = [];
		$('.primary-navigation a, .gem-button, .footer-navigation a, .scroll-top-button, .scroll-to-anchor, .scroll-to-anchor a, .top-area-menu a').each(function(e) {
			var $anhor = $(this);
			var link = $anhor.attr('href');
			if(!link) return ;
			link = link.split('#');
			if($('#'+link[1]).hasClass('vc_tta-panel')) return ;
			if($('#'+link[1]).length) {
				$anhor.closest('li').removeClass('menu-item-active current-menu-item');
				$anhor.closest('li').parents('li').removeClass('menu-item-current');
				$(window).scroll(function() {
					if(!$anhor.closest('li.menu-item').length) return ;
					var correction = 0;
					if(!$('#page').hasClass('vertical-header')) {
						correction = $('#site-header').outerHeight() + $('#site-header').position().top;
					}
					var target_top = $('#'+link[1]).offset().top - correction;
					if(getScrollY() >= target_top && getScrollY() <= target_top + $('#'+link[1]).outerHeight()) {
						$anhor.closest('li').addClass('menu-item-active');
						$anhor.closest('li').parents('li').addClass('menu-item-current');
					} else {
						$anhor.closest('li').removeClass('menu-item-active');
						$anhor.closest('li').parents('li.menu-item-current').each(function() {
							if(!$('.menu-item-active', this).length) {
								$(this).removeClass('menu-item-current');
							}
						});
					}
				});
				$(document).on('update-page-scroller', function(e, elem) {
					var $elem = $(elem);
					if(!$anhor.closest('li.menu-item').length) return ;
					if($elem.is($('#'+link[1])) || $elem.find($('#'+link[1])).length) {
						$anhor.closest('li').addClass('menu-item-active');
						$anhor.closest('li').parents('li').addClass('menu-item-current');
					} else {
						$anhor.closest('li').removeClass('menu-item-active');
						$anhor.closest('li').parents('li.menu-item-current').each(function() {
							if(!$('.menu-item-active', this).length) {
								$(this).removeClass('menu-item-current');
							}
						});
					}
				});
				$anhor.click(function(e) {
					e.preventDefault();
					var correction = 0;
					if($('#site-header.animated-header').length) {
						var shrink = $('#site-header').hasClass('shrink');
						$('#site-header').addClass('scroll-counting');
						$('#site-header').addClass('fixed shrink');
						correction = $('#site-header').outerHeight() + $('#site-header').position().top;
						if(!shrink && $('#top-area').length && !$('#site-header').find('#top-area').length) {
							correction = correction - $('#top-area').outerHeight();
						}
						if(!shrink) {
							$('#site-header').removeClass('fixed shrink');
						}
						setTimeout(function() {
							$('#site-header').removeClass('scroll-counting');
						}, 50);
					}
					var target_top = $('#'+link[1]).offset().top - correction + 1;
					if($('body').hasClass('page-scroller') && $('.page-scroller-nav-pane').is(':visible')) {
						var $block = $('#'+link[1]+'.scroller-block').add($('#'+link[1]).closest('.scroller-block')).eq(0);
						if($block.length) {
							$('.page-scroller-nav-pane .page-scroller-nav-item').eq($('.scroller-block').index($block)).trigger('click');
						}
					} else {
						$('html, body').stop(true, true).animate({scrollTop:target_top}, 1500, 'easeInOutQuint');
					}
				});
				$anhorsElements.push($anhor[0]);
			}
		});

		if ($anhorsElements.length) {
			$(window).load(function() {
				for (var i = 0; i < $anhorsElements.length; i++) {
					var anhor = $anhorsElements[i];
					if (anhor.href != undefined && anhor.href && window.location.href == anhor.href) {
						anhor.click();
						break;
					}
				}
			});
		}

		$('body').on('click', '.post-footer-sharing .gem-button', function(e) {
			e.preventDefault();
			$(this).closest('.post-footer-sharing').find('.sharing-popup').toggleClass('active');
		});

		var scrollTimer,
			body = document.body;

		$(window).scroll(function() {
			clearTimeout(scrollTimer);
			if(!body.classList.contains('disable-hover')) {
				//body.classList.add('disable-hover')
			}

			scrollTimer = setTimeout(function(){
				//body.classList.remove('disable-hover')
			}, 300);

			if(getScrollY() > 0) {
				$('.scroll-top-button').addClass('visible');
			} else {
				$('.scroll-top-button').removeClass('visible');
			}
		}).scroll();

		function getScrollY(elem){
			return window.pageYOffset || document.documentElement.scrollTop;
		}

		$('a.hidden-email').each(function() {
			$(this).attr('href', 'mailto:'+$(this).data('name')+'@'+$(this).data('domain'));
		});

		$('#colophon .footer-widget-area').thegemPreloader(function() {
			$('#colophon .footer-widget-area').isotope({
				itemSelector: '.widget',
				layoutMode: 'masonry'
			});
		});

		$('body').updateTabs();
	});

	$(document).on('show.vc.accordion', '[data-vc-accordion]', function() {
		var $target = $(this).data('vc.accordion').getContainer();
		var correction = 0;
		if(!$target.find('.vc_tta-tabs').length || !$(this).is(':visible') || $target.data('vc-tta-autoplay')) return ;
		if($('#site-header.animated-header').length && $('#site-header').hasClass('fixed')) {
			var shrink = $('#site-header').hasClass('shrink');
			$('#site-header').addClass('scroll-counting');
			$('#site-header').addClass('fixed shrink');
			correction = $('#site-header').outerHeight() + $('#site-header').position().top;
			if(!shrink) {
				$('#site-header').removeClass('fixed shrink');
			}
			$('#site-header').removeClass('scroll-counting');
		}
		var target_top = $target.offset().top - correction - 100 + 1;
		$('html, body').stop(true, true).animate({scrollTop:target_top}, 500, 'easeInOutQuint');
	});

	var vc_update_fullwidth_init = true;
	$(document).on('vc-full-width-row', function(e) {
		if (window.gemOptions.clientWidth - $page.width() > 25 || window.gemSettings.isRTL) {
			for (var i = 1; i < arguments.length; i++) {
				var $el = $(arguments[i]);
				$el.addClass("vc_hidden");
				var $el_full = $el.next(".vc_row-full-width");
				$el_full.length || ($el_full = $el.parent().next(".vc_row-full-width"));
				var el_margin_left = parseInt($el.css("margin-left"), 10),
					el_margin_right = parseInt($el.css("margin-right"), 10),
					offset = 0 - $el_full.offset().left - el_margin_left + $('#page').offset().left + parseInt($('#page').css('padding-left')),
					width = $('#page').width();

				var offsetKey = window.gemSettings.isRTL ? 'right' : 'left';
				var cssData = {
					position: "relative",
					left: offset,
					"box-sizing": "border-box",
					width: $("#page").width()
				};
				cssData[offsetKey] = offset;

				if ($el.css(cssData), !$el.data("vcStretchContent")) {
					var padding = -1 * offset;
					0 > padding && (padding = 0);
					var paddingRight = width - padding - $el_full.width() + el_margin_left + el_margin_right;
					0 > paddingRight && (paddingRight = 0), $el.css({
						"padding-left": padding + "px",
						"padding-right": paddingRight + "px"
					})
				}
				$el.attr("data-vc-full-width-init", "true"), $el.removeClass("vc_hidden");
				$el.trigger('VCRowFullwidthUpdate');
			}
		}
		update_fullwidths(true, vc_update_fullwidth_init);
		vc_update_fullwidth_init = false;
	});

	if (!window.gemSettings.lasyDisabled && $.support.opacity) {
	    $('.wpb_text_column.wpb_animate_when_almost_visible.wpb_fade').each(function() {
	        $(this).wrap('<div class="lazy-loading"></div>').addClass('lazy-loading-item').data('ll-effect', 'fading');
	    });

	    $('.gem-list.lazy-loading').each(function() {
	        $(this).data('ll-item-delay', '200');
	        $('li', this).addClass('lazy-loading-item').data('ll-effect', 'slide-right');
	        $('li', this).each(function(index) {
	            $(this).attr("style", "transition-delay: " + (index + 1) * 0.2 + "s;");
	        });
	    });

	    $.lazyLoading();
	}

	$('body').on('click', '.gem-button[href^="#give-form-"]', function(e) {
		var form_id = $(this).attr('href').replace('#give-form-', '');
		form_id = parseInt(form_id);
		if (!isNaN(form_id)) {
			$('#give-form-' + form_id + ' .give-btn-modal').click();
		}
		e.preventDefault();
		return false;
	});

})(jQuery);

(function($) {
		$('.menu-item-search a').on('click', function(e){
			e.preventDefault();
			$('.menu-item-search').toggleClass('active');
		});
})(jQuery);


console.log("-----RB-----");
/*$ = jQuery ;
$(function(a){a.fn.okzoom=function(b){return b=a.extend({},a.fn.okzoom.defaults,b),this.each(function(){var c={},d=this;c.options=b,c.$el=a(d),c.el=d,c.listener=document.createElement("div"),c.$listener=a(c.listener).addClass("ok-listener").css({position:"absolute",zIndex:1e4}),a("body").append(c.$listener);var e=document.createElement("div");if(e.id="ok-loupe",e.style.position="absolute",e.style.backgroundRepeat="no-repeat",e.style.pointerEvents="none",e.style.display="none",e.style.zIndex=7879,a("body").append(e),c.loupe=e,c.$el.data("okzoom",c),c.options=b,a(c.el).bind("mouseover",function(b){return function(c){a.fn.okzoom.build(b,c)}}(c)),c.$listener.bind("mousemove",function(b){return function(c){a.fn.okzoom.mousemove(b,c)}}(c)),c.$listener.bind("mouseout",function(b){return function(c){a.fn.okzoom.mouseout(b,c)}}(c)),c.options.height=c.options.height||c.options.width,c.image_from_data=c.$el.data("okimage"),c.has_data_image="undefined"!=typeof c.image_from_data,c.has_data_image&&(c.img=new Image,c.img.src=c.image_from_data),c.msie=-1,"Microsoft Internet Explorer"==navigator.appName){var f=navigator.userAgent,g=new RegExp("MSIE ([0-9]{1,}[.0-9]{0,})");null!=g.exec(f)&&(c.msie=parseFloat(RegExp.$1))}})},a.fn.okzoom.defaults={width:150,height:null,scaleWidth:null,round:!0,background:"#fff",backgroundRepeat:"no-repeat",shadow:"0 0 5px #000",border:0},a.fn.okzoom.build=function(b,c){if(b.has_data_image?b.image_from_data!=b.$el.attr("data-okimage")&&(b.image_from_data=b.$el.attr("data-okimage"),a(b.img).remove(),b.img=new Image,b.img.src=b.image_from_data):b.img=b.el,b.msie>-1&&b.msie<9&&!b.img.naturalized){var d=function(a){a=a||this;var b=new Image;b.el=a,b.src=a.src,a.naturalWidth=b.width,a.naturalHeight=b.height,a.naturalized=!0};if(!b.img.complete)return;d(b.img)}b.offset=b.$el.offset(),b.width=b.$el.width(),b.height=b.$el.height(),b.$listener.css({display:"block",width:b.$el.outerWidth(),height:b.$el.outerHeight(),top:b.$el.offset().top,left:b.$el.offset().left}),b.options.scaleWidth?(b.naturalWidth=b.options.scaleWidth,b.naturalHeight=Math.round(b.img.naturalHeight*b.options.scaleWidth/b.img.naturalWidth)):(b.naturalWidth=b.img.naturalWidth,b.naturalHeight=b.img.naturalHeight),b.widthRatio=b.naturalWidth/b.width,b.heightRatio=b.naturalHeight/b.height,b.loupe.style.width=b.options.width+"px",b.loupe.style.height=b.options.height+"px",b.loupe.style.border=b.options.border,b.loupe.style.background=b.options.background+" url("+b.img.src+")",b.loupe.style.backgroundRepeat=b.options.backgroundRepeat,b.loupe.style.backgroundSize=b.options.scaleWidth?b.naturalWidth+"px "+b.naturalHeight+"px":"auto",b.loupe.style.borderRadius=b.loupe.style.OBorderRadius=b.loupe.style.MozBorderRadius=b.loupe.style.WebkitBorderRadius=b.options.round?b.options.width+"px":0,b.loupe.style.boxShadow=b.options.shadow,b.initialized=!0,a.fn.okzoom.mousemove(b,c)},a.fn.okzoom.mousemove=function(a,b){if(a.initialized){var c=a.options.width/2,d=a.options.height/2,e="undefined"!=typeof b.pageX?b.pageX:b.clientX+document.documentElement.scrollLeft,f="undefined"!=typeof b.pageY?b.pageY:b.clientY+document.documentElement.scrollTop,g=-1*Math.floor((e-a.offset.left)*a.widthRatio-c),h=-1*Math.floor((f-a.offset.top)*a.heightRatio-d);document.body.style.cursor="none",a.loupe.style.display="block",a.loupe.style.left=e-c+"px",a.loupe.style.top=f-d+"px",a.loupe.style.backgroundPosition=g+"px "+h+"px"}},a.fn.okzoom.mouseout=function(a){a.loupe.style.display="none",a.loupe.style.background="none",a.listener.style.display="none",document.body.style.cursor="auto"}});

//fancybox-button--thumbs
*/