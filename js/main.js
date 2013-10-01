jQuery(document).ready(function() {


    $('#requestauth').click( function() {
        document.cookie = 'requestauth=' + true ;
    });

    $('#preventauth').click( function() {
        document.cookie = 'requestauth=' + false ;
    });
    if(document.cookie == 'requestauth=' + true) {
        $.getScript("_make/write.jquery.js?v=q");
    };

});

