var drf_lemma = window.location.pathname.split('/').pop().split('.')[0] ;
var drf_noedit = false ;
var drf_sourcepath_write = drf_sourcepath_prefill = $("meta[name='dcterms.source']").attr("content");

if ( $( "body" ).hasClass( "noedit" ) ) {
    drf_noedit = true ;
}

if (typeof drf_new != 'undefined' && drf_lemma != '404error' ) {

    var drf_lemma = drf_lemma.split(':').join('/') ;

    drf_sourcepath_prefill = drf_new.prefillpath ;
    drf_sourcepath_write = drf_new.sourcepath + drf_lemma + drf_new.sourceextension ;

    drf_noedit = false ;
}

jQuery(document).ready(function() {

    $('input#drf-sourcepath').val( drf_sourcepath_write );
    $('#drf-webedit').find('textarea').load( drf_sourcepath_prefill  + '?v=' + Math.random() ) ; // force reload 

    $('#drf-hide-edit').click( function() {
        $.cookie('showedit', false);
        $('#drf-edit').hide();
    });

    //~ if TODO ( drf_noedit ) {


    if ( window.location.hash == "#drf-markdownwysiwym" ) {
        $.getScript("//cdn.jsdelivr.net/g/editor(editor.js)", function() { // need to load out of webedit TODO
            var editor = new Editor({
                element : $('#drf-webedit textarea').get(0) ,
                tools: []
            });
            editor.render();
            webeditSend();
        });
    } else {
            webeditSend();
    }

    var intermission ; 
    $( '#drf-webedit' ).find('textarea').keyup( function() {
        window.clearTimeout( intermission );
        intermission = window.setTimeout( 'render()' , 3000);
    });
});

function webeditSend ( ) {

    $( '#drf-webedit' ).submit( function(event) {

        event.preventDefault();
        $.ajax({
            url: $(this).context.action ,
                type: $(this).context.method ,
            data: $(this).serialize() ,
            success: function( msg ){
                $( '#successmsg' ).show("slow", function() {
                    $(this).find('pre').text( msg );
                    window.location.hash = "#main"
                    location.reload();
                });
            },
            error:function( msg ){
                $( '#errormsg' ).show( function() {
                     $(this).find('pre').text( msg );
                });
            }
        });         
    });    
    
}
function render () {
    $('#main').html( Markdown( $( '#drf-webedit' ).find('textarea').val() ) );
};
