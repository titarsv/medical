jQuery(document).ready(function($){
    /**
     * Добавление/удаление в желаемые
     */
    $('.liked_btn').click(function(e){
        var $this = $(this);
        var data = {
            user_id: $this.data('user-id'),
            product_id: $this.data('prod-id')
        };

        if ($this.prev().prop('checked')) {
            data['action'] = 'remove';
        } else {
            data['action'] = 'add';
        }

        $.post('/wishlist/update', data, function(response) {
            if(response.count === false) {
                var input = $this.prev();
                input.prop('checked', !input.prop('checked'));
            }else{
                var segments = location.pathname.split('/');
                if(segments[1] == 'user'){
                    $this.parents('.item').remove();
                }
            }
        });
    });

    /**
     * Добавление товара в корзину
     */
    $('.btn_buy').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $this = $(this);
        var variable = $this.parent().find('.variable');
        if(variable.length){
            $this.css('visibility', 'hidden');
            setTimeout(function () {
                variable.css('display', 'block');
            }, 500);

            return true;
        }
        var data = {
            action: 'add',
            product_id: $this.data('prod-id'),
            quantity: $('#single_product_count').length ? $('#single_product_count').val(): 1,
            //color: $this.parents('.product').find('[name=color]:checked').val()
        };
        var variations = $('#variations');
        if(variations.length){
            var v = variations.serializeArray();
            if(v.length){
                data['variations'] = [];
                for(var variation in v){
                    data['variations'][v[variation].name.replace(/attr\[(\d+)\]/, "$1")] = v[variation].value;
                }
            }
        }
        update_cart(data);
    });

    // $('.variable .colors input').change(function (e) {
    //     e.preventDefault();
    //     e.stopPropagation();
    //     var $this = $(this);
    //
    //     update_cart({
    //         action: 'add',
    //         product_id: $this.parents('.btn_buy_container').find('.btn_buy').data('prod-id'),
    //         quantity: 1,
    //         color: $this.val()
    //     });
    //
    //     $this.parents('.variable').css('display', 'none');
    //     $this.parents('.btn_buy_container').find('.btn_buy').css('visibility', 'visible');
    // });

    /**
     * Удаление товара из корзины
     */
    $('#cart_content, #order_cart_content').on('click', '.mc_item_delete', function(){
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
    $('#cart_content, #order_cart_content').on('input change', '.count_field', function(){
        var $this = $(this);
        update_cart({
            action: 'update',
            product_id: $this.data('prod-id'),
            quantity: $this.val()
        });
    });

    /**
     * Обновление корзины
     * @param data
     */
    function update_cart(data){
        $("#cart_content").load("/cart/update", data, function(cart){
            var order_cart_content = $('#order_cart_content');
            if(order_cart_content.length > 0){
                order_cart_content.html(cart);
            }
            $('.cart_scroll_wrapper').jScrollPane();
            update_cart_quantity();
            if(order_cart_content.length == 0)
                $('#cart').trigger('click');
        });
    }


    function update_cart_quantity() {
        var quantity = parseInt($('.mc_total_items_count span').text());
        if(quantity > 0){
            if($('#cart_items_count').length){
                $('#cart_items_count').text(quantity);
            }else{
                $('#cart').before('<div id="cart_items_count">'+quantity+'</div>');
            }
        }else{
            $('#cart_items_count').remove();
        }
    }

    /**
     * Кнопка уменьшения колличества товара в корзине
     */
    $('#cart_content, #order_cart_content').on('click', '.cart_minus', function () {
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
    $('#cart_content, #order_cart_content').on('click', '.cart_plus', function () {
        var $input = $(this).parent().find('input');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    });

    /**
     * Ползунок цен
     */
    $("#cat_price_range").slider({ from: $("#cat_price_range").data('min'), to: $("#cat_price_range").data('max'), step: 100, dimension: '&nbsp;грн' });

    $('.cart_scroll_wrapper').jScrollPane();

    $('.product-filter-form').submit(function(e){
        e.preventDefault();
        var filters = '';
        var filters_data = {};
        $('.product-filter-data:checked').each(function(){
            if(typeof filters_data[$(this).data('attribute')] === 'undefined')
                filters_data[$(this).data('attribute')] = [];

            filters_data[$(this).data('attribute')][filters_data[$(this).data('attribute')].length] = $(this).data('value');
        });
        for(var attribute in filters_data){
            filters += '_' + attribute;
            for(var value in filters_data[attribute]){
                filters += '-' + filters_data[attribute][value];
            }
        }

        var pathname = '';
        if(window.location.pathname.indexOf('/auto') == 0){
            pathname = window.location.pathname.match(/\/auto\/\w+/i)[0];
        }else if(window.location.pathname.indexOf('/categories') == 0){
            pathname = window.location.pathname.match(/\/categories\/\w+/i)[0];
        }

        if($('#year').length && $('#year').val() != ''){
            pathname = '/auto/' + $('#year').val();
        }else if($('#model').length && $('#model').val() != ''){
            pathname = '/auto/' + $('#model').val();
        }else if($('#brand').length && $('#brand').val() != ''){
            pathname = '/auto/' + $('#brand').val();
        }

        var url = window.location.origin
            + pathname
            + '/' + filters + '?sort='
            + $('[name=sorting]').val()
            + '&limit=' + $('[name=limit]').val();

        if($('#cat_price_range').length)
            url += '&price=' + $('#cat_price_range').val();

        // if($('#brand').length && $('#brand').val() != '')
        //     url += '&brand=' + $('#brand').val();
        //
        // if($('#model').length && $('#model').val() != '')
        //     url += '&model=' + $('#model').val();
        //
        // if($('#year').length && $('#year').val() != '')
        //     url += '&year=' + $('#year').val();

        if($('#current_category_id').length)
            url += '&categories[]=' + $('#current_category_id').val();
        else{
            $('[name="categories[]"]:checked').each(function(){
                url += '&categories[]=' + $(this).val();
            });
        }


        window.location = url;
    });

    $('#callback-form').submit(function (e) {
        e.preventDefault();
        var $this = $(this);

        $.post('/callback', $this.serialize(), function(response){
            var errors = $this.find('.errors');
            errors.html('');
            if (response.name || response.phone){
                for(var error in response)
                    errors.append('<p>' + response[error] + '</p>');
            } else if (response.success) {
                $('.del-info-popup .del-info-popup__text').html(response.success);
                $.magnificPopup.open({
                    items: {
                        src: $('.del-info-popup')[0].outerHTML
                    },
                    type: 'inline'
                });
                $('#callback-modal').removeClass('active');
            }
        });
    });

    /**
     * Подгрузка модельного ряда
     */
    $('#brand').on('change.fs', function(){
        $this = $(this);
        $.post('/get_models', {brand: $this.val()}, function(response){
            var model = $('#model');
            model.html(response);
            model.attr('disabled', false);
            model.trigger('enable');
            setTimeout(function () {
                model.trigger('update.fs');
            }, 100);
            $('#year').html('<option value="">Год выпуска</option>').trigger('update.fs').trigger('disable');
        });
    });


    $('#model').on('change.fs', function(){
        $this = $(this);
        $.post('/get_years', {brand: $('#brand').val(), model: $this.val()}, function(response){
            var years = $('#year');
            years.html(response);
            years.attr('disabled', false);
            years.trigger('enable');
            setTimeout(function () {
                years.trigger('update.fs');
            }, 100);
        });
    });

    $('.auto_filter_button').click(function () {
        $('.product-filter-form').trigger('submit');
    });

    $('.items_counter .minus').click(function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });

    $('.items_counter .plus').click(function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) + 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });

    // $('#product-type').click(function () {
    //     $('.product-types').toggleClass('open');
    // });

    // $(document).click(function(event) {
    //     if ($(event.target).closest(".open.product-types").length || $(event.target).attr('id') == 'product-type') return;
    //     $(".open.product-types").removeClass('open');
    //     event.stopPropagation();
    // });

    $('#main_filter').submit(function (e) {
        e.preventDefault();
        var data = $(this).serializeArray();
        var sendData = '';
        var model = 'auto';
        for(var field in data){
            if(data[field].name == 'categories[]'){
                if(sendData != '')
                    sendData += '&';
                sendData += ('categories[]='+data[field].value);
            }else if(data[field].value != '' && (data[field].name == 'model' || data[field].name == 'brand' || data[field].name == 'year')){
                model = data[field].value;
            }
        }

        window.location = '/auto/'+model+'?'+sendData;
    });

    $(".ps-current ul").lightGallery({
        thumbnail:true,
        download:false,
        selector: 'li'
    });

    $('#variations input').change(function(){
        var price = parseFloat($('#single-price').data('price'));
        $('#variations input:checked').each(function(){
            price += parseFloat($(this).data('price'));
        });
        $('#single-price').text(number_format(price, 2, ',', ' '));
    });

    $('#variations').on('click', ' input:checked + label', function (e) {
        e.preventDefault();

        $('#'+$(this).attr('for')).prop('checked', false).trigger('change');
    });

    function number_format( number, decimals, dec_point, thousands_sep ) {
        var i, j, kw, kd, km;

        // input sanitation & defaults
        if( isNaN(decimals = Math.abs(decimals)) ){
            decimals = 2;
        }
        if( dec_point == undefined ){
            dec_point = ",";
        }
        if( thousands_sep == undefined ){
            thousands_sep = ".";
        }

        i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

        if( (j = i.length) > 3 ){
            j = j % 3;
        } else{
            j = 0;
        }

        km = (j ? i.substr(0, j) + thousands_sep : "");
        kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
        //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
        kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


        return km + kw + kd;
    }

    /**
     * Оформление заказа
     */

    /**
     * Обработка первого шага оформления заказа
     */
    $('#checkout-personal-info').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        var error_div = form.find('.error-message');
        var error_text = error_div.find('.error-message__text');

        $.ajax({
            url: '/order/create',
            type: 'post',
            data: $(this).serialize(),
            beforeSend: function() {
                $('.checkout-step__body').addClass('checkout-step__body_loader');
                error_div.fadeOut(300);
                error_text.html('');
                form.find('input').removeClass('input-error');

            },
            success: function(response) {
                if(response.error){
                    var html = '';
                    $.each(response.error, function(field, error){
                        html += error + '<br>';
                        form.find('input[name="' + field + '"]').addClass('input-error');
                    });
                    form.find('.error-message__text').html(html);
                    $('.checkout-step__body').removeClass('checkout-step__body_loader');
                    form.find('.error-message').fadeIn(300);
                } else if(response.success){
                    $('.checkout-step__body').removeClass('checkout-step__body_loader');
                    $('.checkout-step__body_first').slideUp('slow');
                    $('.checkout-step__body_second').slideDown('slow');
                    $('.checkout-step__edit').toggleClass('hidden');
                    $('#current_order_id, #current_order_id_2').val(response.success);
                    $('#checkout-step__region').trigger('refresh');
                    $('#submit_order').css('display', 'inline-block');
                }
            }
        })
    });

    /**
     * Отображение полей в зависимости от выбранного способа доставки
     */
    $('.cart_reg_form').on('change.fs', '#checkout-step__delivery', function(){
        if ($(this).val() != 0) {
            $('.checkout-step__body').addClass('checkout-step__body_loader');
            $('.checkout-step__body_second .error-message').fadeOut(300);
            $('.checkout-step__body_second .error-message__text').html('');
            var data = {
                delivery: $(this).val(),
                order_id: $('#current_order_id').val()
            };

            $("#checkout-delivery-payment").load("/checkout/delivery", data, function (cart) {
                $('select').fancySelect();
            });
            $('.checkout-step__body').removeClass('checkout-step__body_loader');
        }
    });

    /**
     * Обработка второго шага оформления заказа
     */
    $('#submit_order').on('click', function(e){
        $.ajax({
            url: '/checkout/confirm',
            type: 'post',
            data: $('#checkout-step-2').serialize(),
            dataType: 'json',
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
                        console.log(id);
                        var error = id.split('.');
                        $('[name="' + error[0] + '[' + error[1] + ']"').addClass('input-error');
                        html += text + '<br>';
                    });
                    $('.checkout-step__body_second .error-message__text').html(html);
                    $('.checkout-step__body').removeClass('checkout-step__body_loader');
                    $('.checkout-step__body_second .error-message').fadeIn(300);
                } else if (response.success) {
                    if (response.success == 'redirect') {
                        window.location = '/checkout/complete?order_id=' + response.order_id;
                    }
                }
            }
        })
    });

    $('#review-slide').click(function(){
        $(this).fadeOut('fast');
        $('.review-form').slideDown('slow');
    });

    $('.review-form__btn_cancel').click(function(){
        $('.review-form').slideUp('slow');
        $('.review-btn').fadeIn('slow');
    });

    $('.all-review-btn').click(function(){
        $('.review-item').show();
        $(this).hide();
    });

    $('#comment-slide').click(function(){
        $(this).siblings('.answer-form').slideDown('slow');
    });

    $('.answer-form__btn_cancel').click(function(){
        $(this).parents('.answer-form').slideUp('slow');
    });

    $('.product-types .close').click(function () {
        $(this).parent().removeClass('open');
    });

    /*
     * Добавление отзывов и комментариев к ним
     */

    /**
     * Попап для незарегистрированных пользователей о запрете голосования
     */
    $('#add-like, #add-dislike').on('mouseenter', function () {
        $('.cart-hover').removeClass('active');
        if($(this).hasClass('unregistered')){
            $(this).parent().parent().find('.cart-hover').addClass('active');
        }
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

    /**
     * Обработка лайков и дизлайков
     */
    $('#add-like, #add-dislike').on('click', function(){
        var $this = $(this);

        var data = {
            '_token': $('input[name="_token"]').val(),
            'review_id': $(this).data('review'),
            'action': $(this).data('action')
        };

        $.ajax({
            url: '/review/add-likes',
            data: data,
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                $(this).find('.error-message').fadeOut(300);
            },
            success: function (response) {
                if(response.error){
                    var html = '<div class="cart-hover usefull-hover active error" onmouseleave="$(this).remove()">';
                    html += '<span class="cart-hover__text">Ошибка: ' + response.error + '</span>';
                    html += '</div>';

                    $this.parent().parent().append(html);
                } else if(response.success) {
                    var html = '<div class="cart-hover usefull-hover active success" onmouseleave="$(this).remove()">';
                    html += '<span class="cart-hover__text">' + response.success + '</span>';
                    html += '</div>';

                    $this.parent().parent().find('#add-like').html('Да('+ response.like +')');
                    $this.parent().parent().find('#add-dislike').html('Нет('+ response.dislike +')');

                    $this.parent().parent().append(html);
                }
            }
        });
    });
	
	 $('[name=limit]').on('change.fs', function(){
        var limit = $(this).val();

        var locate = location.search.split('&');
        var new_location = '';

        $.each(locate, function (i, value) {
            var parameters = value.split('=');
            if (parameters[0] != 'limit' && parameters[0] != 'page') {
                new_location += value + '&';
            }
        });

        location.search = new_location + 'limit=' + limit;
    });
});

