<?php

namespace App\Models;

//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;

//class User extends Authenticatable
class User extends \Cartalyst\Sentinel\Users\EloquentUser
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'first_name', 'last_name', 'email', 'password',
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function blog()
    {
        return $this->hasOne('App\Models\Blog', 'user_id', 'id');
    }
    public function user_data()
    {
//        return $this->belongsTo('App\Models\UserData', 'user_id', 'id');
        return $this->hasOne('App\Models\UserData', 'user_id', 'id');
    }
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'user_id', 'id');
    }
    public function wishlist()
    {
        return $this->hasMany('App\Models\Wishlist', 'user_id', 'id');
    }
    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'user_id', 'id');
    }

    /**
     * Получение списка желаний пользователя в виде массива id товаров или коллекции товаров
     * в зависимости от входящего аргумента
     *
     * @param $type = array or object
     * @return array|mixed|void
     */
//    public function wishlist($type = 'object')
//    {
//        if (!is_null($this->user_data->wishlist)) {
//            if ($type == 'array') {
//                return json_decode($this->user_data->wishlist, true);
//            } elseif ($type == 'object') {
//                $product = new Product;
//                return $product->getProducts(json_decode($this->user_data->wishlist, true));
//            }
//        } else {
//            return [];
//        }
//    }

    public function checkIfUnregistered($phone, $email){
        return $this->where('email', $email)->orWhere('phone', $phone)->first();
    }

}
