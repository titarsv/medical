'use strict';
// Depends
var $ = require('jquery');
var swal = require('sweetalert2');

// Are you ready?
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  // Выбор вариации
  $('.variation-select').change(function(){
      var val = $(this).val();
      $('.product-price').html($(this).find('option[value="'+val+'"]').data('price'));
  });

    $('.btn_buy').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $this = $(this);
        var data = {
            action: 'add',
            product_id: $this.data('prod-id'),
            quantity: 1
        };
        var variations = $('.variation-select');
        if(variations.length){
            var v = variations.serializeArray();
            if(v.length){
                data['variations'] = [];
                for(var variation in v){
                    data['variations'][v[variation].name.replace(/attr\[(\d+)\]/, "$1")] = v[variation].value;
                }
            }
        }

        $("#order-popup").load("/cart/update", data, function(cart){
            $.magnificPopup.open({
                items: {
                    src: '#order-popup'
                },
                type: 'inline'
            }, 0);
            update_cart_quantity();
        });
        //update_cart(data);
    });

    /*
     * Добавление отзывов комментариев
     */
    $('form.review-form, form.answer-form').on('submit', function(e){
        e.preventDefault();
        var $this = $(this);

        $.ajax({
            url: '/review/add',
            data: $(this).serialize(),
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                $this.find('.error-message').fadeOut(300);
                $this.find('button[type="submit"]').html('Отправляем...');
            },
            success: function (response) {
                if(response.error){
                    var html = '';
                    $.each(response.error, function(i, value){
                        html += value + '<br>';
                    });
                    $('#error-' + response.type + ' > div').html(html);
                    $('#error-' + response.type).fadeIn(300);
                } else if(response.success) {
                    $('#error-' + response.type + ' > div').html(response.success);
                    $('#error-' + response.type).fadeIn(300);

                    setTimeout(function(){
                        $this.slideUp('slow');
                        $('.review-btn').fadeIn('slow');
                    },2500);
                    $('form.' + response.type + '-form')[0].reset();
                }
                $this.find('button[type="submit"]').html('Оставить отзыв')
            }
        });
    });

    window.sortBy = function(sort){
        var locate = location.search.split('&');
        var new_location = '';

        jQuery.each(locate, function (i, value) {
            var parameters = value.split('=');
            if (parameters[0] != 'sort') {
                new_location += value + '&';
            }
        });

        location.search = new_location + 'sort=' + sort;
    };

    /**
     * Отображение полей в зависимости от выбранного способа доставки
     */
    $('.order-page__form').on('change', '#checkout-step__delivery', function(){
        if ($(this).val() != 0) {
            $('.checkout-step__body').addClass('checkout-step__body_loader');
            $('.checkout-step__body_second .error-message').fadeOut(300);
            $('.checkout-step__body_second .error-message__text').html('');
            var data = {
                delivery: $(this).val(),
                order_id: $('#current_order_id').val()
            };

            $("#checkout-delivery-payment").load("/checkout/delivery", data, function (cart) {
                //$('select').fancySelect();
            });
            $('.checkout-step__body').removeClass('checkout-step__body_loader');
        }
    });

    /**
     * Удаление товара из корзины
     */
    $('#order-popup, #order_cart_content').on('click', '.mc_item_delete', function(){
        var $this = $(this);
        update_cart({
            action: 'remove',
            product_id: $this.data('prod-id')
        });
        $(this).parent('li').slideUp('slow').promise().done(function() {
            $(this).remove();
            if ($('.order-page__item').length != 0) {
                $('.order-page-inner').show();
            }
            else {
                $('.order-page-inner').hide();
                $('.order-page__empty').css('display', 'flex');
            }
        });
    });

    /**
     * Обновление колличества товара в корзине
     */
    $('#order-popup, #order_cart_content').on('input change', '.count_field', function(){
        var $this = $(this);
        update_cart({
            action: 'update',
            product_id: $this.data('prod-id'),
            quantity: $this.val()
        });
    });

    $('.login-inner .cart-wrapper').click(function(){
        $("#order-popup").load("/cart/get", {}, function(cart){
            $.magnificPopup.open({
                items: {
                    src: '#order-popup'
                },
                type: 'inline'
            }, 0);
        });
    });

    /**
     * Кнопка уменьшения колличества товара в корзине
     */
    $('#order-popup, #order_cart_content').on('click', '.cart_minus', function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });

    /**
     * Кнопка увеличения колличества товара в корзине
     */
    $('#order-popup, #order_cart_content').on('click', '.cart_plus', function () {
        var $input = $(this).parent().find('input');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    });

    /**
     * Обработка оформления заказа
     */
    $('#order-checkout').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        var error_div = form.find('.error-message');

        $.ajax({
            url: '/order/create',
            type: 'post',
            data: $(this).serialize(),
            beforeSend: function(){
                $('.checkout-step__body').addClass('checkout-step__body_loader');
                $('.checkout-step__body_second .error-message').fadeOut(300, function(){
                    $('.checkout-step__body_second .error-message__text').html('');
                });
                $('select, input').removeClass('input-error');
            },
            success: function(response) {

                if (response.error) {
                    var html = '';
                    $.each(response.error, function (id, text){
                        var error = id.split('.');
                        $('[name="' + error[0] + '[' + error[1] + ']"').addClass('input-error');
                        html += text + '<br>';
                    });
                    $('.cart-block_checkout .error-message__text').html(html);
                    $('.cart-block_checkout').removeClass('checkout-step__body_loader');
                    $('.cart-block_checkout .error-message').fa
                    deIn(300);
                } else if (response.success) {
                    console.log(response);
                    if (response.success == 'liqpay') {
                        // $('body').prepend(
                        //     '<form method="POST" id="liqpay-form" action="' + response.liqpay.url + '" accept-charset="utf-8">' +
                        //     '<input type="hidden" name="data" value="' + response.liqpay.data + '" />' +
                        //     '<input type="hidden" name="signature" value="' + response.liqpay.signature + '" />' +
                        //     '</form>');
                        // $('#liqpay-form').submit();
                        LiqPayCheckout.init({
                            data: response.liqpay.data,
                            signature:  response.liqpay.signature,
                            embedTo: "#liqpay_checkout",
                            mode: "embed" // embed || popup
                        }).on("liqpay.callback", function(data){
                            console.log(data.status);
                            console.log(data);
                            window.location = '/checkout/complete?order_id=' + response.order_id;
                        }).on("liqpay.ready", function(data){
                            $('#liqpay_checkout').css('display', 'block');
                        }).on("liqpay.close", function(data){
                            window.location = '/checkout/complete?order_id=' + response.order_id;
                        });
                    } else if (response.success == 'redirect') {
                        window.location = '/checkout/complete?order_id=' + response.order_id;
                    }
                }
            }
        })
    });

    $('.subscribe-form').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: '/subscribe',
            data: $(this).serialize(),
            method: 'post',
            dataType: 'json',
            success: function(response){
                if (response.email){
                    swal('Подписка', response.email[0], 'error');
                } else if (response.success) {
                    swal('Подписка', response.success, 'success');
                }

                $('.subscribe-form').find('input[type="email"]').val('');
            }
        });
    });

    /*табы логин и регистрация*/
    $(function() {
        $('ul.log_reg_caption').on('click', 'li:not(.active)', function() {
            $(this)
                .addClass('active').siblings().removeClass('active')
                .closest('nav.log_reg_tabs').find('div.log_reg_content').removeClass('active').eq($(this).index()).addClass('active');
        });

    });

    $('.filters input').change(function(){
        $(this).parents('form').submit();
        // $('#filters').submit();
    });

    $('.quantity-num').change(function(){
        $('.buy-popup-input-quant').val($(this).val());
    });

    $('#buy-popup form').on('sent', function(){
        if (typeof dataLayer !== 'undefined') {
            dataLayer.push({'event':'checkout'});
        }
    });
});

