(function($){
$(document).ready(function() {
	$('body').prepend($(".mask"));

	$(window).load(function() { 
        $("#preloader").delay(500).fadeOut(); 
        $(".mask").delay(1000).fadeOut("slow");
    });

});
})(jQuery);

