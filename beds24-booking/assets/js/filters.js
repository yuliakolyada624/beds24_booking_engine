$('#btn-filter').on('click', function () {
    var btn = $('#filters-body');
    if (btn.css('display') === 'none'){
        btn.css('display','flex')
    } else {
        btn.css('display','none')

    }
})

$('.filter-wrap-block').on('click', function (){
    if ($(this).hasClass('active-filter') === false){
        if ($(this).attr('id') === 'dog-enable'){
            $("#animals").prop('checked', true);
        }
        $(this).addClass('active-filter');
    } else {
        if ($(this).attr('id') === 'dog-enable'){
            $("#animals").prop('checked', false);
        }

            $(this).removeClass('active-filter')
    }
})

jQuery("#btn-map").on('click', function () {
    jQuery('.searh-item-wrap').css('display','none')
    jQuery('.pagination_content').css('display','none')
    jQuery("#map").css('display','block')
    jQuery("#btn-map").css('display','none')
    jQuery("#btn-lista").css('display','block')

})

jQuery("#btn-lista").on('click', function () {
    jQuery('.searh-item-wrap').css('display','flex')
    jQuery('.pagination_content').css('display','block')
    jQuery("#map").css('display','none')
    jQuery("#btn-map").css('display','block')
    jQuery("#btn-lista").css('display','none')
})