function popupOrderAccept(){
    var popup_accept_button = $('#popup_accept_button');
    var sum = $('#cart_popup_prod_sum').text();
    sum = Number(sum.replace('на сумму ', '').replace(' грн', ''));
    console.log(sum);
    if(sum > 750){
        popup_accept_button.removeClass('cart-btn_disabled');
        popup_accept_button.html('Оформить заказ');
        popup_accept_button.attr('href','/order');
        $('#order_form').show();
    }else{
        popup_accept_button.addClass('cart-btn_disabled');
        popup_accept_button.html('Потрать еще');
        popup_accept_button.attr('href','javascript:void(0)');
        $('#order_form').hide();
    }
}

$('.header-middle__cart-link').on('click', function(){
    console.log('зина-корзина');
    $.ajax({
            url: '/cart/get',
            type: 'POST'
        })
        .done(function(data){
            console.log(data);
            getCartList(data);
            orderAccept();
            emptyCart();
            popupOrderAccept();
        });

});
var products_in_cart = document.querySelectorAll('#cart-table__row');
$.each(products_in_cart, function(){
    var product_cart_quantity = this.querySelector('#cart-table__input');
    function getValueOfInputNumber() {
        quantity_val = this.value;
        this.setAttribute("value", quantity_val);
    }
    product_cart_quantity.onkeyup = getValueOfInputNumber;
    product_cart_quantity.onchange = getValueOfInputNumber;
});

