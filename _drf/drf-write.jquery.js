
var drf_sourcepath = $("meta[name='dcterms.source']").attr("content");

if ( $( "body" ).hasClass( "d2c_404error" ) ) {

     drf_sourcepath = drf_sourcepath.split('/') ;
     drf_sourcepath.pop() ;
     drf_sourcepath.push( window.location.pathname.split('/').pop().split('.')[0] ) ; // get lemma

     drf_sourcepath_prefill = drf_sourcepath.join('/') + '/example.md'; // TODO var sourceextension
     drf_sourcepath_write = drf_sourcepath.join('/') + '.md'; // TODO var sourceextension

} else {
    drf_sourcepath_write = drf_sourcepath ;
    drf_sourcepath_prefill = drf_sourcepath + '?v=' + Math.random() ; // force reload
}

jQuery(document).ready(function() {

    $( "#footer" ).load( "template/drf-formwebedit.html", function() {

        $('#drf-edit').click( function() {

            $(this).hide();
            $('.drf-webedit').show();
            $('.drf-webedit').find('textarea').load( drf_sourcepath_prefill );

            if ( $( "body" ).hasClass( "noedit" ) ) {
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
