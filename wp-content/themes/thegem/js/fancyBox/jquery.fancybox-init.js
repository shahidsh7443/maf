(function($) {

	$.fn.initGalleryFancybox = function() {

	    $('a.fancy-gallery', this).fancybox({
	        caption : function( instance, item ) {
	            var slideInfo = $('.slide-info', this);
	            if ($('> *', slideInfo).length) {
	                return slideInfo.clone().html();
	            }
	        },
	        onInit: function(instance) {
	            instance.$refs.caption.addClass('fancybox-title');
	            instance.$refs.caption.parent().addClass('slideinfo');
	        }
	    });
	};

	$.fn.initPortfolioFancybox = function() {
		$('a.fancy, .fancy-link-inner a', this).fancybox();

		$('.portfolio-item a.vimeo, .portfolio-item a.youtube', this).fancybox({
			type: 'iframe'
		});

		$('.portfolio-item a.self_video', this).fancybox({
			iframe : {
				preload : false
			},
			type: 'video',
			afterShow: function(instance, current) {
				current.$slide.addClass('thegem-fancybox-video');
				var $video = $('<div id="fancybox-video"><video width="100%" height="100%" autoplay="autoplay" controls="controls" src="'+current.opts.$orig.attr('href')+'" preload="none"></video></div>');
				current.$content.html($video);
				$('video', $video).mediaelementplayer();
			}
		});
	};

	$.fn.initBlogFancybox = function() {
		$('a.fancy, .fancy-link-inner a', this).fancybox();

		$('.blog article a.youtube, .blog article a.vimeo', this).fancybox({
			type: 'iframe'
		});
	};

	$(document).initGalleryFancybox();
	$(document).initPortfolioFancybox();
	$(document).initBlogFancybox();

	$('a.fancy, .fancy-link-inner a').fancybox();
})(jQuery);
