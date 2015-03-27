jQuery(document).ready(function() {

    if( $.cookie('drf-showedit') == "true" ) {
        $('#drf-edit a').show();
    };

   (this.hashrouter = function () {
        if ( window.location.hash == "#drf-edit" ) {
            $( "#drf-edit" ).load( "_drf/drf-formwebedit.html")
        }
   })();
   window.onhashchange = this.hashrouter;
});
