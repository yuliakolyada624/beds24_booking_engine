let site_url = document.location.origin;
let locale = $('.locale').html();
if(locale == 'sv_SE'){
    locale = 'sv-SE';
}else{
    locale = 'en-US';
}

let unavailDates = []

let product_id = $('.beds_add_to_cart').attr('data-product_id');
let av_days = [];
$.ajax({
    type: 'POST',
    url: site_url + '/wp-admin/admin-ajax.php',
    data: {
        product_id: product_id,
        action: 'getAvailByRoomID'
    },
    cache: false,
    error: function(error){
        alert(error);
    },
    success: function(data){
        data = $.parseJSON(data.slice(0,-1));
        av_days = data;
        
    }
});


let bookedDays = [];
$.ajax({
    type: 'POST',
    url: site_url + '/wp-admin/admin-ajax.php',
    data: {
        product_id: product_id,
        action: 'getPeriodByRoomID'
    },
    cache: false,
    error: function(error){
        alert(error);
    },
    success: function(data){
        data = $.parseJSON(data.slice(0,-1));
        bookedDays = data;

    }
});
$.each(bookedDays, function(key, val) {
    var dates1 = val.split("-");
    var newDate = dates1[1]+"/"+dates1[2]+"/"+dates1[0];
    let tsDatebooked = new Date(newDate).getTime()
    // $('.container__days .day-item').data('time')
    if(tsDatebooked > today){
        // $('*[data-time="'+tsDatebooked+'"]').addClass('is-locked')
        // $('*[data-time="'+tsDatebooked+'"]').attr('tabindex',"-1");      
        $('*[data-time="'+tsDatebooked+'"]').css('background',"#fff");
        $('*[data-time="'+tsDatebooked+'"]').css('color',"#fff");

    }
    $(this).attr('onclick',"triger()");
});

// $("body").on('click','.add-to-cart', function (e) {
//     alert(333333)
//     e.preventDefault();
//     let custom_price = $(this).attr('data-custom_price');
//     let product_id = $(this).attr('data-product_id');
//     let add_button = $(this);
//     let date_from = $("#date-start").val()
//     let date_to = $("#date-end").val()
//     let persons = $("#adult").val()
//
//     console.log(product_id);
// })


$("body").on('click','.beds_add_to_cart', function (e) {
    if ($('.beds_add_to_cart').hasClass( "notBuy" ).toString() === 'false'){
        e.preventDefault();
        let custom_price = $(this).attr('data-custom_price');
        let product_id = $(this).attr('data-product_id');
        let add_button = $(this);
        let date_from = $("#startDateNew").val()
        let date_to = $("#endDateNew").val()
        let persons = $("#adult").val()

        let personsA = $("#num-adult").val()
        let personsC = $("#num-child").val()
        let url = window.location.href
        url = url.split('/')


        let dog = $('#animals').is(':checked');
        const ajaxFormData = {
            product_id,
            custom_price,
            date_from,
            date_to,
            personsA,
            personsC,
            dog,
            action: 'addtocart'
        };
        const accompanied_dog = $('#animals');
        if(accompanied_dog.length > 0 && accompanied_dog.is(':checked')){
            ajaxFormData.accompanied_dog = accompanied_dog.val()
        }
        console.log(date_from);
        console.log(date_to);

        $.ajax({
            type: 'POST',
            url: site_url + '/wp-admin/admin-ajax.php',
            data: ajaxFormData,
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
                // console.log(data[0])
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

                }

            }
        }); //endajax
    } else {
        return false;
    }

});

$('.slider').slick({
  dots: false,
  arrows:true,
  infinite: true,
  speed: 500,
  fade: true,
  cssEase: 'linear'
});

let now = new Date();
today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

new Litepicker({
    element: document.getElementById('date-3_1'),
    // elementEnd: document.getElementById('date-end'),
    delimiter: ' → ',
    format: "D MMM",
    singleMode: false,
    showWeekNumbers: true,
    numberOfMonths: 2,
    minDate: today,
    tooltipText:{"one":"night","other":"nights"},
    resetButton:false,
    lang:locale,
    buttonText:{"cancel":"Cancel","reset":"Reset"},
    tooltipNumber: (totalDays) => {
        return totalDays - 1;
    },
    disallowLockDaysInRange: true,
    lockDaysFilter: (date1, date2, pickedDates) => {
        return !av_days.includes(date1.format('YYYY-MM-DD'));
      },
    position: 'left'
});


// $("body").on('click','#date-3_1', function () {
//     addAvailable();
// });
$("body .month-item").on('click', function () {
    addAvailable();
});
$("body").on('click','.day-item', function () {
    // alert('test');
    addAvailable();
});
$("body").on('click','.day-item', function () {
    // alert('test');
    addAvailable();
});
$("body").on('mouseover','.day-item', function () {
    addAvailable();
});

