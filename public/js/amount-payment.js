jQuery(function ($) {
  $(".add_to_cart_payment").on("click", function (e) {
    $.ajax({
      type: "post",
      dataType: "json",
      url: payCart.ajaxurl,
      data: { action: "amount_payment_cart", nonce: payCart.nonce },
      success: function (response) {
        if (response.type == "success") {
          $(location).attr("href", response.redirect);
        }
      },
      error: function (err) {
        console.error(err);
      },
    });
  });
});
