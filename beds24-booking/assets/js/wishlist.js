$('#favlist').on('click', function () {

  let disp = $('.wishlist-wrap').css('display')



  if (disp === 'none') {

    $('.wishlist-wrap').css('display', 'block')

  } else {

    $('.wishlist-wrap').css('display', 'none')

  }

})



$(document).on('click', '.add-to-favorites', function (e) {
  e.stopPropagation();
  let id = $(this).attr('data-id')
  $.ajax({
    type: 'POST',
    url: site_url + '/wp-admin/admin-ajax.php',
    data: {
      product_id: id,
      action: 'setFavSess'
    },
    success: function (data) {
      let resp = data.slice(0, -1)
      if (resp === 'ADD') {
        $(".add-to-favorites").find(`[data-id='${id}-b']`).css('display', 'none')
        $(".add-to-favorites").find(`[data-id='${id}-r']`).css('display', 'block')
        $(".wishlist-wrap").load(window.location.href + " .wishlist-wrap > * ");
      }
      if (resp === 'DEL') {
        $(".add-to-favorites").find(`[data-id='${id}-b']`).css('display', 'block')
        $(".add-to-favorites").find(`[data-id='${id}-r']`).css('display', 'none')
        $(".wishlist-wrap").load(window.location.href + " .wishlist-wrap > * ");
      }
    }
  })
})



$(document).on('click', '.del-div', function () {
  let id = $(this).attr('data-id')
  $.ajax({
    type: 'POST',
    url: site_url + '/wp-admin/admin-ajax.php',
    data: {
      product_id: id,
      action: 'delFavSess'
    },
    success: function (data) {
      let resp = data.slice(0, -1)
      if (resp === 'DEL') {
        $(".wishlist-wrap").load(window.location.href + " .wishlist-wrap > * ");
        $(".add-to-favorites").find(`[data-id='${id}-b']`).css('display', 'block')
        $(".add-to-favorites").find(`[data-id='${id}-r']`).css('display', 'none')
      }
    }
  })
})