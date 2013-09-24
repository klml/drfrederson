var psmpagesourcev = psmpagesource + '?v=' + Math.random() ; // force reload

var formwebeditsrc = '<form accept-charset="ISO-8859-1" class="webedit" style="width:95%;height:430px;"><textarea name="content" style="width:95%;" ></textarea><br/><button>save</button></form>' ;
var formwebedit = 'form.webedit'; // redu


var sucmsg = '<div id="successmsg" class="notice" >submitted successfully</div>' ;    
var errmsg = '<div id="errormsg" class="hidden notice" >error occurred while sending</div>' ;



jQuery(document).ready(function() {

    $('#edit').show();

    $('#edit').click( function() {
        $('#edit').replaceWith( formwebeditsrc ) ;
        
        
        $(formwebedit).find('textarea').load( psmpagesourcev, function (responseText, textStatus, req) {
            if (textStatus == "error") {
              $( formwebedit ).find( psmpagelemma ).val(page.title); // on 404 pages, you will get no source, its not there, try to create it
            }
        });
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
            url: '_make/writer.php',
            type: 'POST',
            data: $(this).serialize() + '&sourcepath=' + psmpagesource ,
            success: function(){
                $( formwebedit ).html( sucmsg ) ;
                location.reload();
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
