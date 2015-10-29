steal( 'jquery/class' )
    .then('mp/confirm/confirm.js' + motopress.pluginVersionParam, function($) {
    /**
    * @class MP.Language
    */
    $.Class('MP.Language', {
        init: function() {
            $.ajax({
                url: MP.Settings.pluginRootUrl + '/' + MP.Settings.pluginName + '/lang/' + MP.Settings.lang + motopress.pluginVersionParam,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    localStorage.clear();
                    $.each(data.lang, function(key, value) {
                        localStorage.setItem(key, value);
                    });
                    new MP.Confirm($('#motopress-confirm-modal'));
                },
                error: function() {
                    MP.Flash.setFlash(localStorage.getItem('langJsonNotFound'), 'error');
                    MP.Flash.showMessage();
                }
            });
        }
    });
});
