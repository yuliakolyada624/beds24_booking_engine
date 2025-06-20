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
console.log(arr)
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
        lockDaysInclusivity: "[]",
        lockDays: arr,

        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        position: 'left'
    })
            // jQuery('.d_s_new').addClass('d_s_new_select');
    }
});

/*$( "#date-3_1" ).on( "change", function() {
    alert( "Handler for `change` called." );
} );

var activities = document.getElementById("date-3_1");
activities.addEventListener("change", function() {
    if(activities.value != "")
    {
        element.classList.add("my-class");
        jQuery('.d_s_new').addClass('d_s_new_select');
    }
});*/

// $( '#date-3_2' ).on('click',function () {
//     setTimeout(function () {
//         let apend = $('body').find("#test")
//         if (apend.length < 1){
//             $('body').find('.litepicker .container__main .container__months').append('<div class="plusDay"><input type="radio" value="Exakta datum" id="0day" name="plusDays"><label for="0day">Exakta datum</label></div>')
//         }
//     }, 100)})
// $('.litepiker').on('click',function () {
//     setTimeout(function () {
//         let apend = $('body').find("#test")
//         if (apend.length < 1){
//             $('body').find('.litepicker .container__main .container__months').append('<div class="plusDay"><input type="radio" value="Exakta datum" id="0day" name="plusDays"><label for="0day">Exakta datum</label></div>')
//         }
//     }, 100)})


/*
work
 */
// $( 'body' ).on('click',function () {
//     setTimeout(function () {
//             $('body').find('.litepicker .container__main .container__months').append('<div class="plusDay"><input type="radio" value="Exakta datum" id="0day" name="plusDays"><label for="0day">Exakta datum</label></div>')
//         }, 10)
// })


$('body').on('click','.filter-wrap-block', function(){
    setTimeout(filter_products, 1);
});
$('body').on('change','.range-filter', function(){
    setTimeout(filter_products, 1);
});

function filter_products(){
    let sovrum = $('#sovrum').val();
    let skidlift = $('#skidlift').val();
    let param1 = [];
    let param2 = [];
    $( ".top_blocks .filter-case .active-filter" ).each(function() {
        let data = $(this).attr('data-item');
        param1.push(data);
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
    // if (display === 'block'){
    //     $(".form-clients").css('display','none')
    // }
})


let checkin = 'Tillträdesdatum'
let checkout = 'Avresedatum'
let url = window.location.href
url = url.split('/')
console.log(url)
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
        var s = new Date(parseInt(datetime)).toLocaleDateString("en-CA")
        if (s !== 'Invalid Date') {
            // console.log(s)

            $('.btn-calendar-info-start').addClass('btn-start-inactiv').html(s)
            $('.btn-calendar-info-end').removeClass('btn-start-inactiv')
            var dateParts = s.split('-'); // Split the date into parts (YYYY, MM, DD)
            var formattedDate = dateParts[0] + '/' + dateParts[1] + '/' + dateParts[2];
            // console.log(dateParts)
            // console.log(formattedDate)
            $('#startDateNew').val(formattedDate)
        }
    }, 10)

    setTimeout(function () {
        let datetime = $('body').find('.litepicker .container__days .day-item.is-start-date').data('time')
        let datetime_end = $('body').find('.litepicker .container__days .day-item.is-end-date').data('time')
        
        if (datetime !== datetime_end ){
            var endDate = new Date(parseInt(datetime_end)).toLocaleDateString("en-CA")
            if (endDate !== 'Invalid Date'){
                // console.log(endDate)

                $('.btn-calendar-info-end').addClass('btn-start-inactiv').html(endDate)
                var dateParts = endDate.split('-'); // Split the date into parts (YYYY, MM, DD)
                var formattedDateEnd = dateParts[0] + '/' + dateParts[1] + '/' + dateParts[2];
                // console.log(dateParts)
                // console.log(formattedDateEnd)
                $('#endDateNew').val(formattedDateEnd)
            }
        }

    }, 10)


    // jQuery('#date-3_1').on('change', function (e) {
    //     console.log(e)
    //     console.log(jQuery(this).val())
    // })
})