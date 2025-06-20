let site_url = document.location.origin;

$("body").on('change','.woocommerce-cart-form .accompanied_dog:input', function (e) {
    let sum = 0;
    $( ".accompanied_dog" ).each(function() {
        let value = $( this ).val();
        let name = $(this).attr('name');
        value = parseFloat(value);
        
        if ($(this).prop('checked')) {
            Cookies.set(name, value, { expires: 1, path: '/' });
            console.log(name);
            sum = sum+value;
        }else{
            Cookies.remove(name);
            console.log('no');
        }
    });
    if (sum != 0) {
        Cookies.set('accompanied_dog', sum, { expires: 1, path: '/' });
    }else{
        Cookies.remove('accompanied_dog');
    }
    
    let cartID = $(this).attr('data-item_key');
    
    val_arr = [];
    if ($('.accompanied_dog[data-item_key="'+cartID+'"]').is(':checked')) {
        let accompanied_dog = $('.accompanied_dog[data-item_key="'+cartID+'"]').attr('data-accompanied_dog');
        let cur_value = $('.accompanied_dog[data-item_key="'+cartID+'"]').val();
        let val_str = 'accompanied_dog|'+cur_value+'|'+accompanied_dog;
        val_arr.push(val_str);  
    }
    /*if ($('.final_cleaning[data-item_key="'+cartID+'"]').is(':checked')) {
        let final_cleaning = $('.final_cleaning[data-item_key="'+cartID+'"]').attr('data-final_cleaning');
        let cur_value = $('.final_cleaning[data-item_key="'+cartID+'"]').val();
        let val_str = 'final_cleaning|'+cur_value+'|'+final_cleaning;
        val_arr.push(val_str);  
    }*/
    const $finalCleaning = $('.final_cleaning[data-item_key="'+cartID+'"]:checked');
    if($finalCleaning.length > 0){
        let final_cleaning = $finalCleaning.attr('data-final_cleaning');
        let cur_value = $finalCleaning.val();
        let val_str = 'final_cleaning|'+cur_value+'|'+final_cleaning;
        val_arr.push(val_str);
        if($finalCleaning.hasClass('final_cleaning_rut')){
            val_str = 'final_cleaning_rut|0|'+final_cleaning;
            val_arr.push(val_str);
        }
    }
    if ($('.cancellation[data-item_key="'+cartID+'"]').is(':checked')) {
        let cancellation = $('.cancellation[data-item_key="'+cartID+'"]').attr('data-cancellation');
        let cur_value = $('.cancellation[data-item_key="'+cartID+'"]').val();
        let val_str = 'cancellation|'+cur_value+'|'+cancellation;
        val_arr.push(val_str);  
    }
    let val_str = val_arr.join(';');
    
    update_options(cartID,val_str);
});

$("body").on('change','.woocommerce-cart-form .cancellation:input', function (e) {
    let sum = 0;
    $( ".cancellation" ).each(function() {
        let value = $( this ).val();
        let name = $(this).attr('name');
        value = parseFloat(value);
        
        if ($(this).prop('checked')) {
            Cookies.set(name, value, { expires: 1, path: '/' });
            console.log(name);
            sum = sum+value;
        }else{
            Cookies.remove(name);
            console.log('no');
        }
    });
    if (sum != 0) {
        Cookies.set('cancellation', sum, { expires: 1, path: '/' });
    }else{
        Cookies.remove('cancellation');
    }
    
    let cartID = $(this).attr('data-item_key');
    
    val_arr = [];
    if ($('.accompanied_dog[data-item_key="'+cartID+'"]').is(':checked')) {
        let accompanied_dog = $('.accompanied_dog[data-item_key="'+cartID+'"]').attr('data-accompanied_dog');
        let cur_value = $('.accompanied_dog[data-item_key="'+cartID+'"]').val();
        let val_str = 'accompanied_dog|'+cur_value+'|'+accompanied_dog;
        val_arr.push(val_str);  
    }
    /*if ($('.final_cleaning[data-item_key="'+cartID+'"]').is(':checked')) {
        let final_cleaning = $('.final_cleaning[data-item_key="'+cartID+'"]').attr('data-final_cleaning');
        let cur_value = $('.final_cleaning[data-item_key="'+cartID+'"]').val();
        let val_str = 'final_cleaning|'+cur_value+'|'+final_cleaning;
        val_arr.push(val_str);  
    }*/
    const $finalCleaning = $('.final_cleaning[data-item_key="'+cartID+'"]:checked');
    if($finalCleaning.length > 0){
        let final_cleaning = $finalCleaning.attr('data-final_cleaning');
        let cur_value = $finalCleaning.val();
        let val_str = 'final_cleaning|'+cur_value+'|'+final_cleaning;
        val_arr.push(val_str);
        if($finalCleaning.hasClass('final_cleaning_rut')){
            val_str = 'final_cleaning_rut|0|'+final_cleaning;
            val_arr.push(val_str);
        }
    }
    if ($('.cancellation[data-item_key="'+cartID+'"]').is(':checked')) {
        let cancellation = $('.cancellation[data-item_key="'+cartID+'"]').attr('data-cancellation');
        let cur_value = $('.cancellation[data-item_key="'+cartID+'"]').val();
        let val_str = 'cancellation|'+cur_value+'|'+cancellation;
        val_arr.push(val_str);  
    }
    let val_str = val_arr.join(';');
    
    update_options(cartID,val_str);
});

