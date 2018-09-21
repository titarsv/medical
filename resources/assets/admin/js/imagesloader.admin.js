$(document).ready(function(){

    $(document).on('click', '[data-open]', function(){

        buttonOpen = document.querySelectorAll('[data-open]');
        var button = $('#openLibrary');

        /**
         * По нажатию на кнопку добавления открываем библиотеку изображений и добавляем активный класс к кнопке, которая была нажата
         * Исходя из того, что кнопок несколько - для разных изображений, делаем развилку назначений активного класса, чтобы избежать дубляжа
         * @type {any}
         */
        if (!events.libraryOpened) {
            events.callEvent('imageLoaderOpened', {button: button});
            $(this).addClass('active');
        } else {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                events.callEvent('imageLoaderClosed', {button: button});
            } else {
                $(buttonOpen).removeClass('active');
                $(this).addClass('active');
            }

        }
    });

    /**
     * При условии выбора изображения(ий)
     * Делаем развилку условий, которая позволить назначить только одну картинку как изображение товара
     * (которое будет показано в категории и как главное изображение в карточке товара)
     * И второе условие - которое позволит добавить несколько изображений к товару, которые могут быть показаны при нажатии на главное изображение
     */
    imagesLoaderEvents.on('filesSelected',function(data){
        var image_area = $('#image-output');

        $.each(buttonOpen, function (i, value) {
            if ($(value).hasClass('active') && $(value).attr('data-open') == 'image') {
                var ig = data.ig;
                var file = {
                    id: ig.collection[0].get('id'),
                    href: ig.collection[0].get('href')
                };
                $('#image').val(file.id);

                ig.unset();
                image_area.find('img').remove();
                image_area.prepend('<img src="/assets/images/'+file.href+'">');

            } else if ($(value).hasClass('active') && $(value).attr('data-open') == 'gallery') {
                var ig = data.ig;
                for(var id in ig.collection){
                    var file = {
                        id: ig.collection[id].get('id'),
                        href: ig.collection[id].get('href')
                    };

                    $('#add-gallery-image').before('<div class="col-sm-3">' +
                        '<div>' +
                        '<i class="remove-gallery-image">-</i>' +
                        '<input type="hidden" name="gallery[]" value="' + file.id + '">' +
                        '<img src="/assets/images/' + file.href + '">' +
                        '</div>' +
                        '</div>');
                }

                ig.unset();
                data.ig.collection.splice(0, data.ig.collection.length);
                data.ig.collection.length = 0;

            } else if ($(value).hasClass('active') && $(value).attr('data-open') == 'module-image') {
                var ig = data.ig;
                var file = {
                    id: ig.collection[0].get('id'),
                    href: ig.collection[0].get('href')
                };
                var key = $(this).attr('data-key');
                var img_output = $('#module-image-output-' + key);
                $('#module-image-' + key).val(file.id);

                ig.unset();
                img_output.find('img').remove();
                img_output.prepend('<img src="/assets/images/'+file.href+'">');

            }

            $('.btn-del').show();
            $(value).removeClass('active');
            $('#imagesloader-modal').modal('hide');
        });

    });

    $('.gallery-container').on('click', '.remove-gallery-image', function(){
        $(this).parent().parent().remove();
    });
});