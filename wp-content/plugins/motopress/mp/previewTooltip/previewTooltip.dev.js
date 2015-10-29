steal('jquery/class', function($) {
   /**
    * @class MP.previewTooltip
    */
    $.Class('MP.previewTooltip',
    /** @Static */
    {
        previewTooltip: function() {
            $(".motopress-show-hide-btn").tooltip({
                placement: "top",
                container: ".motopress-helper-container"
            });
        }
    },
    /** @Prototype */
    {})
});