let site_url = document.location.origin;
let now = new Date();
today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
let locale = $('.locale').html();
if(locale == 'sv_SE'){
    locale = 'sv-SE';
}else{
    locale = 'en-US';
}

let arr;
$.ajax({
    type: 'POST',
    url: site_url + '/wp-admin/admin-ajax.php',
    data: {
        action: 'getAllowDatesLitepicker'
    },
    error: function(error){
        alert('error');
    },
    success: function(data){
        // arr = jQuery.parseJSON(data.slice(0,-1));
        arr = jQuery.parseJSON(data);
// console.log(arr)
        new Litepicker({
        element: document.getElementById('date-3_1'),
            delimiter: ' → ',
            format: "D MMM",
        // elementEnd: document.getElementById('date-3_2'),
        singleMode: false,
        showWeekNumbers: true,
        numberOfMonths: 2,
        minDate: today,
        tooltipText:{"one":"natt","other":"nätter"},
        resetButton:false,
        buttonText:{"cancel":"Cancel","reset":"Reset"},
        lang:locale,
        lockDaysInclusivity: "()",
        lockDays: arr,

        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        position: 'left'
    })
    }
});
let url = window.location.href
url = url.split('/')
console.log(url)

$("body").on('click','.beds_add_to_cart', function (e) {
    e.preventDefault();
    let custom_price = $(this).attr('data-custom_price');
    let product_id = $(this).attr('data-product_id');
    let add_button = $(this);
    let date_from = $("#startDateNew").val()
    let date_to = $("#endDateNew").val()
    let persons = $("#adult-select").val()
    let personsA = $("#num-adult").val()
    let personsC = $("#num-child").val()
    let dog = $('#animals').is(':checked');


    $.ajax({
        type: 'POST',
        url: site_url + '/wp-admin/admin-ajax.php',
        data: {
            product_id: product_id,
            custom_price: custom_price,
            date_from:date_from,
            date_to:date_to,
            personsA:personsA,
            personsC:personsC,
            dog:dog,
            action: 'addtocart'
        },
        dataType: "json",
        cache: false,
    error: function(error){

        alert('error');
        $('.backmodal').remove();
        },
    beforeSend: function(){
            $('body').append('<div class="backmodal"><div></div></div>');
        },
    success: function(data){
            $('.backmodal').remove();
            if (data[0] === 'limit'){
                alert("Unfortunately, it is possible to add no more than 3 objects to the basket. To book more objects, contact the administration.")
            } else {
                $(add_button).closest('.content_bottom').children('.result').addClass('active');
                $(add_button).closest('.buy_button').children('.result').addClass('active');
                if (url[3] === 'accommodation'){
                    location = site_url+"/index.php/cart/?lang=en";
                } else {
                    location = site_url+"/index.php/cart/";
                }
                // location = site_url+"/index.php/cart/";
            }

            
        }
    }); //endajax
});

$('body').on('click','.filter-wrap-block', function(){
    setTimeout(filter_products, 1);

});
$('body').on('change','.inputRange', function(){
    setTimeout(filter_products, 1);
});
$("#animals").on('change',function () {
    setTimeout(filter_products, 1);

    dog = $("#animals").prop('checked');
    if (dog === true){
        $(document).find("#dog-enable").addClass('active-filter')
    }
    if (dog === false){
        $(document).find("#dog-enable").removeClass('active-filter')
    }

    let dog_enable = $('body').find("#dog-enable").hasClass('active-filter')
    // console.log(dog_enable)

    if (dog_enable === true){
        $('body').find("#dog-enable").removeClass('active-filter')
    }
})



function filter_products(){
    let sovrum = $('#sovrum').val();
    let skidlift = $('#skidlift').val();
    let param1 = [];
    let param2 = [];
    let dog = $("#animals").is(':checked')
    // console.log(dog)

    if (dog === true){
        param1.push('_product_hundtillåtet');
    }


    $( ".top_blocks .filter-case .active-filter" ).each(function() {
        let data = $(this).attr('data-item');
        param1.push(data);
        if (data === '_product_hundtillåtet'){
            $("#animals").prop('checked', true);
        } else {
            $("#animals").prop('checked', false);
        }
    });
    $( ".bottom_blocks .filter-case .active-filter" ).each(function() {
        let data = $(this).attr('data-item');
        param2.push(data);
    });
    let param1String = param1.join(';');
    let param2String = param2.join(';');

    if(sovrum != ''){
        $('#start_form .sovrum_input').remove();
        $('#start_form').append('<input class="sovrum_input" name="sovrum" type="hidden" value="'+sovrum+'">');
    }
    if(skidlift != ''){
        $('#start_form .skidlift_input').remove();
        $('#start_form').append('<input class="skidlift_input" name="skidlift" type="hidden" value="'+skidlift+'">');
    }
    if(param1String != ''){
        $('#start_form .param1String_input').remove();
        $('#start_form').append('<input class="param1String_input" name="parstring1" type="hidden" value="'+param1String+'">');
    }else{
        $('#start_form .param1String_input').remove();
    }
    if(param2String != ''){
        $('#start_form .param2String_input').remove();
        $('#start_form').append('<input class="param2String_input" name="parstring2" type="hidden" value="'+param2String+'">');
    }else{
        $('#start_form .param2String_input').remove();
    }
}



