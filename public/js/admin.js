$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();

    navigate();
    search_output = $('[data-output="search-results"]');

    $('#button-add-telephone').on('click', function(){
        var html = '<div class="input-group">';
        html += '<input type="text" name="other_phones[]" class="form-control" placeholder="+38 (000) 111-22-33" />';
        html += '<span class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Удалить" onclick="$(this).parent().remove();">';
        html += '<i class="glyphicon glyphicon-trash"></i>';
        html += '</span></div>';
        $('.form-group.phones > .row > .form-element').prepend(html);
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#button-add-email').on('click', function(){
        var html = '<div class="input-group">';
        html += '<input type="text" name="notify_emails[]" class="form-control" placeholder="example@domain.com" />';
        html += '<span class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Удалить" onclick="$(this).parent().remove();">';
        html += '<i class="glyphicon glyphicon-trash"></i>';
        html += '</span></div>';
        $('.form-group.emails > .row > .form-element').prepend(html);
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#button-add-attribute').on('click', function(){
        var iterator = $('#attribute-values-iterator');
        var i = iterator.val();
        i++;

        var html = '<div class="row form-group">';
        html += '<div class="col-xs-5 attribute-name">';
        html += '<input type="text" name="values[new][' + i + '][name]" class="form-control" placeholder="Назание" />';
        html += '</div>';
        html += '<div class="col-xs-5 attribute-name">';
        html += '<input type="text" name="values[new][' + i + '][value]" class="form-control" placeholder="Значение" />';
        html += '</div>';
        html += '<div class="col-xs-2 text-center">';
        html += '<button type="button" class="btn btn-danger" onclick="$(this).parent().parent().remove();"><i class="glyphicon glyphicon-trash"></i></button>';

        html += '</div></div>';

        $('.form-group.attribute-value > .row > .form-element').append(html);
        $('[data-toggle="tooltip"]').tooltip();
        iterator.val(i);
    });

    $('#button-add-slide').on('click', function() {
        var iterator = $('#slideshow-iterator');
        var i = iterator.val();
        i++;

        var html = '<tr><td class="col-md-2">';
        html += '<input type="hidden" id="module-image-' + i + '" name="slide[' + i + '][image_id]" value="1" />';
        html += '<div id="module-image-output-' + i + '" class="module-image">';
        html += '<img src="/assets/images/no_image.jpg" />';
        html += '<button type="button" class="btn btn-del" data-delete="' + i + '" data-toggle="tooltip" data-placement="bottom" title="Удалить изображение">X</button>';
        html += '<button type="button" data-open="module-image" data-key="' + i + '" class="btn">Выбрать изображение</button>';
        html += '</div></td><td class="col-md-2"><b>Ссылка</b>';
        html += '<input type="text" name="slide[' + i + '][link]" class="form-control" value="" />';
        html += '</div><br><div><b>Текст кнопки</b>';
        html += '<input type="text" name="slide[' + i + '][button_text]" class="form-control" value="" />';
        html += '</div><br><div><b>Отображать ссылку</b>';
        html += '<select name="slide[' + i + '][enable_link]" class="form-control">';
        html += '<option value="1" selected>Да</option><option value="0">Нет</option>';
        html += '</select></div></td>';
        html += '<td class="col-md-2"><div><b>Порядок сортировки</b>';
        html += '<input type="text" name="slide[' + i + '][sort_order]" class="form-control" value="" />';
        html += '</div><br><div><b>Статус</b><select name="slide[' + i + '][status]" class="form-control">';
        html += '<option value="1" selected>Отображать</option><option value="0">Скрыть</option>';
        html += '</select></div></td>';
        html += '<td class="col-md-2"><div><b>Заголовок</b>';
        html += '<input type="text" name="slide[' + i + '][slide_title]" class="form-control" value="" />';
        html += '<span style="color: red">';
        html += '</span></div><br><div><b>Описание</b>';
        html += '<input type="text" name="slide[' + i + '][slide_description]" class="form-control" value="" />';
        html += '<span style="color: red">';
        html += '</span></div></td><td class="col-md-3">';
        html += '<div><b>Текст 1</b>';
        html += '<input type="text" name="slide[' + i + '][text][0]" class="form-control" value="" />';
        html += '</div>';
        html += '<div><b>Текст 2</b>';
        html += '<input type="text" name="slide[' + i + '][text][1]" class="form-control" value="" />';
        html += '</div>';
        html += '<div><b>Текст 3</b>';
        html += '<input type="text" name="slide[' + i + '][text][2]" class="form-control" value="" />';
        html += '</div>';
        html += '<div><b>Текст 4</b>';
        html += '<input type="text" name="slide[' + i + '][text][3]" class="form-control" value="" />';
        html += '</div>';

        html += '</td>';
        html += '<td class="col-md-1" align="center">';
        html += '<button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>';
        html += '</td></tr>';
        
        
        

        if ($('#modules-table tr.empty').length) {
            $('#modules-table tr.empty').remove();
        }
        $('#modules-table').append(html);
        iterator.val(i);
        $('[data-toggle="tooltip"]').tooltip();

    });

    $('.category-image .btn-del').on('click', function(){
        $('input#image').val('');
        $('#image-output > img').remove();
        $(this).hide();
    });

    $('.description-image .btn-del').on('click', function(){
        $('input#description-image').val('');
        $('#description-image-output > img').remove();
        $(this).hide();
    });

    $('#modules-table').on('click', '.btn-del', function(){
        var key = $(this).attr('data-delete');
        $('input#module-image-' + key).val('');
        $('#module-image-output-' + key +' > img').remove();
        $(this).hide();
    });

    $('.collapse').on('show.bs.collapse', function(e) {
        var id = e.currentTarget.id;
        $('#sidebar ul li').not('.nav-collapse li').removeClass('active');
        $('[data-target="#' + id + '"]').addClass('active');
    });

    $('.collapse').on('hide.bs.collapse', function(e) {
        var id = e.currentTarget.id;
        $('[data-target="#' + id + '"]').removeClass('active');
        navigate();
    });

    $('[data-autocomplete="input-search"]').on('keyup focus', function(){

        var search = $(this).val();
        var target = $(this).attr('data-target');
        search_output.html('').hide();

        if (search.length > 1) {
            var data = {};
            data.search = search;
            $.ajax({
                url: '/livesearch',
                data: data,
                method: 'GET',
                dataType: 'JSON',
                success: function(resp) {
                    var html = '<ul>';
                    $.each(resp, function(i, value){
                        if (value.empty) {
                            html += '<li>';
                            html += value.empty;
                            html += '</li>';
                        } else {
                            html += '<li class="selectable" data-name="' + value.name + '" data-id="' + value.product_id + '">';
                            html += value.name;
                            html += '</li>';
                        }
                    });
                    html += '</ul>';

                    $.each(search_output, function(i, value){
                        if ($(value).attr('data-target') == target) {
                            $(value).html(html).show();
                        }
                    });

                }
            });
        } else {
            search_output.hide();
        }
    });

    $('.search-results').on('click', 'li.selectable', function(){
        var product_id = $(this).attr('data-id');
        var product_name = $(this).attr('data-name');
        var existed_products = [];
        var target = $(this).parent().parent().attr('data-target');
        var manufacturer = $(this).parent().parent().attr('data-manufacturer');

        $.each($('.selected-products'), function (i, value) {
            if (manufacturer != null) {
                if ($(value).attr('data-manufacturer') == manufacturer) {
                    existed_products.push($(value).val());
                }
            } else {
                existed_products.push($(value).val());
            }
        });

        if (($.inArray(product_id, existed_products)) == -1) {

            if (manufacturer != null){
                var html = '<li>' + product_name;
                html += '<input type="hidden" class="selected-products" data-manufacturer="'+manufacturer+'" name="products['+manufacturer+'][]" value="' + product_id + '">';
                html += '<span aria-hidden="true" onclick="$(this).parent().remove()">&nbsp;Удалить</span>';
                html += '</li>';
            } else {
                var html = '<li>' + product_name;
                html += '<input type="hidden" class="selected-products" name="products[]" value="' + product_id + '">';
                html += '<span aria-hidden="true" onclick="$(this).parent().remove()">&nbsp;Удалить</span>';
                html += '</li>';
            }

            $.each($('[data-autocomplete="selected-products"]'), function (i, value) {
                if($(value).attr('data-target') == target) {
                   var output = $(value).children('ul');

                    if ($(output).find('li.empty').length) {
                        output.html(html);
                    } else {
                        output.append(html);
                    }
                }
            });

            $('[data-autocomplete="input-search"]').val('');
            search_output.hide();

        } else {
            search_output.html('<ul><li>Этот товар уже добавлен!</li></ul>');
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function update_next_image(){
        $.ajax({
            url: '/admin/images/update_sizes',
            type: 'POST',
            dataType: 'json',
            success: function(response)
            {
                if(response.result == 'success'){
                    $('#update_images_sizes span').text(' '+response.progress+'%');
                    if(response.progress != 100)
                        update_next_image();
                }
            }
        });
    }

    $('#update_images_sizes').click(function(){console.log('hello');
        $.ajax({
            url: '/admin/images/start_updating',
            type: 'POST',
            dataType: 'json',
            success: function(response)
            {
                if(response.result == 'success'){
                    if($('#update_images_sizes span').length === 0)
                        $('#update_images_sizes').append('<span> 0%</span>');
                    else
                        $('#update_images_sizes span').text(' 0%');
                    update_next_image();
                }
            }
        });
    });

    $('.form-group.attribute-value').on('click', '.button-upload-attribute-image', function(){
        var button = $(this);
        $('#form-upload').remove();

        $('body').prepend(
            '<form action="/upload_attribute_image" method="post" enctype="multipart/form-data" style="display:none" id="form-upload">' +
            '<input type="file" name="attribute_image" />' +
            '</form>'
        );

        var button_upload = $('#form-upload input[type="file"]');

        button_upload.trigger('click');

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function() {
            if ($(button_upload).val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: '/admin/upload_attribute_image',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if(response.href){
                            $(button).find('img').attr('src', '/assets/attributes_images/' + response.href);
                            $(button).prev('input.input-uploaded-image-href').val(response.href);
                        }
                    }
                });
            }
        }, 500);

    });

    $('.form-group.attribute-value').on('click', '.btn-del', function(){
        $(this).parent().find('input.input-uploaded-image-href').val(null);
        $(this).parent().find('img').attr('src', '/assets/attributes_images/no_image.jpg');
    });

    $('.chosen-select').chosen();

});

