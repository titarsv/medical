<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/loadimages',
        'admin/upload',
        'admin/upload_attribute_image',
        'admin/products/getattributevalues',
        '/livesearch',
        '/wishlist/update',
        'cart/update',
        'cart/updateAll',
        'cart/get',
        'get_models',
        'get_years',
        '/checkout/cities',
        '/checkout/warehouses',
        '/checkout/delivery',
        '/checkout/confirm',
    ];
}
