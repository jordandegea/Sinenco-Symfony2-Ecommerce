

function editCurrency( $url ){
    
    $.ajax({
        url: $url,
        context: document.body
    }).done(function(data) {
        $('#editingCurrencyModal').modal('hide');
        if ( data > 0 ){
            $('#editedCurrencyModal').modal('show'); 
            location.reload(true);
        }else{
            $('#editingErrorCurrencyModal').modal('show');
        }
    });
    $('#editingCurrencyModal').modal('show');
}