function navigate()
{
    $('#sidebar ul li').removeClass('active');

    var links = $('#sidebar ul li a');
    var path = window.location.pathname;

    path = path.replace('/admin/', '');
    var location = '/admin';

    if (path.indexOf('/') !== -1) {
        path = path.substring(path, path.indexOf('/'));

        if (path.length) {
            location += '/' + path;
        }

    } else {
        location += '/' + path;
    }

    $.each(links, function(i, value){
        var href = $(value).attr('href');

        if (href == location) {
            $(this).parent().addClass('active');
            if ($(this).parent().parent().hasClass('nav-collapse')) {
                $(this).parent().parent().prev().addClass('active');
                $(this).parent().parent().addClass('collapse in');
            }
        }
    });
}

function navigateProductFilter()
{
    var search = window.location.search.replace('?', '');
    var path = search.split('&');
    var buttons = $('.sort-buttons');

    $.each(path, function (i, value) {
        if (value.length){
            var sort = value.split('=');
            $.each(buttons, function (i, value) {
                if ($(value).attr('data-sort') == sort[0] && $(value).attr('data-value') == sort[1]) {
                    $(value).addClass('active');

                    if ($(value).parent().parent().prev().attr('data-toggle') == 'dropdown') {
                        $(value).parent().parent().prev().addClass('active');
                        var text = $(value).context.innerHTML;

                        $(value).parent().parent().prev().find('.dropdown-selected-name').html(text);
                    }
                }

            });
        }
    });
}