function cartUpdate(){
    var products = document.querySelectorAll('#cart-table__row');
    var updated_cart = {};
    var cart1 = {};
    var sum = 0;
    var products_quantity = 0;
    $.each(products, function(i){
        var product_quantity = this.querySelector('#cart-table__input');
        var product_price = $(product_quantity).attr('data-product-price');
        var prod_sum_price = this.querySelector('.cart-table__price');
        var quantity_val;
        function getValueOfInputNumber() {
            quantity_val = this.value;
            this.setAttribute("value", quantity_val);
        }
        product_quantity.onkeyup = getValueOfInputNumber;
        product_quantity.onchange = getValueOfInputNumber;
        var sum_price = Number(product_quantity.value * product_price);
        sum += sum_price;
        prod_sum_price.innerHTML = sum_price + ' грн';
        var prod_data = this.querySelector('.cart-table__quantity');
        products_quantity += Number(product_quantity.value);
        updated_cart[i] = {
            'product_id':$(prod_data).attr('data-product-id'),
            'product_quantity':product_quantity.value,
            'sum_price':sum_price
        };
        //cart1[i] = Number(prod_sum_price.innerHTML.replace(' грн', ''));

    });
    $('#cart-result__item').text(sum + ' грн');
    $('#cart-result__item_accent').text(sum + ' грн');

    $('#cart_popup_prod_sum').text('на сумму ' + sum + ' грн');
    $('#cart_popup_prod_quantity').text(products_quantity + ' товара');
    console.log(updated_cart);


    $.ajax({
            url: '/cart/updateAll',
            type: 'POST',
            data: {
                'cart':updated_cart,
                'sum':sum,
                'products_quantity':products_quantity
            }
        })
        .done(function(data){
            console.log(data);
            $('#header-middle__cart-counter').text(data.products_quantity);
            $('#cart_popup_prod_quantity').text(data.products_quantity + ' товара');
            emptyCart();
            popupOrderAccept();
            //orderAccept();
        });
}
$('#cart_reload-btn').on('click', function(){
    cartUpdate();
    orderAccept();
    emptyCart();
    popupOrderAccept();
});


