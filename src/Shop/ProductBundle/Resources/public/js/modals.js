
function resizeProdCol(){
    var heightToDefine = 0 ;
    for ( i = 1 ; i < 5 ; i++){
        if ( $("#prodCol"+i).height() > heightToDefine ){
            heightToDefine = $("#prodCol"+i).height() ;
        }
    }
    for ( i = 1 ; i < 5 ; i++){
        $("#prodColThumb"+i).css("height", (heightToDefine-20)+"px");
    }
} 
$( window ).resize(function() {
    resizeProdCol();
});

$(document).ready (function(){
    resizeProdCol();
});

function addToCart( $url){
    
    $.ajax({
        url: $url,
        context: document.body
    }).done(function(data) {
        $('#productAddingModal').modal('hide');
        if ( data > 0 ){
            $('#productAddedModal').modal('show'); 
        }else{
            $('#productErrorModal').modal('show');
        }
    });
    $('#productAddingModal').modal('show');
}
$(document).ready(function(){
    
    $("#cancelPObtn").on("click", function(){ $("#error-dialog").modal('toggle');});
    
});