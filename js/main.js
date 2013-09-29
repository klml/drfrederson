jQuery(document).ready(function() {


    $('#requestauth').click( function() {
        document.cookie = 'requestauth=' + true ;
    });
    if(document.cookie) {
        $.getScript("_make/write.jquery.js?v=q");
    };

});