function addAvailable(){

        let start_date = $('.is-start-date').html();
        let now = new Date();
        let today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        let e = $(this);
        let time = $(e).attr('data-time');
        let time_int = parseInt(time);
        const date = new Date(time_int);
        const date_min = new Date(time_int);

        let datePlus,dateMin,days=4,i=0,dates_arr = [];
        $.each(bookedDays, function(key, val) {
            var dates1 = val.split("-");
            var newDate = dates1[1]+"/"+dates1[2]+"/"+dates1[0];
            let tsDatebooked = new Date(newDate).getTime()
            // $('.container__days .day-item').data('time')
            if(tsDatebooked > today){
                // $('*[data-time="'+tsDatebooked+'"]').addClass('is-locked')
                // $('*[data-time="'+tsDatebooked+'"]').attr('tabindex',"-1");
                $('*[data-time="'+tsDatebooked+'"]').css('background',"#F7F9FC");
                $('*[data-time="'+tsDatebooked+'"]').css('color',"#BFBFBF").css('text-decoration', 'line-through');
            }
        });
        function interval() {
            let time = $(e).attr('data-time');
            let time_int = parseInt(time);
            const date = new Date(time_int);
            const date_min = new Date(time_int);
            let datePlus,dateMin,days=4,i=0,dates_arr = [];
            $.each(bookedDays, function(key, val) {
                var dates1 = val.split("-");
                var newDate = dates1[1]+"/"+dates1[2]+"/"+dates1[0];
                let tsDatebooked = new Date(newDate).getTime()
                // $('.container__days .day-item').data('time')
                if(tsDatebooked > today){
                    // $('*[data-time="'+tsDatebooked+'"]').addClass('is-locked')
                    // $('*[data-time="'+tsDatebooked+'"]').attr('tabindex',"-1");
                    $('*[data-time="'+tsDatebooked+'"]').css('background',"#F7F9FC");
                    $('*[data-time="'+tsDatebooked+'"]').css('color',"#BFBFBF").css('text-decoration', 'line-through');
                }
            });
        }
        setTimeout(interval, 1); 
}

function previosMonth(e){
    addAvailable();
}
function nextMonth(e){
    addAvailable();
}

setInterval(function() {
    addAvailable();
}, 100);



$("body").on('click','#date-3_1', function () {
    let css = $('.litepicker').css('left');
    let winWidth = $(window).width();
    if (winWidth < '1440' & winWidth > 660){
        let newCss = css.split('px')
        let newLeft = newCss[0]-150;
        newCss = newLeft+'px'
        let p = newCss.split('px')
        if (p[0] > (winWidth/2)){
            $('.litepicker').css('left',newCss);
        }
    }
})
$("body").on('click','#date-end', function () {
    let css = $('.litepicker').css('left');
    let winWidth = $(window).width();
    if (winWidth < '1440'){
        let newCss = css.split('.')
        let newLeft = newCss[0]-150;
        newCss = newLeft+'.'+newCss[1]
        let p = newCss.split('px')

        if (p[0] > (winWidth/2)){
            $('.litepicker').css('left',newCss);
        }
    }
})


let checkin = 'Tillträdesdatum'
let checkout = 'Avresedatum'
let url = window.location.href
url = url.split('/')
console.log(url)
if (url[3] === 'accommodation'){
    checkin = 'Arrival date'
    checkout = 'Departure date'
}

$( 'body' ).on('click',function () {
    setTimeout(function () {
        $('body').find('.litepicker .container__main').append('<div class="wrap-calendar-info  fix-prod-info-wrap"><a disabled href="#" class="btn-calendar-info-start-prod">'+checkin+'</a><a href="#" disabled class="btn-calendar-info-end-prod btn-start-inactiv-prod">'+checkout+'</a></div>')
    }, 10)
})

