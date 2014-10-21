var RecMetals = {
    init : function() {
        $('button.buttonPoint').click(function(){
            var link = $(this).attr('link');
            window.location = link;
        });
    }
}

jQuery(document).ready(function() {
    RecMetals.init();

});
jQuery(window).resize(function() {

});