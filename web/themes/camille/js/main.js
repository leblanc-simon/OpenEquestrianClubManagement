function updatePrice() {
    var price = 0;
    $('#list-order-detail select[name="form_order_detail[card_id][]"]').each(function(){
        var i = $(this).attr('id').replace('form_order_detail_card_id_', '');
        price += parseFloat($(this).find('option:selected').attr('data-price')) * parseFloat($('#form_order_detail_quantity_' + i).val());
    });
    
    $('#form_total').val(price);
}

$(document).ready(function(){
    $('.add-order-detail button').click(function(){
        $('#list-order-detail').append($('#order-detail').html().replace(/%i%/g, num_order_detail++));
        updatePrice();
        return false;
    });
    
    $('.remove-order-detail').live('click', function(){
        $(this).parent().parent().remove();
        updatePrice();
        return false;
    });
    
    $('#list-order-detail select, #list-order-detail input[type=number]').live('change', function(){
        updatePrice();
    });
    
    updatePrice();
});