$("body").on('change','.woocommerce-cart-form .final_cleaning:input', function (e) {
    e.preventDefault();
    let sum = 0;
    const self =this;
    /*$( ".final_cleaning" ).each(function() {
        let value = $( this ).val();
        let name = $(this).attr('name');
        value = parseFloat(value);
        
        if ($(this).prop('checked')) {
            Cookies.set(name, value, { expires: 1, path: '/' });
            console.log(name);
            sum = sum+value;
        }else{
            Cookies.remove(name);
            console.log('no');
        }
    });*/
    $('.woocommerce-cart-form .shop_table .cart-subtotal-1').each(function(){
        if($(this).find( ".final_cleaning:checked" ).length > 1){
            $(this).find( ".final_cleaning:checked" ).not(self).prop('checked', false);
        }
        const $checkedCleaning = $(this).find( ".final_cleaning:checked" );
        if($checkedCleaning.length > 0){
            $checkedCleaning.each(function(){
                let value = $( this ).val();
                let name = $(this).attr('name');
                value = parseFloat(value);
                Cookies.set(name, value, { expires: 1, path: '/' });
                console.log(name, value);
                sum += value;
            })
        }
        else{
            let name = $(this).find('.final_cleaning').first().attr('name');
            Cookies.remove(name);
            console.log('no');
        }
    });
    /*$( ".final_cleaning" ).each(function() {
        let value = $( this ).val();
        let name = $(this).attr('name');
        value = parseFloat(value);

        if ($(this).prop('checked')) {
            Cookies.set(name, value, { expires: 1, path: '/' });
            console.log(name);
            sum = sum+value;
        }else{
            Cookies.remove(name);
            console.log('no');
        }
    });*/
    if (sum > 0) {
        Cookies.set('final_cleaning', sum, { expires: 1, path: '/' });
    }else{
        Cookies.remove('final_cleaning');
    }
    let cartID = $(this).attr('data-item_key');
    
    val_arr = [];
    if ($('.accompanied_dog[data-item_key="'+cartID+'"]').is(':checked')) {
        let accompanied_dog = $('.accompanied_dog[data-item_key="'+cartID+'"]').attr('data-accompanied_dog');
        let cur_value = $('.accompanied_dog[data-item_key="'+cartID+'"]').val();
        let val_str = 'accompanied_dog|'+cur_value+'|'+accompanied_dog;
        val_arr.push(val_str);  
    }
    /*if ($('.final_cleaning[data-item_key="'+cartID+'"]').is(':checked')) {
        let final_cleaning = $('.final_cleaning[data-item_key="'+cartID+'"]').attr('data-final_cleaning');
        let cur_value = $('.final_cleaning[data-item_key="'+cartID+'"]').val();
        let val_str = 'final_cleaning|'+cur_value+'|'+final_cleaning;
        val_arr.push(val_str);  
    }*/
    const $finalCleaning = $('.final_cleaning[data-item_key="'+cartID+'"]:checked');
    if($finalCleaning.length > 0){
        let final_cleaning = $finalCleaning.attr('data-final_cleaning');
        let cur_value = $finalCleaning.val();
        let val_str = 'final_cleaning|'+cur_value+'|'+final_cleaning;
        val_arr.push(val_str);
        if($finalCleaning.hasClass('final_cleaning_rut')){
            val_str = 'final_cleaning_rut|0|'+final_cleaning;
            val_arr.push(val_str);
        }
    }
    if ($('.cancellation[data-item_key="'+cartID+'"]').is(':checked')) {
        let cancellation = $('.cancellation[data-item_key="'+cartID+'"]').attr('data-cancellation');
        let cur_value = $('.cancellation[data-item_key="'+cartID+'"]').val();
        let val_str = 'cancellation|'+cur_value+'|'+cancellation;
        val_arr.push(val_str);  
    }
    let val_str = val_arr.join(';');
    
    update_options(cartID,val_str);
    // $('[name="update_cart"]').removeAttr('disabled');
    // $('[name="update_cart"]').removeAttr('aria-disabled');
    // $('[name="update_cart"]').trigger('click');
});

