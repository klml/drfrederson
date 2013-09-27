jQuery(document).ready(function() {


    $('#login').click( function() {
        document.cookie = 'edit=' + true ;
    });
    if(document.cookie) {
        $.getScript("_make/write.jquery.js?v=q");
    };

});