$.each(products_in_cart, function(i){
    var product_delete = this.querySelector('.cart-table__delete');
    $(product_delete).on('click', function (form, e) {
        console.log(12);
    });
});



// $(function() {
//     $('form.product-filter-form').on('submit', function(){
//         var sort = $('select[name="sorting"]').val();
//         $(this).append('<input type="hidden" name="sort" value="' + sort + '" />');
//         return true;
//     });
//
//     if($('form.product-filter-form').length) {
//
//         $.each($('ul.pagination li a'), function (i, value) {
//             var href = $(value).attr('href');
//             href += '&' + ($('form.product-filter-form').serialize());
//             $(value).attr('href', href);
//         });
//     }
// });

function removeFilter(attribute_id, attribute_value_id){
    $('input[name="filter_attributes['+attribute_id+'][value]['+attribute_value_id+']"]').removeAttr('checked');
    $('form.product-filter-form').submit();
}

function sortBy(sort){
    var locate = location.search.split('&');
    var new_location = '';

    $.each(locate, function (i, value) {
        var parameters = value.split('=');
        if (parameters[0] != 'sort') {
            new_location += value + '&';
        }
    });

    location.search = new_location + 'sort=' + sort;
}

/*
* Order
* */

function pageOrderAccept(){
    var order_form = $('#order_form');
    var accept_button = $('#from_cart_to_order_button');
    var sum = $('#cart-result__item_accent').text();
    sum = Number(sum.replace(' грн', ''));
    if(sum <= 750){
        //order_form.hide();
        accept_button.addClass('cart-btn_disabled');
        accept_button.html('Минимальная сумма заказа 750 грн');
    }else{
        accept_button.removeClass('cart-btn_disabled');
        accept_button.html('Далее');
    }
}