function getAttributes(val)
{

    $.ajax({
        url: '/admin/products/getattributevalues',
        type: 'GET',
        dataType: 'JSON',
        success: function(resp)
        {
            var iterator = $('#attributes-iterator').val();
            iterator++;

            var html = '<tr>';
            html += '<td><select class="form-control" onchange="getAttributeValues($(this).val(), ' + iterator + ')">';
            html += '<option value="0">Не выбрано</option>';
            $.each(resp, function(i, value){
                html += '<option value="' + value['attribute_id'] + '">' + value['attribute_name'] + '</option>';
            });
            html += '</select></td>';
            html += '<td align="center" id="attribute-' + iterator + '-values">Выберите значение атрибута</td>';
            html += '<td align="center"><input type="text" value="" name="product_attributes[' + iterator + '][price]" placeholder="Добавочная стоимость"></td>';
            html += '<td align="center"><button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button></td>';
            html += '</tr>';
            $('tbody#product-attributes').append(html);

            $('#attributes-iterator').val(iterator);
        }
    })

}

function getAttributeValues(attribute_id, iterator)
{
    var data = {
        'attribute_id': attribute_id
    };

    $.ajax({
        url: '/admin/products/getattributevalues',
        type: 'POST',
        data: data,
        dataType: 'JSON',
        success: function(resp)
        {
            if (resp.length) {
                var html = '<input type="hidden" name="product_attributes[' + iterator + '][id]" value="' + attribute_id + '"/>';
                html += '<select class="form-control" name="product_attributes[' + iterator + '][value]">';
                $.each(resp, function (i, value) {
                    html += '<option value="' + value['attribute_value_id'] + '">' + value['attribute_value'] + '</option>';
                });
                html += '</select>';
            } else {
                var html = 'Выберите значение атрибута';
            }

            $('td#attribute-' + iterator + '-values').html(html);
        }
    })
}

