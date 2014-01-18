var drf_lemma = window.location.pathname.split('/').pop().split('.')[0] ;
var drf_noedit = false ;
var drf_sourcepath_write = drf_sourcepath_prefill = $("meta[name='dcterms.source']").attr("content");


if ( $( "body" ).hasClass( "noedit" ) ) {
    drf_noedit = true ;
}

if (typeof drf_new != 'undefined' && drf_lemma != '404error' ) {

    drf_sourcepath_prefill = drf_new.prefillpath ;
    drf_sourcepath_write = drf_new.sourcepath + drf_lemma + drf_new.sourceextension ;
    drf_noedit = false ;

} 

jQuery(document).ready(function() {

    $( "#footer" ).load( "template/drf-formwebedit.html", function() {

        $('#drf-edit').click( function() {

            $(this).hide();
            $('.drf-webedit').show();
            $('.drf-webedit').find('textarea').load( drf_sourcepath_prefill  + '?v=' + Math.random() ); // force reload 

            if ( drf_noedit ) {
                $( '.drf-webedit' ).find('button').replaceWith('edit is veiled');
            } else {
                webeditSend( ); // init after replaceWith
            }
         });

        var intermission ; 
        $( '.drf-webedit' ).find('textarea').keyup( function() {
            window.clearTimeout( intermission );
            intermission = window.setTimeout( 'render()' , 3000);
        });
    });
});



function webeditSend ( ) {
    $( '.drf-webedit' ).submit( function(event) {
        event.preventDefault();
        $.ajax({
            url: '_drf/make.php',
            type: 'POST',
            data: $(this).serialize() + '&drf_sourcepath=' + drf_sourcepath_write ,
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
    $('#main').html( Markdown( $( '.drf-webedit' ).find('textarea').val() ) );
};
