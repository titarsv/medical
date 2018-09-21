$(window).load(function(){
    $('body').niceScroll({
        cursorborder: '1px solid #eee',
        cursorcolor: '#000000',
        horizrailenabled: false
    });
    $('#sidebar').niceScroll({
        cursorborder: '1px solid #000',
        cursorcolor: '#eee',
        horizrailenabled: false
    });
});

$(document).ready(function(){

    $('ul.nav-collapse').on('shown.bs.collapse', function(){
        $("#sidebar").getNiceScroll().resize();
    });

    $('select#sort-by, select#show').on('change', function(){
        $('form#settings-form').submit();
    });

    if ($('.alert').length) {
        setTimeout(function(){
            $('.alert').fadeOut(500);
        }, 3000);
    }

    $('.alert').on('close.bs.alert', function(e){
        e.preventDefault();
        $(this).fadeOut(500);
    });

    //$('input[name="phones[]"]').mask('999-999-99-99');
    $('[data-toggle="tooltip"]').tooltip();

    $(window).scroll(function(e){
        if ($(this).scrollTop() >= 60) {
            $('.navbar-title').css('margin-top', 0);
        } else {
            $('.navbar-title').css('margin-top', '-50px');
        }
    });

    navigate();
    search_output = $('[data-output="search-results"]');

    $('#button-add-branch').on('click', function(){
        var html = '<div class="input-group">';
        html += '<input type="text" name="city[]" class="form-control" placeholder="Город" />';
        html += '<input type="text" name="branch_address[]" class="form-control" placeholder="Адрес" />';
        html += '<input type="text" name="phones[]" class="form-control" placeholder="Телефоны (через запятую)" />';
        html += '<span class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Удалить" onclick="$(this).parent().remove();">';
        html += '<i class="glyphicon glyphicon-trash"></i>';
        html += '</span></div>';
        $(this).before(html);
        $('[data-toggle="tooltip"]').tooltip();
        //$('input[name="phones[]"]').mask('999-999-99-99');
    });

    $('#button-add-email').on('click', function(){
        var html = '<div class="input-group">';
        html += '<input type="text" name="notify_emails[]" class="form-control" placeholder="example@domain.com" />';
        html += '<span class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Удалить" onclick="$(this).parent().remove();">';
        html += '<i class="glyphicon glyphicon-trash"></i>';
        html += '</span></div>';
        $(this).before(html);
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#button-add-attribute').on('click', function(){
        var iterator = $('#attribute-values-iterator');
        var i = iterator.val();
        i++;

        var html = '<div class="row form-group">';
        html += '<div class="col-xs-11 attribute-name">';
        html += '<input type="text" name="values[new][' + i + '][name]" class="form-control" placeholder="Значение" />';
        html += '</div>';
        html += '<div class="col-xs-1 text-center">';
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

        var html = '<tr><td>';
        html += '<input type="hidden" id="module-image-' + i + '" name="settings[slides][' + i + '][image_id]" value="1" />';
        html += '<div id="module-image-output-' + i + '" class="module-image">';
        html += '<img src="/assets/images/no_image.jpg" />';
        html += '<button type="button" class="btn btn-del" data-delete="' + i + '" data-toggle="tooltip" data-placement="bottom" title="Удалить изображение">X</button>';
        html += '<button type="button" data-open="module-image" data-key="' + i + '" class="btn">Выбрать изображение</button>';
        html += '</div></td><td>';
        html += '<input type="text" name="settings[slides][' + i + '][link]" class="form-control" value="" />';
        html += '</td><td>';
        html += '<select name="settings[slides][' + i + '][enabled]" class="form-control">';
        html += '<option value="1" selected>Отображать</option>';
        html += '<option value="0">Скрыть</option>';
        html += '</select></td><td>';
        html += '<input type="text" name="settings[slides][' + i + '][sort_order]" class="form-control" value="0" />';
        html += '</td><td align="center">';
        html += '<button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>';
        html += '</td></tr>';

        if ($('#modules-table tr.empty').length) {
            $('#modules-table tr.empty').remove();
        }
        $('#modules-table').append(html);
        iterator.val(i);
        $('[data-toggle="tooltip"]').tooltip();

    });

    $('#button-add-scheme').on('click', function(){
        var iterator = $('#scheme-values-iterator');
        var i = iterator.val();
        i++;

        var html = '<div class="row form-group">';

        html += '<div class="col-xs-10"><input type="text" placeholder="Название" name="schemes[' + i + '][name]" class="form-control" value="" /></div>';
        html += '<div class="col-xs-2"><button class="btn btn-danger" style="float:right;" onclick="$(this).parent().parent().remove();">Удалить</button></div>';

        html += '<div class="col-sm-push-1 col-sm-10">';
        html += '<input type="hidden" id="module-image-' + i + '" name="schemes[' + i + '][image_id]" value="1" />';
        html += '<div id="module-image-output-' + i + '" class="module-image" style="width: 100%;margin-top: 15px;">';
        html += '<img src="/assets/images/no_image.jpg" />';
        html += '<button type="button" class="btn btn-del" data-delete="' + i + '" data-toggle="tooltip" data-placement="bottom" title="Удалить изображение">X</button>';
        html += '<button type="button" data-open="module-image" data-key="' + i + '" class="btn">Выбрать изображение</button>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-xs-12"><textarea rows="5" placeholder="Карта" name="schemes[' + i + '][map]" class="form-control" value="" /></div>';

        html += '</div>';

        $('.form-group.attribute-value > .row > .form-element').append(html);
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

    $('#sidebar .collapse').on('show.bs.collapse', function(e) {
        var id = e.currentTarget.id;
        $('#sidebar ul li').not('.nav-collapse li').removeClass('active');
        $('[data-target="#' + id + '"]').addClass('active');
    }).on('hide.bs.collapse', function(e) {
        var id = e.currentTarget.id;
        $('[data-target="#' + id + '"]').removeClass('active');
        navigate();
    });

    $('.category-table').on('click', '.category-collapse-link', function() {
        var $this = $(this);
        var target = $this.data('target');
        var child = $this.data('child');
        var $closest = $(target).closest('tr.collapsed');

        if (!$this.hasClass('collapsed')) {
            $(target).collapse('show');
            $closest.addClass('in');
            $this.addClass('collapsed');
            reinitScroller();
        } else {
            $(document).trigger('collapseClosed', [child, $this, $this.parents('tr').get(0)]);
            reinitScroller();
        }

    });


    function reinitScroller() {
        $("body").getNiceScroll().resize();
    }

    $(document).on('collapseClosed', function(event, target, elem, parent){
        var iterator = false;
        $('.category-table tr').each(function(i, value){
            var child = $(value).data('child');
            if ($(parent).data('child') == $(this).data('child')){
                iterator = false;
            }

            if(iterator == true){
                $(value).find('.collapse').collapse('hide');
                $(value).removeClass('in');
                $(elem).removeClass('collapsed');
                $(value).find('.collapsed').removeClass('collapsed');
            }

            if (value == parent) {
                iterator = true;
            }
        })
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
                html += '<input type="hidden" class="selected-products" name="settings[products][]" value="' + product_id + '">';
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

function confirmDelete(alias, id, name) {
    $('#' + alias + '-delete-modal #confirm').attr('href', '/admin/' + alias + '/delete/' + id);
    if (name) {
        $('#' + alias + '-delete-modal #' + alias + '-name').html(name);
    }
    $('#' + alias + '-delete-modal').modal();
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

jQuery(function () {
    jQuery.fn.maphilight.defaults = {
        fill: true,
        fillColor: '0075cf',
        fillOpacity: 0.3,
        stroke: true,
        strokeColor: '0075cf',
        strokeOpacity: 1,
        strokeWidth: 2,
        fade: true,
        alwaysOn: false,
        neverOn: false,
        groupBy: false,
        wrapClass: true,
        shadow: false,
        shadowX: 0,
        shadowY: 0,
        shadowRadius: 6,
        shadowColor: '000000',
        shadowOpacity: 0.8,
        shadowPosition: 'outside',
        shadowFrom: false
    };

    jQuery('.tab-pane.active img[usemap]').rwdImageMaps();

    jQuery('.tab-pane.active .map').maphilight();
    jQuery(window).resize(function () {
        jQuery('.tab-pane.active .map').maphilight();
    });

    $('#schemes ').on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        jQuery('.tab-pane.active img[usemap]').rwdImageMaps();
        updateMaphilight();
        setTimeout(function(){
            $('area').each(function(){
                var d = $(this).data('maphilight') || {};
                if(d.alwaysOn)
                    $(this).data('maphilight', d).trigger('alwaysOn.maphilight');
            });
        }, 500);
    });

    $('#schemes').on('click', '.scheme_map area', function(e){
        e.preventDefault();
        var $this = $(this);
        var d = $this.data('maphilight') || {};
        var destination = $this.parents('map').data('destination');
        var data = $this.attr('href').split('-');
        if(d.alwaysOn){
            d.alwaysOn = false;
            $('#'+destination).val('');
        }else{
            d.alwaysOn = true;
            $('#'+destination).val(data[1]);
        }
        $this.data('maphilight', d).trigger('alwaysOn.maphilight');
        updateMaphilight();
    });

    function updateMaphilight(){
        $('.tab-pane.active .scheme_map').each(function(){
            var $this = $(this);
            var destination = $this.data('destination');
            var id = $('#'+destination).val();
            $this.find('area').each(function(){
                var d = $(this).data('maphilight') || {};
                d.alwaysOn = $(this).hasClass('product-'+id);
                $(this).data('maphilight', d).trigger('alwaysOn.maphilight');
            });
        });
    }

    setTimeout(function(){
        $('area').each(function(){
            var d = $(this).data('maphilight') || {};
            if(d.alwaysOn)
                $(this).data('maphilight', d).trigger('alwaysOn.maphilight');
        });
    }, 500);

    $("#units_select").chosen().change(function(){
        var data = {units: $(this).val()};
        if($(this).data('product-id'))
            data.product = $(this).data('product-id');
        $('#schemes').load('/admin/units/schemes', data, function(){
            jQuery('.tab-pane.active img[usemap]').rwdImageMaps();
            jQuery('.tab-pane.active .map').maphilight();
            setTimeout(function(){
                $('area').each(function(){
                    var d = $(this).data('maphilight') || {};
                    if(d.alwaysOn)
                        $(this).data('maphilight', d).trigger('alwaysOn.maphilight');
                });
            }, 100);
        });
    });

    // Настройка схем
    $('.scheme-areas').click(function(){
        var key = $(this).data('key');
        var module = $('#module-image-output-'+key);
        var img = module.find('img');
        if($('#map_'+key).length == 0) {
            var map = $('<map id="map_' + key + '" class="scheme_map" name="map_' + key + '">' + $('#areas-data-' + key).val() + '</map>');
            img.css('max-height', 'unset');
            img.addClass('map_'+key).attr('usemap', '#map_'+key);
            img.before(map);
            module.find('[data-open]').remove();
            module.find('.scheme-settings').css('display', 'block');
            $('#area_id_'+key).change(function(){
                var active = map.find('.active');
                active.attr('href', '#prodict-'+$(this).val());
                var original = $('<div>'+$('#areas-data-'+key).val()+'</div>').find('area');
                var areas = map.find('area');
                var text = '';
                areas.each(function(area_id){
                    original.eq(area_id).attr('href', $(this).attr('href'));
                    text += original.eq(area_id).wrap("<div>").parent().html();
                });
                $('#areas-data-'+key).val(text);
            });
            setTimeout(function(){
                $('img[usemap]').rwdImageMaps();
                $('.map_'+key).maphilight();
            }, 100);

            map.on('click', 'area', function(e){
                e.preventDefault();
                var $this = $(this);
                map.find('area').each(function(){
                    $(this).removeClass('active');
                    var d = $(this).data('maphilight') || {};
                    d.alwaysOn = false;
                    $(this).data('maphilight', d);
                });
                $this.addClass('active');
                var d = $this.data('maphilight') || {};
                d.alwaysOn = true;
                $this.data('maphilight', d).trigger('alwaysOn.maphilight');
                var href = $this.attr('href').split('-');
                if(href.length == 2)
                    $('#area_id_'+key).val(href[1]);
            });
        }
    });
});