function confirmCategoriesDelete(id, name)
{
    $('#categories-delete-modal #confirm').attr('href', '/admin/categories/delete/' + id);
    $('#categories-delete-modal #category-name').html(name);
    $('#categories-delete-modal').modal();
}

function confirmProductsDelete(id, name)
{
    $('#product-delete-modal #confirm').attr('href', '/admin/products/delete/' + id);
    $('#product-delete-modal #product-name').html(name);
    $('#product-delete-modal').modal();
}

function confirmHTMLDelete(id, name)
{
    $('#html-delete-modal #confirm').attr('href', '/admin/html/delete/' + id);
    $('#html-delete-modal #html-name').html(name);
    $('#html-delete-modal').modal();
}

function confirmMeasuresDelete(id, name)
{
    $('#measures-delete-modal #confirm').attr('href', '/admin/measures/delete/' + id);
    $('#measures-delete-modal #measures-name').html(name);
    $('#measures-delete-modal').modal();
}

function confirmReviewDelete(id)
{
    $('#reviews-delete-modal #confirm').attr('href', '/admin/reviews/delete/' + id);
    $('#reviews-delete-modal').modal();
}

function confirmAttributesDelete(id, name)
{
    $('#attributes-delete-modal #confirm').attr('href', '/admin/attributes/delete/' + id);
    $('#attributes-delete-modal #attribute-name').html(name);
    $('#attributes-delete-modal').modal();
}

function filterProducts(button)
{
    var buttons = $('.sort-buttons');
    var sort = $(button).attr('data-sort');

    $('.sort-buttons[data-sort="'+sort+'"]').removeClass('active');
    $(button).addClass('active');

    var url = '?';

    $.each(buttons, function(i, value){
       if ($(value).hasClass('active')){
           sortBy = $(value).attr('data-sort');
           sortValue = $(value).attr('data-value');
           url += '&' + sortBy + '=' + sortValue;
       }

    });

    window.location.href = url;
}

