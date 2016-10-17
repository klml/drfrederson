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

    // load https://simplemde.com
    $('#drf-markdownwysiwym').click( function( event ) {
        event.preventDefault();
        $.cookie('drf-markdownwysiwym', true);
        markdownwysiwym();
    });
    if( $.cookie('drf-markdownwysiwym') == "true" ) {
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
    $.getScript("//cdn.jsdelivr.net/simplemde/latest/simplemde.min.js", function() { // need to load out of webedit TODO
        var simplemde = new SimpleMDE({
            element: document.getElementById("drf-webedit-textarea"),
            toolbar: [ "bold", "italic", "strikethrough", "heading-2", "heading-3", "code", "quote", "unordered-list", "ordered-list",  "link", "image", "table", "horizontal-rule", "|",
            "clean-block", "preview", "side-by-side", "fullscreen", "|", {
                    name: "close",
                    action: function customFunction(editor){
                        $.cookie('drf-markdownwysiwym', false);
                        simplemde.toTextArea();
                        simplemde = null;
                    },
                    className: "fa fa-close right",
                    title: "close editor and get back",
                }
            ],
        });
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