/**
 * Обновление корзины
 * @param data
 */
function update_cart(data){
    $("#order-popup").load("/cart/update", data, function(cart){
        var order_cart_content = $('#order_cart_content');
        if(order_cart_content.length > 0){
            order_cart_content.load("/cart/update", {type: "order_cart"});
        }
        //$('.cart_scroll_wrapper').jScrollPane();
        update_cart_quantity();
        //if(order_cart_content.length == 0)
        //    $('#cart').trigger('click');
    });
}

function update_cart_quantity() {
    var quantity = parseInt($('.order-popup__count-items').text());
    if(quantity > 0){
        if($('.login-inner .cart-wrapper i').length){
            $('.login-inner .cart-wrapper i').text(quantity);
        }else{
            $('.login-inner .cart-wrapper').append('<i>'+quantity+'</i>');
        }
    }else{
        $('.login-inner .cart-wrapper i').remove();
    }
}

/**
 * Загрузка городов и отделений Новой Почты
 * @param id
 * @param value
 */
function newpostUpdate(id, value) {
    if (id == 'city') {
        var data = {
            city_id: value
        };
        var path = '/checkout/warehouses';
        var selector = $('#checkout-step__warehouse');
    } else if (id == 'region') {
        var data = {
            region_id: value
        };
        var path = 'checkout/cities';
        var selector = $('#checkout-step__city');
    }

    $.ajax({
        url: path,
        data: data,
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            $('.checkout-step__body_second .error-message').fadeOut(300);
            $('.checkout-step__body').addClass('checkout-step__body_loader');
            $('.checkout-step__body_second .error-message__text').html('');
            $('#checkout-step__warehouse').html('<option value="0">Сначала выберите город!</option>');
            $('#checkout-step__warehouse').trigger('refresh');
        },
        success: function(response){
            if (response.error) {
                $('.checkout-step__body_second .error-message__text').html(response.error);
                $('.checkout-step__body').removeClass('checkout-step__body_loader');
                $('.checkout-step__body_second .error-message').fadeIn(300);
            } else if (response.success) {
                var html = '<option value="0">Выберите город</option>';
                $.each(response.success, function(i, resp){
                    if (id == 'city') {
                        var info = resp.address_ru;
                    } else if (id == 'region') {
                        var info = resp.name_ru;
                    }
                    html += '<option value="' + resp.id + '">' + info + '</option>';
                });
                selector.html(html);
                selector.trigger('update.fs');
                $('.checkout-step__body').removeClass('checkout-step__body_loader');
            }
        }
    });
}