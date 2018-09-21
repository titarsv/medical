<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = 'orders';
    protected $fillable = [
        'user_id',
        'products',
        'total_price',
        'total_quantity',
        'status_id',
        'user_info',
        'delivery',
        'payment'
    ];

    /**
     * Связь с моделью статусов заказа
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('App\Models\OrderStatus', 'status_id');
    }

    /**
     * Получение незавершенного заказа по id пользователя
     *
     * @param $user
     * @return int
     */
    public function getCurrentIncompleteOrder($user)
    {
        if ($user) {
            $order = $this->where('user_id', $user->id)->where('status_id', 0)->first();

            if (!is_null($order)) {
                return $order->id;
            }
        }
        return 0;
    }

    /**
     * Товары в заказе
     *
     * @return array
     */
    public function getProducts()
    {
        $products = json_decode($this->products, true);
        $result = [];

        foreach ($products as $product_code => $data) {
            $product_vars = explode('_', $product_code);
            $result[] = [
                'product'   => Products::where('id', $product_vars[0])->with('attributes.value', 'image')->first(),
                'quantity'  => $data['quantity'],
                'variations'  => isset($data['variations'])?$data['variations']:[],
                'price'  => $data['price']
            ];
        }

        return $result;
    }

    /**
     * Информация о доставке в удобночитаемом формате
     *
     * @return array
     */
    public function getDeliveryInfo()
    {
        $delivery_info = json_decode($this->delivery, true);
        $newpost = new Newpost();

        if ($delivery_info['method'] == 'newpost') {
            return [
                'method'    => 'Новая Почта',
                'region'    => $newpost->getRegionRef($delivery_info['info']['region'])->name,
                'city'      => $newpost->getCityRef($delivery_info['info']['city'])->name_ru,
                'warehouse' => $newpost->getWarehouse($delivery_info['info']['warehouse'])->address_ru,
            ];
        } elseif ($delivery_info['method'] == 'ukrpost') {
            $delivery_info['info']['method'] = 'Укрпочта';
            return $delivery_info['info'];
        } elseif ($delivery_info['method'] == 'courier') {
            $delivery_info['info']['method'] = 'Доставка курьером по Харькову';
            return $delivery_info['info'];
        } elseif ($delivery_info['method'] == 'pickup') {
            return [
                'method' => 'Самовывоз'
            ];
        }

        return [
            'error' => 'Невозможно отобразить информацию о доставке!'
        ];
    }
}
