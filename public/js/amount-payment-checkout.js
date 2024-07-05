jQuery(function($){
    var payment = $("#payment_amount");


    function setPayment() {
        var paymentAmount = $("#payment_amount").val();
        var paymentHidden = $("#verify_payment_amount");
        console.log(paymentHidden);
        $.ajax({
        type : "post",
            dataType : "json",
            url : payCheckout.ajaxurl,
            data : {action: "amount_payment_checkout", nonce:payCheckout.nonce, payment_amount:paymentAmount},
            success: function(response) {
            console.log(response)
            if(response.type == "success") {
                paymentHidden.val(response.pay);
                $('body').trigger('update_checkout');
            }
            },
            error: function(err) {
                console.log(err);
            }
        });

    }

    payment.on('change', function(e) {
        setPayment();
    });





    payment.on('keypress', function(e) {
        if(e.which == 13) {
            setPayment();
        }
    });
});