$('body').on('click', function () {
    setTimeout(function () {
        let datetime = $('body').find('.litepicker .container__days .day-item.is-start-date').data('time')
        var s = new Date(parseInt(datetime)).toLocaleDateString("en-US")
        if (s !== 'Invalid Date'){
            $('.btn-calendar-info-start-prod').addClass('btn-start-inactiv-prod').html(s)
            $('.btn-calendar-info-end-prod').removeClass('btn-start-inactiv-prod')
            if (s.includes('/')){
                var dateParts = s.split('/'); // Split the date into parts (YYYY, MM, DD)
                var formattedDate = dateParts[2] + '/' + dateParts[0] + '/' + dateParts[1];
                // console.log(dateParts)
                // console.log(formattedDate)
                $('#startDateNew').val(formattedDate)
            }
// else {
//                 console.log(s)
            //     $('#startDateNew').val(s)
            // }
            // $('#startDateNew').val(s)
        }
    }, 10)

    setTimeout(function () {
        let datetime = $('body').find('.litepicker .container__days .day-item.is-start-date').data('time')
        let datetime_end = $('body').find('.litepicker .container__days .day-item.is-end-date').data('time')

        if (datetime !== datetime_end ){
            var endDate = new Date(parseInt(datetime_end)).toLocaleDateString("en-US")
            if (endDate !== 'Invalid Date'){
                console.log(endDate)
                $('.btn-calendar-info-end-prod').addClass('btn-start-inactiv-prod').html(endDate)
                if (endDate.includes('/')){
                    var dateParts = endDate.split('/'); // Split the date into parts (YYYY, MM, DD)
                    // console.log(dateParts)
                    var formattedDateEnd = dateParts[2] + '/' + dateParts[0] + '/' + dateParts[1];
                    $('#endDateNew').val(formattedDateEnd)
                }
                // else {
                //     $('#endDateNew').val(endDate)
                // }
                // $('#endDateNew').val(endDate)
            }

            let dateStart = jQuery('#startDateNew').val()
            let dateEnd = jQuery('#endDateNew').val()
            let getDateEnd = getSearchParams('date_end');
            let getDateStart = getSearchParams(('date_start'))
            let urlGo = document.location.href.replace(document.location.search,'');
            let adult = jQuery('#num-adult').val()
            let child = jQuery('#num-child').val()
            let animals = jQuery('#animals').val()
            var re = setInterval(function() {if ((getDateEnd !== dateEnd) || (getDateStart !== dateStart)){
                $('.beds_add_to_cart').addClass('notBuy');
                if (url[3] === 'accommodation'){
                    window.location.href = urlGo+'?date_start='+dateStart+'&date_end='+dateEnd+'&number-adult='+adult+'&number-child='+child+"&animals="+animals+'&lang=en';
                } else {
                    window.location.href = urlGo+'?date_start='+dateStart+'&date_end='+dateEnd+'&number-adult='+adult+'&number-child='+child+"&animals="+animals;
                }
                clearInterval(re)
            };}, 500);
        }


    }, 10)

    // const element = document.getElementById("endDateNew");
// const elementS = document.getElementById("startDateNew");
// const elementA = document.getElementById("num-adult");
// const elementC = document.getElementById("num-child");
// const pet = document.getElementById("animals");

// let dateEnd = element.value;
// let dateStart = elementS.value;
// let urlGo = document.location.href.replace(document.location.search,'');
// var interval = setInterval(function() {if ((element.value !== dateEnd) || (elementS.value !== dateStart)){
//     console.log(element.value)
//     console.log(elementS.value)
//     console.log(elementA.value);
//
//     $('.beds_add_to_cart').addClass('notBuy');
//     window.location.href = urlGo+'?date_start='+elementS.value+'&date_end='+element.value+'&number-adult='+elementA.value+'&number-child='+elementC.value+"&animals="+pet.value;
//     clearInterval(interval)
// };}, 500);

    function getSearchParams(k){
        var p={};
        location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
        return k?p[k]:p;
    }


//     jQuery('#endDateNew').on('change', function (e) {
//         let dateStart = jQuery('#startDateNew').val()
//         let dateEnd = jQuery('#endDateNew').val()
//         let getDateEnd = getSearchParams('date_end');
//         let getDateStart = getSearchParams(('date_start'))
//         let urlGo = document.location.href.replace(document.location.search,'');
//
//         console.log(dateStart)
//         console.log(getDateStart)
//         console.log(dateEnd)
//         console.log(getDateEnd)
//
//         var re = setInterval(function() {if ((getDateEnd !== dateEnd) || (getDateStart !== dateStart)){
//     $('.beds_add_to_cart').addClass('notBuy');
//     window.location.href = urlGo+'?date_start='+elementS.value+'&date_end='+element.value+'&number-adult='+elementA.value+'&number-child='+elementC.value+"&animals="+pet.value;
//     clearInterval(re)
// };}, 500);
//     })

    // setTimeout(function () {
    //     let datetime = $('body').find('.litepicker .container__days .day-item.is-end-date').data('time')
    //     var s = new Date(parseInt(datetime)).toLocaleDateString("en-US")
    //     if (s !== 'Invalid Date'){
    //         $('.btn-calendar-info-end').addClass('btn-start-inactiv-prod').html(s)
    //     }
    // }, 10)
})