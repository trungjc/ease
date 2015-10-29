jQuery(document).ready(function($) {
    var userAgent = navigator.userAgent.toLowerCase();
    Browser = {
        Version: (userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
        Chrome: /chrome/.test(userAgent),
        Safari: /webkit/.test(userAgent),
        Opera: /opera/.test(userAgent),
        IE: /msie/.test(userAgent) && !/opera/.test(userAgent),
        Mozilla: /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent)
    };

    if (Browser.IE || Browser.Opera) {
        $('a[href*="motopress_visual_editor"]')
            .attr('href', 'javascript:void(0);')
            .css({
                'cursor': 'default',
                'color': 'gray'
            });
        $('#motopress-visual-editor-btn').addClass('disabled');
        $('#motopress-browser-support-msg').show();
    }
});