function overlaySettings(setting){
    if(setting == 1){
        $('#attribute-image-overlay-setting').show();
    } else {
        $('#attribute-image-overlay-setting').hide();
    }
}

function deleteAttribute(id){
    $('.form-group.attribute-value').append('<input type="hidden" name="values[delete][]" value="' + id + '" />');
}

function getVariationAttributes(obj, iterator)
{
    $.ajax({
        url: '/admin/products/getattributevalues',
        type: 'GET',
        dataType: 'JSON',
        success: function(resp)
        {
            addVariationAttribute(resp, obj, iterator);
        }
    });

}

function addVariationAttribute(resp, obj, it){
    var table = obj.parents('table');
    console.log(it);
    if(typeof it == 'undefined'){
        // var iterator = table.find('.attributes-iterator').val();
        // iterator++;
        var iterator = $('.add-var-attr').length - 1;
    }else{
        var iterator = it;
    }
    console.log(table.find('.attributes-iterator'));
    var html = '<tr>';
    html += '<td><select class="form-control" onchange="getVariationAttributeValues($(this), '+iterator+')">';
    html += '<option value="0">Не выбрано</option>';
    $.each(resp, function(i, value){
        html += '<option value="' + value['attribute_id'] + '">' + value['attribute_name'] + '</option>';
    });
    html += '</select></td>';
    html += '<td align="center" id="attribute-' + iterator + '-values">Выберите значение атрибута</td>';
    html += '<td align="center"><button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button></td>';
    html += '</tr>';
    table.append(html);

    if(typeof it == 'undefined') {
        table.find('.attributes-iterator').val(iterator);
    }
}

function getVariationAttributeValues(obj, iterator)
{
    var attribute_id = obj.val();
    var key = obj.parents('table').find('.variation_value').length;
    var data = {
        'attribute_id': attribute_id
    };

    $.ajax({
        url: '/admin/products/getattributevalues',
        type: 'POST',
        data: data,
        dataType: 'JSON',
        success: function(resp)
        {
            if (resp.length) {
                var html = '<select class="form-control variation_value" name="variations[' + iterator + '][id]['+key+']">';
                $.each(resp, function (i, value) {
                    html += '<option value="' + value['attribute_value_id'] + '">' + value['attribute_value'] + '</option>';
                });
                html += '</select>';
            } else {
                var html = 'Выберите значение атрибута';
            }

            obj.parent().next().html(html);
        }
    })
}

function addVariation(obj){
    var html = '<div class="form-group">\n' +
        '<div class="row">\n' +
        '<label class="col-sm-2 text-right">Цена</label>\n' +
        '<div class="form-element col-sm-10">\n' +
        '<input class="form-control variation-price" name="variations['+$('.variation-price').length+'][price]" value="" type="text">\n' +
        '</div>\n' +
        '</div>\n' +
        '</div>' +
        '<div class="form-group">\n' +
        '<div class="row">' +
        '<div class="table table-responsive">\n' +
        '<table class="table table-hover">\n' +
        '<thead>\n' +
        '<tr class="success">\n' +
        '<td align="center">Выберите атрибут</td>\n' +
        '<td align="center">Выберите значение атрибута</td>\n' +
        '<td align="center">Действия</td>\n' +
        '</tr>\n' +
        '</thead>\n' +
        '<tbody class="product-attributes">\n' +
        '</tbody>\n' +
        '<tfoot>\n' +
        '<tr>\n' +
        '<td colspan="2"></td>\n' +
        '<td align="center">\n' +
        '<button type="button" onclick="getVariationAttributes($(this), '+$('.variation-price').length+');" class="btn add-attribute add-var-attr">Добавить атрибут</button>\n' +
        '</td>\n' +
        '</tr>\n' +
        '</tfoot>\n' +
        '</table>\n' +
        '</div>\n' +
        '</div>\n' +
        '</div>';

    obj.parents('.form-group').before(html);
}