// если чувак на странице ордера или если есть ID блока ордера

var order_view = $('#order_process');
if(order_view.length > 0){
//console.log('э.... заебись!');}else{console.log('заебись в двойне!!!');}
    $('#order_reload-btn').on('click', function(){

        cartUpdate();
        pageOrderAccept();
        emptyCart();
    });
    $.ajax({
            url: '/cart/get',
            type: 'POST'
        })
        .done(function(data){
            console.log(data);
            getCartList(data);
            pageOrderAccept();
            //orderAccept();// метод для изменения кнопок
            //emptyCart();//метод показывает пустую корзину
        });

}

$('#subscribe').on('submit', function(e){
    e.preventDefault();

    $.ajax({
        url: '/subscribe',
        data: $(this).serialize(),
        method: 'post',
        dataType: 'json',
        success: function(response){
            if (response.email){
                $('.del-info-popup .del-info-popup__text').html(response.email);
            } else if (response.success) {
                $('.del-info-popup .del-info-popup__text').html(response.success);
            }

            $.magnificPopup.open({
                items: {
                    src: $('.del-info-popup')[0].outerHTML
                },
                type: 'inline'
            });

            $('#subscribe').find('input[type="email"]').val('');
        }
    });
});

$('#give-review__form').on('submit', function(e){
    e.preventDefault();

    $.ajax({
        url: '/review/add',
        data: $(this).serialize(),
        method: 'post',
        dataType: 'json',
        success: function (response) {
            if(!response.success){
                $('.del-info-popup .del-info-popup__icon').html('&#xe81a;');
                var html = '';
                $.each(response, function(i, value){
                    html += value + '<br>';
                });
                $('.del-info-popup .del-info-popup__text').html(html);
            } else {
                $('.del-info-popup .del-info-popup__icon').html('&#xe819;');
                $('.del-info-popup .del-info-popup__text').html(response.success);

                $('#give-review__form')[0].reset();
            }

            $.magnificPopup.open({
                items: {
                    src: $('.del-info-popup')[0].outerHTML
                },
                type: 'inline'
            });
        }
    });
});

$(function(){
    if (location.hash == '#reviews') {
        $(".tabs-caption__item.active").removeClass("active");
        $(".product-review__caption").addClass("active");
        $(".tabs_content.active").removeClass("active");
        $(".product-review").addClass("active");
    }
});

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
                var html = '<option value="0">Выберите...</option>';
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
    })
}