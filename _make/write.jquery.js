
var pagesourcepath = $("meta[name='dcterms.source']").attr("content");

if ( $( "body" ).hasClass( "d2c_404error" ) ) {

     pagesourcepath = pagesourcepath.split('/') ;
     pagesourcepath.pop() ;
     lemma = window.location.pathname.split('/').pop().split('.')[0] ;

     pagesourcepathprefill = pagesourcepath.join('/') + '/example.md'; // TODO var sourceextension

     pagesourcepath.push( lemma ) ;
     pagesourcepathwrite = pagesourcepath.join('/') + '.md'; // TODO var sourceextension

} else {
    pagesourcepathwrite = pagesourcepath ;
    pagesourcepathprefill = pagesourcepath + '?v=' + Math.random() ; // force reload
}

var formwebedit = 'form.webedit'; // to formwebedit.php

jQuery(document).ready(function() {

    $( "#footer" ).load( "template/formwebedit.html", function() {

        $('#edit').click( function() {
            $(this).hide();
            $('.webedit').show();
            $(formwebedit).find('textarea').load( pagesourcepathprefill );
            webeditSend( formwebedit ); // init after replaceWith
         });

        var intermission ; 
        $( formwebedit ).find('textarea').keyup( function() {
            //~ window.clearTimeout( intermission );
            //~ intermission = window.setTimeout( 'render()' , 3000);
        });
    });
});

function webeditSend ( formwebedit ) {
    $( formwebedit ).submit( function(event) {
        event.preventDefault();
        $.ajax({
            url: '_make/make.php',
            type: 'POST',
            data: $(this).serialize() + '&sourcepath=' + pagesourcepathwrite ,
            success: function(){
                $( '#successmsg' ).show() ;
                location.reload(true);
            },
            error:function(){
                $( '#errormsg' ).show();
            }
        });         
    });    
    
}
function render () {
    $('#content').html( Markdown( $( formwebedit ).find('textarea').val() ) );
};
