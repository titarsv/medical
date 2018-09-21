@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Каталог товаров
@endsection
@section('content')

    <h1>Редактирование товара</h1>

    @if(session('message-error'))
        <div class="alert alert-danger">
            {{ session('message-error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="form">
        <form method="post">
            {!! csrf_field() !!}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Название</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" data-translit="input" class="form-control" name="name" value="{!! old('name') ? old('name') : $product->name !!}" />
                                    @if($errors->has('name'))
                                        <p class="warning" role="alert">{!! $errors->first('name',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Изображение товара</label>
                                <div class="form-element col-sm-4">
                                    <input type="hidden" id="image" name="image_id" value="{!! old('image_id') ? old('image_id') : $product->image_id !!}" />
                                    <div id="image-output" class="category-image">
                                        <img src="/assets/images/{!! old('href') ? old('href') : (!empty($product->image)?$product->image->href:'no_image.jpg') !!}" />
                                        <button type="button" class="btn btn-del" data-toggle="tooltip" data-placement="bottom" title="Удалить изображение">X</button>
                                        <button type="button" data-open="image" id="add-image" class="btn">Выбрать изображение</button>
                                    </div>
                                </div>
                                <div class="form-element col-sm-3">
                                    <label class="gallery-label">Галлерея</label>
                                     <div class="row gallery-container">
                                         @if(!is_null($product->gallery))
                                             @foreach($product->gallery->objects() as $image)
                                                 @if(is_object($image))
                                                     <div class="col-sm-3">
                                                         <div>
                                                             <i class="remove-gallery-image">-</i>
                                                             <input name="gallery[]" value="{{ $image->id }}" type="hidden">
                                                             <img src="{{ $image->url('product_gallery') }}">
                                                         </div>
                                                     </div>
                                                 @endif
                                             @endforeach
                                         @endif
                                         <div class="col-sm-3 add-gallery-image" id="add-gallery-image">
                                            <div class="add-btn" data-open="gallery"></div>
                                         </div>
                                    </div>
                                </div>
                                <div class="form-element col-sm-3">
                                    <label class="gallery-label">Дополнительные фото</label>
                                    <div class="row gallery-container">
                                        @if(!is_null($product->photos))
                                            @foreach($product->photos->objects() as $image)
                                                @if(is_object($image))
                                                    <div class="col-sm-3">
                                                        <div>
                                                            <i class="remove-gallery-image">-</i>
                                                            <input name="photos[]" value="{{ $image->id }}" type="hidden">
                                                            <img src="{{ $image->url('product_gallery') }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                        <div class="col-sm-3 add-gallery-image" id="add-photos-image">
                                            <div class="add-btn" data-open="photos"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-element col-sm-12">
                                    <div class="form-group" style="margin-top: 30px;">
                                        <div class="row">
                                            <label class="col-sm-2 text-right control-label">Видео</label>
                                            <div class="form-element col-sm-10">
                                                <input type="text" class="form-control" name="video" value="{!! old('video') ? old('video') : $product->video !!}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Краткое описание товара</label>
                                <div class="form-element col-sm-10">
                                    <textarea name="excerpt"
                                              class="form-control"
                                              rows="3">{!! old('excerpt') ? old('excerpt') : $product->excerpt !!}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Полное описание товара</label>
                                <div class="form-element col-sm-10">
                                    <textarea name="description"
                                              id="text-area"
                                              class="form-control"
                                              rows="6">{!! old('description') ? old('description') : $product->description !!}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Опции</label>
                                <div class="form-element col-sm-10">
                                    <textarea name="options"
                                              class="form-control"
                                              rows="6">{!! old('options') ? old('options') : $product->options !!}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Акция</label>
                                <div class="form-element col-sm-10">
                                    <textarea name="action"
                                              class="form-control"
                                              rows="2">{!! old('action') ? old('action') : $product->action !!}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Плашка</label>
                                <div class="form-element col-sm-10">
                                    <select name="label" class="form-control">
                                        @foreach($labels as $label => $label_name)
                                            <option value="{!! $label !!}"
                                                    @if ($label == old('label'))
                                                    selected
                                                    @elseif ($label == $product->label)
                                                    selected
                                                    @endif
                                            >{!! $label_name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Старая цена</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="old_price" value="{!! old('old_price') ? old('old_price') : $product->old_price !!}" />
                                    @if($errors->has('old_price'))
                                        <p class="warning" role="alert">{!! $errors->first('old_price',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Цена</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="price" value="{!! old('price') ? old('price') : $product->price !!}" />
                                    @if($errors->has('price'))
                                        <p class="warning" role="alert">{!! $errors->first('price',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Артикул</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="articul" value="{!! old('articul') ? old('articul') : $product->articul!!}" />
                                    @if($errors->has('articul'))
                                        <p class="warning" role="alert">{!! $errors->first('articul',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Комплект из</label>
                                <div class="form-element col-sm-10">
                                    <select name="sets[]" class="form-control chosen-select" multiple="multiple">
                                        @foreach($sets as $set)
                                            <option value="{!! $set->id !!}"
                                            @if (in_array($set->id, (array)old('ssets')))
                                                    selected
                                                    @elseif (in_array($set->id, $added_set))
                                                    selected
                                                    @endif
                                                    >{!! $set->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Связанные товары</label>
                                <div class="form-element col-sm-10">
                                    <select name="related[]" class="form-control chosen-select" multiple="multiple">
                                        @foreach($sets as $set)
                                            <option value="{!! $set->id !!}"
                                                    @if (in_array($set->id, (array)old('related')))
                                                    selected
                                                    @elseif (in_array($set->id, $related))
                                                    selected
                                                    @endif
                                            >{!! $set->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Наличие товара</label>
                                <div class="form-element col-sm-10">
                                    <select name="stock" class="form-control">
                                        @if(old('stock') || $product->stock)
                                            <option value="1" selected>В наличии</option>
                                            <option value="0">Нет в наличии</option>
                                        @elseif(!old('stock') || !$product->stock)
                                            <option value="1">В наличии</option>
                                            <option value="0" selected>Нет в наличии</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>SEO</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Title</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="meta_title" value="{!! old('meta_title') ? old('meta_title') : $product->meta_title !!}" />
                                    @if($errors->has('meta_title'))
                                        <p class="warning" role="alert">{!! $errors->first('meta_title',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Meta description</label>
                                <div class="form-element col-sm-10">
                                    <textarea name="meta_description" class="form-control" rows="6">{!! old('meta_description') ? old('meta_description') : $product->meta_description !!}</textarea>
                                    @if($errors->has('meta_description'))
                                        <p class="warning" role="alert">{!! $errors->first('meta_description',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Meta keywords</label>
                                <div class="form-element col-sm-10">
                                    <textarea name="meta_keywords" class="form-control" rows="6">{!! old('meta_keywords') ? old('meta_keywords') : $product->meta_keywords !!}</textarea>
                                    @if($errors->has('meta_keywords'))
                                        <p class="warning" role="alert">{!! $errors->first('meta_keywords',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Alias</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" data-translit="output" class="form-control" name="url_alias" value="{!! old('url_alias') ? old('url_alias') : $product->url_alias !!}" />
                                    @if($errors->has('url_alias'))
                                        <p class="warning" role="alert">{!! $errors->first('url_alias',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Robots</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="robots" value="{!! old('robots') ? old('robots') : $product->robots !!}" />
                                    @if($errors->has('robots'))
                                        <p class="warning" role="alert">{!! $errors->first('robots',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Связи</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Категория товара</label>
                                <div class="form-element col-sm-10">
                                    <select name="product_category_id[]" class="form-control chosen-select" multiple="multiple">
                                        @foreach($categories as $category)
                                            <option value="{!! $category->id !!}"
                                                @if (in_array($category->id, (array)old('product_category_id')))
                                                    selected
                                                @elseif (in_array($category->id, $added_categories))
                                                    selected
                                                @endif
                                            >{!! $category->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right">Связанная категория</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<select name="product_related_category_id" class="form-control">--}}
                                        {{--<option value="0">Не выбрано</option>--}}
                                        {{--@foreach($categories as $category)--}}
                                            {{--<option value="{!! $category->id !!}"--}}
                                                    {{--@if ($category->id == old('product_related_category_id'))--}}
                                                    {{--selected--}}
                                                    {{--@elseif ($category->id == $product->product_related_category_id)--}}
                                                    {{--selected--}}
                                                    {{--@endif--}}
                                            {{-->{!! $category->name !!}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Атрибуты товара</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="table table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr class="success">
                                            <td align="center">Выберите атрибут</td>
                                            <td align="center">Выберите значение атрибута</td>
                                            <td align="center">Добавочная стоимость</td>
                                            <td align="center">Действия</td>
                                        </tr>
                                        </thead>
                                        <tbody id="product-attributes">
                                        @if(old('product_attributes') !== null)
                                            @if(session('attributes_error'))
                                                <tr>
                                                    <td colspan="3">
                                                        <p class="warning" role="alert">{!! session('attributes_error') !!}</p>
                                                    </td>
                                                </tr>
                                            @endif
                                            @foreach(old('product_attributes') as $key => $attr)
                                                <tr>
                                                    <td>
                                                        <select class="form-control" onchange="getAttributeValues($(this).val(), '{!! $key !!}')">
                                                            @foreach($attributes as $attribute)
                                                                <option value="{!! $attribute->id !!}"
                                                                        @if ($attribute->id == $attr['id'])
                                                                        selected
                                                                        @endif
                                                                >{!! $attribute->name !!}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td align="center" id="attribute-{!! $key !!}-values">
                                                        <input type="hidden" name="product_attributes[{!! $key !!}][id]" value="{!! $attr['id'] !!}"/>
                                                        <select class="form-control" name="product_attributes[{!! $key !!}][value]">
                                                            @foreach($attributes as $attribute)
                                                                @if($attribute->id == $attr['id'])
                                                                    @foreach($attribute->values as $value)
                                                                        <option value="{!! $value->id !!}"
                                                                                @if ($value->id == $attr['value'])
                                                                                selected
                                                                                @endif
                                                                        >{!! $value->name !!}</option>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td align="center">
                                                        <input type="text" value="{{ !empty($attr['price']) ? $attr['price'] : 0 }}" name="product_attributes[{!! $key !!}][price]" placeholder="Добавочная стоимость">
                                                    </td>
                                                    <td align="center">
                                                        <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <input type="hidden" value="{!! $key !!}" id="attributes-iterator" />
                                        @else
                                            @forelse($product->attributes as $key => $attr)
                                                <tr>
                                                    <td>
                                                        <select class="form-control" onchange="getAttributeValues($(this).val(), '{!! $key !!}')">
                                                            @foreach($attributes as $attribute)
                                                                <option value="{!! $attribute->id !!}"
                                                                        @if ($attribute->id == $attr->attribute_id)
                                                                        selected
                                                                        @endif
                                                                >{!! $attribute->name !!}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td align="center" id="attribute-{!! $key !!}-values">
                                                        <input type="hidden" name="product_attributes[{!! $key !!}][id]" value="{!! $attr->attribute_id !!}"/>
                                                        <select class="form-control" name="product_attributes[{!! $key !!}][value]">
                                                            @foreach($attributes as $attribute)
                                                                @if($attribute->id == $attr->attribute_id)
                                                                    @foreach($attribute->values as $value)
                                                                        <option value="{!! $value->id !!}"
                                                                                @if ($value->id == $attr->attribute_value_id)
                                                                                selected
                                                                                @endif
                                                                        >{!! $value->name !!}</option>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td align="center">
                                                        <input type="text" value="{{ !empty($attr->price) ? $attr->price : 0 }}" name="product_attributes[{!! $key !!}][price]" placeholder="Добавочная стоимость">
                                                    </td>
                                                    <td align="center">
                                                        <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                    </td>
                                                </tr>

                                                @if($key == count($product->attributes) - 1)
                                                    <input type="hidden" value="{!! $key !!}" id="attributes-iterator" />
                                                @endif
                                            @empty
                                                <input type="hidden" value="0" id="attributes-iterator" />
                                            @endforelse
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td align="center">
                                                <button type="button" id="add-attribute" onclick="getAttributes();" class="btn">Добавить</button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <script src="/js/libs/transliterate.js"></script>

    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'text-area', {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
        });
        CKEDITOR.config.allowedContent = true;
    </script>
@endsection
@section('before_footer')
    @include('admin.layouts.imagesloader')
@endsection
