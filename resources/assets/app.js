// God save the Dev

'use strict';

if (process.env.NODE_ENV !== 'production') {
    require('./assets/templates/layouts/index.html');
}

// Depends
var $ = require('jquery');
require('bootstrap');
require('./jquery.multipurpose_tabcontent.js');

// Modules
var Forms = require('_modules/forms');
var Slider = require('_modules/slider');
var Popup = require('_modules/popup');
var Fancy_select = require('_modules/fancyselect');
var Jscrollpane = require('_modules/jscrollpane');
// var LightGallery = require('_modules/lightgallery');
//var Jslider = require('_modules/jslider');
var Fancybox = require('_modules/fancybox');
var Chosen = require('_modules/chosen');
var Zoom = require('_modules/elevate-zoom');

// счетчики
require('../../node_modules/odometer/odometer.js');

// обрезание текста
require('./modules/succinct/succinct.js');

// Stylesheet entrypoint
require('_stylesheets/app.scss');

require('jquery.nicescroll');

require('_modules/jquery-ui');

// Are you ready?
$(function() {
    new Forms();
    new Popup();
    new Fancy_select();
    new Jscrollpane();
    // new LightGallery();
    new Slider();
    //new Jslider();
    new Fancybox();
    new Chosen();
    new Zoom();

    /* слайдер цен */
    var price_range = $('.price-range');
    if(price_range.length) {
        price_range.slider({
            min: price_range.data('min'),
            max: price_range.data('max'),
            values: price_range.data('value').split(';'),
            range: true,
            slide: function (event, ui) {
                for (var i = 0; i < ui.values.length; ++i) {
                    $('input.sliderValue[data-index=' + i + ']').val(ui.values[i]);
                    //$('.clear-filters').addClass('active');
                }

            },
            stop: function( event, ui ) {
                $(this).parents('form').submit();
            }
        });

        $('input.sliderValue').change(function () {
            var $this = $(this);
            $('.price-range').slider('values', $this.data('index'), $this.val());
        });
    }

    var price_range_min = $('.price-range-min');
    if(price_range_min.length) {
        price_range_min.slider({
            min: price_range_min.data('min'),
            max: price_range_min.data('max'),
            values: price_range_min.data('value').split(';'),
            range: true,
            slide: function (event, ui) {
                for (var i = 0; i < ui.values.length; ++i) {
                    $('input.sliderValueMin[data-index=' + i + ']').val(ui.values[i]);
                    //$('.clear-filters').addClass('active');
                }

            },
            stop: function( event, ui ) {
                $(this).parents('form').submit();
            }
        });

        $('input.sliderValueMin').change(function () {
            var $this = $(this);
            $('.price-range-min').slider('values', $this.data('index'), $this.val());
        });
    }

    // Прокрутка к якорю
    $('.go_to').each(function() {
        var $this = $(this);
        $this.click(function() {
            var scroll_el = $($this.data('destination'));
            if ($(scroll_el).length != 0) {
                $('html, body, .content-pusher').animate({
                    scrollTop: $(scroll_el).offset().top
                }, 500);
            }
            // return false;
        });
    });

    $(document).on('scroll', function(){
        if( $(document).scrollTop() > $('.siteHeader').height() + 10 ){
            $('.siteHeader-nav').addClass('fixed');
            $('.siteHeader-nav-wrp').addClass('container-fluid') && $('.siteHeader-nav-wrp').removeClass('container');
        }else{
            $('.siteHeader-nav').removeClass('fixed');
            $('.siteHeader-nav-wrp').removeClass('container-fluid') && $('.siteHeader-nav-wrp').addClass('container');
        }
    });
    if( $(document).scrollTop() > $('.siteHeader').height() + 10 ){
        $('.siteHeader-nav').addClass('fixed');
    }else{
        $('.siteHeader-nav').removeClass('fixed');
    }

    var $toggleElem = $('.siteHeader .js-toggle');

    if($('body').width() < 992){
        $toggleElem.click(function (e) {
            e.preventDefault();
            var $toggleTarget = $($(this).data('toggle'));
            $toggleTarget.toggleClass('is-open');
            e.stopPropagation();
            hideOnClickOutside($(this).data('toggle'));
        });
    }else{
        $toggleElem.mouseenter(function (e) {
            // e.preventDefault();
            var $toggleTarget = $($(this).data('toggle'));
            $toggleTarget.toggleClass('is-open');
            e.stopPropagation();
            hideOnClickOutside($(this).data('toggle'));
        });
    }

    // $('.siteFooter').click(function(){
    //     jQuery('.siteHeader .js-toggle').trigger('mouseenter');
    // });

    function hideOnClickOutside(element) {
        $(document).click(function(event) {
            if(!$(event.target).closest(element).length) {
                if($(element).is(":visible")  && $(element).hasClass('is-open')) {
                    $(element).removeClass('is-open');
                }
            }
        });
    }
    //$('.js-slider').slick({
    //    centerMode: true,
    //    variableWidth: true,
    //    centerPadding: '60px',
    //    slidesToShow: 1
    //});

    $(".demo").champ();

    $(".demo .accordian_header").click(function(){
        if($(document).width() < 737){
            var $this = $(this);
            setTimeout(function(){
                $('html, body, .content-pusher').animate({
                    scrollTop: $this.offset().top - $('.siteHeader-nav').height() - 15
                }, 500);
            }, 400);
        }
    });

    // $(".accordion_filter").champ({
    //     plugin_type :  "accordion",
    //     side : "left",
    //     active_tab : "1",
    //     controllers : "true"
    // });
    $('.collapse').collapse();

    // $('.content_wrapper').on('click', '.accordian_header.active', function(){
    //     var $this = $(this);
    //     $this.removeClass('active');
    //     $this.next().removeClass('active').css('display', 'none');
    // });

    $('.filterList .accordian_header').click(function(){
        var $this = $(this);

        if($this.hasClass('active')){
            $this.next().slideUp(function(){
                $this.removeClass('active');
            });
        }else{
            $this.addClass('active');
            $this.next().slideDown();
        }
    });

    if($('.mobile-menu-container').length){
        $("body").wrapInner('<div id="wrapper-container" class="wrapper-container nav-top right three-d"><div class="content-pusher"></div></div>');
        if($(".navbar").hasClass("navbar-fixed-bottom")){
            $(".content-pusher").append($(".yogurt-navbar-container"));
        }
        if($(".wrapper-container.three-d").length){
            $(".mobile-menu-container").prependTo("#wrapper-container");
        }
        $('.navbar-toggle.collapsed[data-toggle="mobile-side"]').on("click", function(){
            $("#wrapper-container").toggleClass("mobile-menu-open");
            if($("#wrapper-container").hasClass("mobile-menu-open")){
                $("#wrapper-container").css({"perspective":"2000px", "height":"100%"});
            }else{
                setTimeout(function(){$("#wrapper-container").css({"perspective":"none", "height":"auto"});}, 500);
            }
        });

        $('.content-pusher').on('scroll', function(){
            if( $('.content-pusher').scrollTop() > $('.siteHeader').height() + 10 ){
                $('.siteHeader-nav').addClass('fixed');
                $('.siteHeader-nav-wrp').addClass('container-fluid') && $('.siteHeader-nav-wrp').removeClass('container');
            }else{
                $('.siteHeader-nav').removeClass('fixed');
                $('.siteHeader-nav-wrp').removeClass('container-fluid') && $('.siteHeader-nav-wrp').addClass('container');
            }
        });
        if( $('.content-pusher').scrollTop() > $('.siteHeader').height() + 10 ){
            $('.siteHeader-nav').addClass('fixed');
            $('.siteHeader-nav-wrp').addClass('container-fluid') && $('.siteHeader-nav-wrp').removeClass('container');
        }else{
            $('.siteHeader-nav').removeClass('fixed');
            $('.siteHeader-nav-wrp').removeClass('container-fluid') && $('.siteHeader-nav-wrp').addClass('container');
        }
    }

    $('.slick-slider').on('init', function(event, slick, currentSlide, nextSlide){
        $(document).resize();
        setTimeout(function(){
            $(document).resize();
        }, 500)
    });

    var search_output = $('[data-output="search-results"]');

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
                            html += '<a href="/product/'+value.url+'">';
                            html += value.name;
                            html += '</a>';
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

    // $('.search-results').on('click', 'li.selectable', function(){
    //     var product_id = $(this).attr('data-id');
    //     var product_name = $(this).attr('data-name');
    //     var existed_products = [];
    //
    //     $.each($('.selected-products'), function (i, value) {
    //         existed_products.push($(value).val());
    //     });
    //
    //     if (($.inArray(product_id, existed_products)) == -1) {
    //
    //         var html = '<li>' + product_name;
    //         html += '<input type="hidden" class="selected-products" name="settings[products][]" value="' + product_id + '">';
    //         html += '<span aria-hidden="true" onclick="$(this).parent().remove()">&nbsp;Удалить</span>';
    //         html += '</li>';
    //
    //         $.each($('[data-autocomplete="selected-products"]'), function (i, value) {
    //             if($(value).attr('data-target') == target) {
    //                 var output = $(value).children('ul');
    //
    //                 if ($(output).find('li.empty').length) {
    //                     output.html(html);
    //                 } else {
    //                     output.append(html);
    //                 }
    //             }
    //         });
    //
    //         $('[data-autocomplete="input-search"]').val('');
    //         search_output.hide();
    //
    //     } else {
    //         search_output.html('<ul><li>Этот товар уже добавлен!</li></ul>');
    //     }
    // });

    document.oncopy = function () { var bodyElement = document.body; var selection = getSelection(); var href = document.location.href; var copyright = "<br><br>Источник: <a href='"+ href +"'>" + href + "</a>"; var text = selection + copyright; var divElement = document.createElement('div'); divElement.style.position = 'absolute'; divElement.style.left = '-99999px'; divElement.innerHTML = text; bodyElement.appendChild(divElement); selection.selectAllChildren(divElement); setTimeout(function() { bodyElement.removeChild(divElement); }, 0); };
});
// CATEGORY`S ASIDE MENU
$('.filterList-category-item').click(function(event){
    var children = $(this).next('.filterList-category-accordion');
    if(children.length){
        children.toggleClass('hidden');
        event.preventDefault();
        return false;
    }
});
$('.filterList-category-item a').click(function(event){
    var children = $(this).parent().parent().next('.filterList-category-accordion');
    if(children.length){
        children.toggleClass('hidden');
        event.preventDefault();
        return false;
    }
});
// $('.filterList-category-item a').click(function(event) {
//     event.preventDefault();
// });

window.onload = function(){
    $(document).resize();
};

require('./custom.js');