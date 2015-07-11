
function resizeProdCol() {
    var heightToDefine = 0;
    var firstILine = 1;
    var lastOffsetTop = document.getElementById("prodCol1").offsetTop;
    var obj = null;
    for (var i = 1; i < 20; i++) {
        obj = document.getElementById("prodCol" + i);

        if (obj === null || obj.offsetTop !== lastOffsetTop || i === 19 ) {
            for (var j = firstILine; j < i; j++) {
                document.getElementById("prodCol" + j).style.height = heightToDefine + "px";
            }
	    
  	    if ( obj === null ){
                break ;
            }

            obj = document.getElementById("prodCol" + i);
            lastOffsetTop = obj.offsetTop;
            firstILine = i;
            heightToDefine = 0;
        }
        
        if (obj.offsetHeight > heightToDefine) {
            heightToDefine = obj.offsetHeight;
        }
    }
}

resizeProdCol();


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

