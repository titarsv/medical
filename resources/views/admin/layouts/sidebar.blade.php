<ul class="nav nav-pills nav-stacked">
    <li role="presentation">
        <a href="/admin"><i class="fa fa-tachometer" aria-hidden="true"></i>Главная</a>
    </li>
    <li role="presentation">
        <p data-toggle="collapse" data-target="#products-collapse" class="nav-collapse"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Товары</p>
        <ul id="products-collapse" class="collapse nav nav-pills nav-stacked nav-collapse">
            <li><a href="/admin/products"><i class="fa fa-circle-thin" aria-hidden="true"></i>Каталог товаров</a></li>
            <li><a href="/admin/attributes"><i class="fa fa-circle-thin" aria-hidden="true"></i>Атрибуты товаров</a></li>
            <li><a href="/admin/upload-products"><i class="fa fa-circle-thin" aria-hidden="true"></i>Импорт товаров</a></li>
            {{--<li><a href="/admin/manufacturers"><i class="fa fa-circle-thin" aria-hidden="true"></i>Линейки товаров</a></li>--}}
            {{--<li><a href="/admin/parfum-groups"><i class="fa fa-circle-thin" aria-hidden="true"></i>Группы ароматов</a></li>--}}
            {{--<li><a href="/admin/parfum-notes"><i class="fa fa-circle-thin" aria-hidden="true"></i>Ноты ароматов</a></li>--}}
        </ul>
    </li>
    <li role="presentation">
        <a href="/admin/categories"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i>Категории</a>
    </li>
    <li role="presentation">
        <a href="/admin/modules"><i class="fa fa-code-fork" aria-hidden="true"></i>Модули</a>
    </li>
    <li role="presentation">
        <a href="/admin/orders"><i class="fa fa-shopping-cart" aria-hidden="true"></i>Заказы</a>
    </li>
    <li role="presentation">
        <a href="/admin/blog"><i class="fa fa-newspaper-o" aria-hidden="true"></i>Статьи</a>
    </li>
    <li role="presentation">
        <a href="/admin/news"><i class="fa fa-newspaper-o" aria-hidden="true"></i>Новости</a>
    </li>
    <li role="presentation">
        <a href="/admin/reviews"><i class="fa fa-comments-o" aria-hidden="true"></i>Отзывы
            @if(!$new_reviews->isEmpty())
                <span class="badge">{!! $new_reviews->count() !!}</span>
            @endif
        </a>
    </li>
    <li role="presentation">
        <p data-toggle="collapse" data-target="#users-collapse" class="nav-collapse"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Пользователи</p>
        <ul id="users-collapse" class="collapse nav nav-pills nav-stacked nav-collapse">
            <li><a href="/admin/users"><i class="fa fa-circle-thin" aria-hidden="true"></i>Покупатели</a></li>
            <li><a href="/admin/managers"><i class="fa fa-circle-thin" aria-hidden="true"></i>Менеджеры</a></li>
        </ul>
    </li>
    <li role="presentation">
        <p data-toggle="collapse" data-target="#settings-collapse" class="nav-collapse"><i class="fa fa-wrench" aria-hidden="true"></i>Настройки</p>
        <ul id="settings-collapse" class="collapse nav nav-pills nav-stacked nav-collapse">
            <li><a href="/admin/settings"><i class="fa fa-circle-thin" aria-hidden="true"></i>Общие настройки магазина</a></li>
            <li><a href="/admin/html"><i class="fa fa-circle-thin" aria-hidden="true"></i>HTML-контент</a></li>
        </ul>
    </li>
</ul>