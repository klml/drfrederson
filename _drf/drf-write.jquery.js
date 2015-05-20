var drf_lemma = window.location.pathname.split('/').pop().split('.')[0] ;
var drf_sourcepath_write = drf_sourcepath_prefill = $("meta[name='dcterms.source']").attr("content");

// to create new pages from a 404
if (typeof drf_new != 'undefined' && drf_lemma != 'drf:404error' ) {
    var drf_lemma = drf_lemma.split(':').join('/') ;
    drf_sourcepath_prefill = drf_new.prefillpath ;
    drf_sourcepath_write = drf_new.sourcepath + drf_lemma + drf_new.sourceextension ;
}

jQuery(document).ready(function() {

    // set cookie for editlink on other pages
    $.cookie('drf-showedit', true);

    // get sourcepath from document and fill it in the edit form
    $('input#drf-sourcepath').val( drf_sourcepath_write );

    // fetch existing source file and fill it in textarea
    $('#drf-webedit').find('textarea').load( drf_sourcepath_prefill  + '?v=' + Math.random() ) ; // force reload 

    // remove edit link and edit section
    $('#drf-showedit').click( function() {
        $.cookie('drf-showedit', false) ;
        $('#drf-edit').hide('slow') ;
        window.location.hash = "#main" ;
    });

    // load https://github.com/lepture/editor markdown WYSIWYM
    $('#drf-markdownwysiwym').click( function() {
        if( $(this).is(':checked') ) {
            $.cookie('drf-markdownwysiwym', true);
            markdownwysiwym();
        };
        if( !$(this).is(':checked') ) {
            $.cookie('drf-markdownwysiwym', false);
            //~  TODO delete lepture
        };
    });
    if( $.cookie('drf-markdownwysiwym') == "true" ) {
        $('#drf-markdownwysiwym').prop('checked', true);
        markdownwysiwym();
    };

    // redner preview on click and on time
    $('#drf-render').click( function() {
        render();
    });
    var intermission ; 
    $( '#drf-webedit' ).find('textarea').keyup( function() {
        window.clearTimeout( intermission );
        intermission = window.setTimeout( 'render()' , 3000);
    });
     webeditSend();
});

function markdownwysiwym ( ) {
    $.getScript("//cdn.jsdelivr.net/g/editor(editor.js)", function() { // need to load out of webedit TODO
        var editor = new Editor({
            element : $('#drf-webedit textarea').get(0) ,
            tools: []
        });
        editor.render();
        webeditSend();
    });
}
function webeditSend ( ) {
    $('#drf-save').click( function() { // assign submit after changing the editor by user
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
    });
}
function render () {
    $('#main').html( Markdown( $( '#drf-webedit' ).find('textarea').val() ) );
};
