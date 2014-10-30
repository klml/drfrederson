jQuery(document).ready(function() {

    $('#requestauth').click( function() {

        if( $(this).is(':checked') ) {
            document.cookie = 'requestauth=true' ;
            $.getScript("template/drf-write.jquery.js");
        };
        if( !$(this).is(':checked') ) {
            document.cookie = 'requestauth=false';
            $('#edit').remove();
        };
        
    });    

    edit = document.cookie.search(/requestauth=true/); 
    if( edit != -1 ) {
        $.getScript("template/drf-write.jquery.js");
        $('#requestauth').prop('checked', true);
    };
    // TODO if more cookie useage -- plugin
});
