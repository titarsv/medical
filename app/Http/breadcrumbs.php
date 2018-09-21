<?php

/**
 * Home
 */
Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push('Главная', url('/'));
});

/**
 * User
 */
Breadcrumbs::register('user', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Личный кабинет', url('/user'));
    $breadcrumbs->push('Личные данные');
});

Breadcrumbs::register('history', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Личный кабинет', url('/user'));
    $breadcrumbs->push('История заказов');
});

Breadcrumbs::register('wishlist', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Личный кабинет', url('/user'));
    $breadcrumbs->push('Список желаний');
});

Breadcrumbs::register('change_user', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Личный кабинет');
});

/**
 * Categories
 */
Breadcrumbs::register('categories', function($breadcrumbs, $category) {
    $breadcrumbs->parent('home');
    if(!empty($category[0])) {
        foreach (array_reverse($category[0]->get_parent_categories()) as $category) {
            if (!empty($category)) {
                if (is_object($category[0])) {
                    $name = $category[0]->name;
                    $alias = $category[0]->url_alias;
                } else {
                    $name = $category['name'];
                    $alias = $category['url_alias'];
                }
            }
            $breadcrumbs->push($name, url('/categories/' . $alias));
        }
    }elseif(is_object($category)){
        foreach (array_reverse($category->get_parent_categories()) as $category) {
            if (!empty($category)) {
                if (is_object($category[0])) {
                    $name = $category[0]->name;
                    $alias = $category[0]->url_alias;
                } else {
                    $name = $category['name'];
                    $alias = $category['url_alias'];
                }
            }
            $breadcrumbs->push($name, url('/categories/' . $alias));
        }
    }else{
        if (!empty($category)) {
            if (is_object($category[0])) {
                $name = $category[0]->name;
                $alias = $category[0]->url_alias;
            } else {
                $name = $category['name'];
                $alias = $category['url_alias'];
            }
        }
        $breadcrumbs->push($name, url('/categories/' . $alias));
    }
});

/**
 * Articles
 */
Breadcrumbs::register('blog', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Статьи', url('/articles'));
});

Breadcrumbs::register('blog_item', function($breadcrumbs, $article) {
    $breadcrumbs->parent('blog');
    $breadcrumbs->push($article->title);
});

/**
 * News
 */
Breadcrumbs::register('news', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Новости', url('/news'));
});

Breadcrumbs::register('news_item', function($breadcrumbs, $article) {
    $breadcrumbs->parent('news');
    $breadcrumbs->push($article->title);
});

/**
 * HTML Pages
 */
Breadcrumbs::register('html', function($breadcrumbs, $page) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push($page->name);
});

/**
 * Login and register
 */
Breadcrumbs::register('login', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Авторизация', url('/login'));
});

Breadcrumbs::register('register', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Регистрация');
});

Breadcrumbs::register('forgotten', function($breadcrumbs) {
    $breadcrumbs->parent('login');
    $breadcrumbs->push('Восстановление пароля');
});

/**
 * Products
 */
Breadcrumbs::register('product', function($breadcrumbs, $product, $category) {
    if($category->count()) {
        $breadcrumbs->parent('categories', $category);
    }
    $breadcrumbs->push($product->name);
});

/**
 * Search
 */
Breadcrumbs::register('search', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Поиск', url('/search'));
});

/**
 * Оформление заказа
 */
Breadcrumbs::register('cart', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Оформление заказа');
});