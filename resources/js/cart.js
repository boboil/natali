$(function() {

    $('body').on('click', '.add-cart', function (e) {
        e.preventDefault();
        var dataContent = $(this).attr('data-content');


        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url    : "/cart-add",
            data   : {product_id: dataContent},
            success: function (data) {
                /*cart.load("/ .cart-count*","");*/
                $('.cart-count.badge-circle').load("/ #count*","");
                $("#drop-cart").load("/ .dropdownmenu-wrapper*","");
                swal.fire({
                    timer: 1500,
                    title: 'Товар добавлен',
                    icon: 'success',
                    showConfirmButton: false,
                });
            }
        })
    });

    $('body').on('click', '.btn-remove', function (e) {
        e.preventDefault();
        var dataContent = $(this).attr('data-content');
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url    : "/cart-remove",
            data   : {product_id: dataContent},
            success: function (data) {
                $('.cart-count.badge-circle').load("/ #count*","");
                $("#drop-cart").load("/ .dropdownmenu-wrapper*","");

                if(data['msg'] == 'empty') {
                    swal.fire({
                        timer: 4500,
                        title: "Корзина пуста",
                        icon: 'info',
                        showConfirmButton: false,
                    });
                    $(location).attr('href', '/');
                }

                else {
                    $('.cart-count.badge-circle').load("/ #count*","");
                    $("#drop-cart").load("/ .dropdownmenu-wrapper*","");
                    $("#table_cart").html(data);
                }
            }
        })
    });

    $('body').on('click', '.table-cart .cart-btn', function (e) {
        e.preventDefault();
        var dataContent = $(this).attr('data-content');
        var dataAction = $(this).attr('data-action');
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url    : "/cart-update",
            data   : {product_id: dataContent, cart_action: dataAction},
            success: function (data) {

                $('.cart-count.badge-circle').load("/ #count*","");
                $("#drop-cart").load("/ .dropdownmenu-wrapper*","");
                $("#table_cart").html(data);
            }
        })
    });


    $('body').on('click', '#order_btn', function (e) {
        e.preventDefault();

        $("#order_btn").attr("disabled", true);
        var data = $(".order-form").serialize();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/order',
            type: 'POST',
            data: data,
            error: function(msg){
                swal.fire({
                    timer: 2000,
                    title: msg.message,
                    icon: 'error',
                    showConfirmButton: false,
                });
                $("#order_btn").attr("disabled", false);
            },

            success: function(msg){
                if(msg == 'error') {
                    swal.fire({
                        timer: 2000,
                        title: 'Ошибка',
                        icon: 'error',
                        showConfirmButton: false,
                    });
                } else {

                    $("#drop-cart").load(location.href + " #drop-cart");
                    $("#order_cart").load(location.href + " #order_cart");
                    cart.load("/ .summa*","");
                    swal.fire({
                        timer: 1500,
                        title: "Спасибо за заказ!",
                        icon: 'success',
                        showConfirmButton: false,
                    });
                    $('form.order-form')[0].reset();
                    $("#order_btn").attr("disabled", false);
                    if(msg.redirect) {
                        window.location.replace(msg.redirect);
                    } else {
                        window.location.replace("/");
                    }
                }
            }
        })
    });


});
