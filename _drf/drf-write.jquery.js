jQuery(document).ready(function() {

    var drf_lemma = window.location.pathname.split('/').pop().split('.')[0] ;
    var dcterms_source = $("meta[name='dcterms.source']").attr("content");

    // to create new pages from a 404
    if ( dcterms_source == 'source/drf/404error.md' && drf_lemma != 'drf:404error' ) {
        var drf_lemma = drf_lemma.split(':').join('/') ;

        $.getJSON( "json/meta.json", function( metadata ) {
            drf_sourcepath_prefill = metadata.prefillpath ;
            drf_sourcepath_write = metadata.sourcepath + drf_lemma + metadata.sourceextension ;
            prepareWebEdit();
        });
    } else {
        drf_sourcepath_write = drf_sourcepath_prefill = dcterms_source ;
        prepareWebEdit();
    }
});

function prepareWebEdit ( ) {

    // set cookie for editlink on other pages
    $.cookie('drf-showedit', true);

    // indicate editmode in title tag
    $(document).prop('title', '✎ ' +  document.title );

    // get sourcepath from document and fill it in the edit form
    $('input#drf-sourcepath').val( drf_sourcepath_write );

    // fetch existing source file and fill it in textarea
    // random to force reload
    $('#drf-webedit').find('textarea').load( drf_sourcepath_prefill  + '?v=' + Math.random() , function() {
        $(this).focus();
    });

    // remove edit link and edit section
    $('#drf-cancel').click( function() {
        $.cookie('drf-showedit', false) ;
        $('#drf-edit').hide('slow') ;
        window.location.hash = "#main" ;
    });

    // load https://simplemde.com
    $('#drf-markdownwysiwym').click( function( event ) {
        event.preventDefault();
        $(this).hide();
        $.cookie('drf-markdownwysiwym', true);
        markdownwysiwym();
    });
    if( $.cookie('drf-markdownwysiwym') == "true" ) {
        $('#drf-markdownwysiwym').hide();
        markdownwysiwym();
    };

    // redner preview on click and on time
    $('#drf-render').click( function() {
        clientRender();
        $('#drf-webedit').find('textarea').focus();
    });
     webeditSend();
}

function markdownwysiwym ( ) {
    $.getScript("//cdn.jsdelivr.net/simplemde/latest/simplemde.min.js", function() { // need to load out of webedit TODO
        var simplemde = new SimpleMDE({
            element: document.getElementById("drf-webedit-textarea"),
            toolbar: [ "bold", "italic", "strikethrough", "heading-2", "heading-3", "code", "quote", "unordered-list", "ordered-list",  "link", "image", "table", "horizontal-rule", "|",
            "clean-block", "preview", "side-by-side", "fullscreen", "|", {
                    name: "close",
                    action: function customFunction(editor){
                        $.cookie('drf-markdownwysiwym', false);
                        $('#drf-markdownwysiwym').show();
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
                    $( '#successmsg' ).show("slow", function(msg) {
                        $(this).find('pre').text( msg );
                        serverRender( );
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

function serverRender ( ) {
    $.get( "/_drf/make.php", { drf_sourcepath: $( '#drf-sourcepath' ).val()  } , function( data ) {
        window.location.hash = "#main" ;
        location.reload();
    });
}

function clientRender () {
    $('#main').html( Markdown( $( '#drf-webedit' ).find('textarea').val() ) );
};