$("body").on('change','.woocommerce-cart-form .foreign_guests', function (e) {
    let sum = '';
    $( ".foreign_guests" ).each(function() {
        let value = $( this ).val();
        let name = $(this).attr('name');
        value = parseFloat(value);

        if ($(this).prop('checked')) {
            Cookies.set(name, value, { expires: 1, path: '/' });
            console.log(name);
            sum = sum+value;
        }else{
            Cookies.remove(name);
            console.log('no');
        }
    });
    if (sum != '') {
        Cookies.set('foreign_guests', sum, { expires: 1, path: '/' });
    }else{
        Cookies.remove('foreign_guests');
    }

    $('[name="update_cart"]').removeAttr('disabled');
    $('[name="update_cart"]').removeAttr('aria-disabled');
    $('[name="update_cart"]').trigger('click');
});


$("body").on('change','.woocommerce-checkout [name="accompanied_dog"]', function (e) {
    let value = $(this).val();
    // add data-id-house
    
    if ($(this).prop('checked')) {
        Cookies.set('accompanied_dog', value, { expires: 1, path: '/' });
    }else{
        Cookies.remove('accompanied_dog');
    }
    $('#ship-to-different-address-checkbox').trigger('click');
    $('#ship-to-different-address-checkbox').trigger('click');
});

$("body").on('change','.woocommerce-checkout [name="cancellation"]', function (e) {
    let value = $(this).val();
    
    if ($(this).prop('checked')) {
        Cookies.set('cancellation', value, { expires: 1, path: '/' });
    }else{
        Cookies.remove('cancellation');
    }
    $('#ship-to-different-address-checkbox').trigger('click');
    $('#ship-to-different-address-checkbox').trigger('click');
});

$("body").on('change','.woocommerce-checkout [name="final_cleaning"]', function (e) {
    let value = $(this).val();
    
    if ($(this).prop('checked')) {
        Cookies.set('final_cleaning', value, { expires: 1, path: '/' });
    }else{
        Cookies.remove('final_cleaning');
    }
    $('#ship-to-different-address-checkbox').trigger('click');
    $('#ship-to-different-address-checkbox').trigger('click');
});

$("body").on('change','.woocommerce-checkout [name="foreign_guests"]', function (e) {
    let value = $(this).val();

    if ($(this).prop('checked')) {
        Cookies.set('foreign_guests', value, { expires: 1, path: '/' });
    }else{
        Cookies.remove('foreign_guests');
    }
    $('#ship-to-different-address-checkbox').trigger('click');
    $('#ship-to-different-address-checkbox').trigger('click');
});

// let adult_min = 1
// let adult_max = 10
// let child_max = 9
// let max_peple = 10


$('body').on('click','.minus-client', function () {
    let type = $(this).attr('data-type')
    let inp_id = $(this).attr('data-client')
    let adult_min = 1
    let adult_max = parseInt($('#num-adult-'+inp_id+'').attr('data-max'))

    let max_peple = adult_max + parseInt($('#num-child-'+inp_id+'').attr('data-max'))
    let child_max = max_peple - 1
    let num_adult = $('#num-adult-'+inp_id+'').val()
    let num_child = $('#num-child-'+inp_id+'').val()
    let cartID = $(this).attr('data-cart')

    // let cartID = $('#cart-item-key').data('key')
    console.log(cartID)

    let num = 1;
    if (type === 'adult'){
        if (num_adult > adult_min ){
            if ((parseInt(num_adult) + parseInt(num_child)) <= parseInt(max_peple)){
                num = parseInt(num_adult) - 1
                $('#num-adult-'+inp_id+'').val(num)  
                update_personsA(cartID,num)          
            }
        }
    }
    if (type === 'child'){
        if (num_child > 0 ){
            if ((parseInt(num_adult) + parseInt(num_child)) <= parseInt(max_peple)){
                num = parseInt(num_child) - 1
                $('#num-child-'+inp_id+'').val(num)
                update_personsC(cartID,num)
            }
        }
    }
    
})
$('body').on('click','.plus-client',function () {
    let type = $(this).attr('data-type')
    let inp_id = $(this).attr('data-client')
    let adult_min = 1
    let adult_max = parseInt($('#num-adult-'+inp_id+'').attr('data-max'))
    let max_peple = adult_max + parseInt($('#num-child-'+inp_id+'').attr('data-max'))
    let num_adult = parseInt($('#num-adult-'+inp_id+'').val())
    let num_child = parseInt($('#num-child-'+inp_id+'').val())
    let child_max = max_peple - 1

    let cartID = $(this).attr('data-cart')
    // let cartID = $('#cart-item-key').data('key')
    console.log(cartID)
    
    let num = 1;
    if (type === 'adult'){
        if (num_adult >= adult_min && num_adult < adult_max){
            if ((num_adult + num_child) < max_peple){
                num = parseInt(num_adult) + 1
                $('#num-adult-'+inp_id+'').val(num)
                update_personsA(cartID,num)
            }

        }
    }
    if (type === 'child'){
        if (num_child >= 0 && num_child < child_max){
            if ((parseInt(num_adult) + parseInt(num_child)) < parseInt(max_peple)){
                num = parseInt(num_child) + 1
                $('#num-child-'+inp_id+'').val(num)
                update_personsC(cartID,num)
            }

        }
    }
    
    
    
})

