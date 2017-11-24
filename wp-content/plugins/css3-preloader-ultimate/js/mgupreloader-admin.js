(function( $ ) {

    $('#settings-form-submit-nosave, .settings-form-submit-nosave').click(function(e) {
    	$('#save_form').val("0");
        $( "#mgupreloader_settings_form" ).submit();
    });

    $('.settings-form-submit-save').click(function(e) {
        $( "#mgupreloader_settings_form" ).submit();
    });

    $('.preview-style-wrapper').click(function(e) {
        var select_id = ($(this).attr('data-id'));
     
        $('#mgtup_change_style option[data-id='+select_id+']').attr('selected','selected');
        $('html,body').animate({ scrollTop: 0 }, 'slow');

    });

    $('.link-smooth-scroll').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('html,body').animate({ scrollTop: $($(this).attr('href')).offset().top }, 'slow');
    });

    $('.color-picker').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        alert('Purchase PRO version to unlock color change feature.');
    });

    $('.image-preloader-selector, #upload-btn').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        alert('Purchase PRO version to unlock image preloader feature.');
        $(this).attr('checked','');
    });

    function setCookie (name, value, expires, path, domain, secure) {
          document.cookie = name + "=" + escape(value) +
            ((expires) ? "; expires=" + expires : "") +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            ((secure) ? "; secure" : "");
    }
    function getCookie(name) {
        var cookie = " " + document.cookie;
        var search = " " + name + "=";
        var setStr = null;
        var offset = 0;
        var end = 0;
        if (cookie.length > 0) {
            offset = cookie.indexOf(search);
            if (offset != -1) {
                offset += search.length;
                end = cookie.indexOf(";", offset)
                if (end == -1) {
                    end = cookie.length;
                }
                setStr = unescape(cookie.substring(offset, end));
            }
        }
        return(setStr);
    }

    if(!getCookie("uwphidemessage")) {
        $('.uwp-message').css("display","block");
    }

    $('.uwp-message button.notice-dismiss').click(
        function() {
            setCookie("uwphidemessage", 1);
            $('.uwp-message').hide();
        }
    );
     
})( jQuery );