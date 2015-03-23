jQuery(document).ready(function() {

    $('#requestauth, #showedit').click( function() {
        if( $(this).is(':checked') ) {
            $.cookie('showedit', true);
        };
        if( !$(this).is(':checked') ) {
            $.cookie('showedit', false);
            $('#drf-edit a').hide();
        };
    });

    if( $.cookie('showedit') == "true" ) {
        $('#drf-edit a').show();
        $('#requestauth, #showedit').prop('checked', true);
    };

   (this.hashrouter = function () {
        var hash = window.location.hash ;
        if ( hash == "#drf-edit" || hash == "#drf-markdownwysiwym" ) {
            $.cookie('showedit', true);
            $( "#drf-edit" ).load( "_drf/drf-formwebedit.html")
        }
   })();
   window.onhashchange = this.hashrouter;
});