function update_personsA(cartID,num){
    jQuery.ajax({
        type: 'post',
        url: wc_add_to_cart_params.ajax_url,
        data: {cart_id: cartID, quantity: num, action:'update_personsA'},
        success: function(response){
            console.log(response)
            $('[name="update_cart"]').removeAttr('disabled');
            $('[name="update_cart"]').removeAttr('aria-disabled');
            $('[name="update_cart"]').trigger('click');
        }
    });
}
function update_personsC(cartID,num){
    jQuery.ajax({
        type: 'post',
        url: wc_add_to_cart_params.ajax_url,
        data: {cart_id: cartID, quantity: num, action:'update_personsC'},
        success: function(response){
            console.log(response)
            $('[name="update_cart"]').removeAttr('disabled');
            $('[name="update_cart"]').removeAttr('aria-disabled');
            $('[name="update_cart"]').trigger('click');
        }
    });
}
function update_options(cartID,options){
    jQuery.ajax({
        type: 'post',
        url: wc_add_to_cart_params.ajax_url,
        data: {cart_id: cartID, options: options, action:'update_options'},
        success: function(response){
            console.log(response)
            $('[name="update_cart"]').removeAttr('disabled');
            $('[name="update_cart"]').removeAttr('aria-disabled');
            $('[name="update_cart"]').trigger('click');
        }
    });
}
// $('#plus-adult').on('click', function () {
//     let num_adult = $('#num-adult').val()
//     let num_child = $('#num-child').val()
//     if (num_adult >= adult_min && num_adult < adult_max){
//         if ((parseInt(num_adult) + parseInt(num_child)) < parseInt(max_peple)){
//             num = parseInt(num_adult) + 1
//             $('#num-adult').val(num)
//         }
//
//     }
// })
// $('#minus-adult').on('click', function () {
//     let num_adult = $('#num-adult').val()
//     let num_child = $('#num-child').val()
//     if (num_adult > adult_min ){
//         if ((parseInt(num_adult) + parseInt(num_child)) <= parseInt(max_peple)){
//             num = parseInt(num_adult) - 1
//             $('#num-adult').val(num)
//         }
//
//     }
// })
//
// $('#plus-child').on('click', function () {
//     let num_adult = $('#num-adult').val()
//     let num_child = $('#num-child').val()
//     if (num_child >= 0 && num_child < child_max){
//         // console.log((num_adult + num_child) < max_peple)
//         if ((parseInt(num_adult) + parseInt(num_child)) < parseInt(max_peple)){
//             num = parseInt(num_child) + 1
//             $('#num-child').val(num)
//         }
//
//     }
// })
// $('#minus-child').on('click', function () {
//     let num_adult = $('#num-adult').val()
//     let num_child = $('#num-child').val()
//     if (num_child > 0 ){
//         if ((parseInt(num_adult) + parseInt(num_child)) <= parseInt(max_peple)){
//             num = parseInt(num_child) - 1
//             $('#num-child').val(num)
//         }
//
//     }
// })

//======================================= add deposit

$('#deposit_btn').on('click', function (){
    let cart_id = $('#deposit_cart_id').val()

    jQuery.ajax({
        type: 'post',
        url: wc_add_to_cart_params.ajax_url,
        data: {cart_id: cart_id, action:'depositOrderAdds'},
        success: function(response){
            console.log(response)
            //site_url + '/wp-admin/admin-ajax.php'
        }
    });
})