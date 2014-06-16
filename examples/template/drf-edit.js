jQuery(document).ready(function() {

    $('#requestauth').click( function() {

        if( $(this).is(':checked') ) {
            document.cookie = 'requestauth=true' ;
            $.getScript("_drf/drf-write.jquery.js?v=q");
        };
        if( !$(this).is(':checked') ) {
            document.cookie = 'requestauth=false';
            $('#edit').remove();
        };
        
    });    

    edit = document.cookie.search(/requestauth=true/); 
    if( edit != -1 ) {
        $.getScript("_drf/drf-write.jquery.js?v=q");
        $('#requestauth').prop('checked', true);
    };
    // TODO if more cookie useage -- plugin
});