let adult_min = 1
let adult_max = 10
let child_max = 9
let max_peple = 10

$('#plus-adult').on('click', function () {
    let num_adult = $('#num-adult').val()
    let num_child = $('#num-child').val()
    if (num_adult >= adult_min && num_adult < adult_max){
        if ((parseInt(num_adult) + parseInt(num_child)) < parseInt(max_peple)){
            num = parseInt(num_adult) + 1
            $('#num-adult').val(num)
        }

    }
})
$('#minus-adult').on('click', function () {
    let num_adult = $('#num-adult').val()
    let num_child = $('#num-child').val()
    if (num_adult > adult_min ){
        if ((parseInt(num_adult) + parseInt(num_child)) <= parseInt(max_peple)){
            num = parseInt(num_adult) - 1
            $('#num-adult').val(num)
        }

    }
})

$('#plus-child').on('click', function () {
    let num_adult = $('#num-adult').val()
    let num_child = $('#num-child').val()
    if (num_child >= 0 && num_child < child_max){
        // console.log((num_adult + num_child) < max_peple)
        if ((parseInt(num_adult) + parseInt(num_child)) < parseInt(max_peple)){
            num = parseInt(num_child) + 1
            $('#num-child').val(num)
        }

    }
})
$('#minus-child').on('click', function () {
    let num_adult = $('#num-adult').val()
    let num_child = $('#num-child').val()
    if (num_child > 0 ){
        if ((parseInt(num_adult) + parseInt(num_child)) <= parseInt(max_peple)){
            num = parseInt(num_child) - 1
            $('#num-child').val(num)
        }

    }
})


// $(document).on('click', function (e){
//     console.log(e.target)
//
// })
$(document).mouseup(function(e)
{
    var container = $(".form-clients");

    if (!container.is(e.target) && container.has(e.target).length === 0)
    {
        container.hide();
    }
});

$('#adult-select').on('click',function () {
    var display = $(".form-clients").css('display')
    if (display === 'none'){
        $(".form-clients").css('display','block')
    }

})


let checkin = 'Tillträdesdatum'
let checkout = 'Avresedatum'
// let url = window.location.href
// url = url.split('/')
// console.log(url)
if (url[3] === 'en'){
    checkin = 'Arrival date'
    checkout = 'Departure date'
}


$( 'body' ).on('click',function () {
    setTimeout(function () {
        $('body').find('.litepicker .container__main').append('<div class="wrap-calendar-info"><button disabled class="btn-calendar-info-start">'+checkin+'</button><button disabled class="btn-calendar-info-end btn-start-inactiv">'+checkout+'</button></div>')
    }, 10)
})

$('body').on('click', function () {
    setTimeout(function () {
        let datetime = $('body').find('.litepicker .container__days .day-item.is-start-date').data('time')
        var s = new Date(parseInt(datetime)).toLocaleDateString("en-US")
        if (s !== 'Invalid Date'){
            // $('#startDateNew').val(s)
            $('.btn-calendar-info-start').addClass('btn-start-inactiv').html(s)
            $('.btn-calendar-info-end').removeClass('btn-start-inactiv')
            if (s.includes('/')){
                var dateParts = s.split('/'); // Split the date into parts (YYYY, MM, DD)
                var formattedDate = dateParts[2] + '/' + dateParts[0] + '/' + dateParts[1];
                console.log(dateParts)
                // console.log(formattedDate)
                $('#startDateNew').val(formattedDate)
            } else {
                // console.log(s)
                $('#startDateNew').val(s)
            }

        }
    }, 10)

    setTimeout(function () {
        let datetime = $('body').find('.litepicker .container__days .day-item.is-start-date').data('time')
        let datetime_end = $('body').find('.litepicker .container__days .day-item.is-end-date').data('time')

        if (datetime !== datetime_end ){
            var endDate = new Date(parseInt(datetime_end)).toLocaleDateString("en-US")
            if (endDate !== 'Invalid Date'){
                // console.log(endDate)
                // $('#endDateNew').val(endDate)
                $('.btn-calendar-info-end').addClass('btn-start-inactiv').html(endDate)
                if (endDate.includes('/')){
                    var dateParts = endDate.split('/'); // Split the date into parts (YYYY, MM, DD)
                    var formattedDateEnd = dateParts[2] + '/' + dateParts[0] + '/' + dateParts[1];
                    $('#endDateNew').val(formattedDateEnd)
                } else {
                    $('#endDateNew').val(endDate)
                }

            }
        }

    }, 10)
})