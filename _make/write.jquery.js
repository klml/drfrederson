
if ( psmpagelemma == "404error" ) {
    
     pagesourcepath = psmpagesourcepath.split('/') ;
     pagesourcepath.pop() ;
     lemma = window.location.pathname.split('/').pop().split('.')[0] ;

     pagesourcepathprefill = pagesourcepath.join('/') + '/example.md'; // TODO var     

     pagesourcepath.push( lemma ) ;
     pagesourcepathwrite = pagesourcepath.join('/') + '.md'; // TODO var

} else {
    pagesourcepathwrite = psmpagesourcepath ;
    pagesourcepathprefill = psmpagesourcepath + '?v=' + Math.random() ; // force reload
}


var formwebeditsrc = '<form accept-charset="ISO-8859-1" class="webedit" style="width:95%;height:430px;"><textarea name="content" style="width:95%;" ></textarea><br/><button>save</button></form>' ;
var formwebeditbtn = '<button id="edit" >edit</button>';
var formwebedit = 'form.webedit'; // redu


var sucmsg = '<div id="successmsg" class="notice" >submitted successfully</div>' ;    
var errmsg = '<div id="errormsg" class="hidden notice" >error occurred while sending</div>' ;



jQuery(document).ready(function() {

    $('#footer').after( formwebeditbtn );

    $('#edit').click( function() {
        $('#edit').replaceWith( formwebeditsrc ) ;
        
        
        $(formwebedit).find('textarea').load( pagesourcepathprefill );
        webeditSend( formwebedit ); // init after replaceWith
     });

    var intermission ; 
    $( formwebedit ).find('textarea').keyup( function() {
        //~ window.clearTimeout( intermission );
        //~ intermission = window.setTimeout( 'render()' , 3000);
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
                $( formwebedit ).html( sucmsg ) ;
                location.reload(true);
            },
            error:function(){
                $( formwebedit ).append( errmsg );
            }
        });         
    });    
    
}
function render () {
    $('#content').html( Markdown( $( formwebedit ).find('textarea').val() ) );
};
