jQuery(document).ready(function() {

    $('#requestauth, #showedit').click( function() {
        if( $(this).is(':checked') ) {
            document.cookie = 'showedit=true' ;
        };
        if( !$(this).is(':checked') ) {
            document.cookie = 'showedit=false';
            $('#drf-edit a').hide();
        };
    });

    edit = document.cookie.search(/showedit=true/); 
    if( edit != -1 ) {
        $('#drf-edit a').show();
        $('#requestauth, #showedit').prop('checked', true);
    };
    // TODO useage -- plugin http://cdn.jsdelivr.net/jquery.cookie/1.4.1/jquery.cookie.js


   (this.hashrouter = function () {
        var hash = window.location.hash ;
        if ( hash == "#drf-edit" || hash == "#drf-markdownwysiwym" ) {
            $( "#drf-edit" ).load( "_drf/drf-formwebedit.html")
        }
   })();
   window.onhashchange = this.hashrouter;
});
