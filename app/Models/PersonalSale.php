<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;

class PersonalSale extends Model
{
    use SoftDeletes;

    protected $table = 'personal_sales';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'product_id',
        'analog',
        'lag',
        'sale_price',
        'status'
    ];

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'email', 'email');
    }

    /**
     * Рассылка
     */
    public function delivery(){
        $sales = $this->where('status', 'sale')->whereDate('lag', '<', date('Y-m-d h:i:s'))->get();

        if($sales->count()) {
            $setting = new Setting();

            foreach ($sales as $sale) {
                Mail::send('emails.sale', ['sale' => $sale], function ($msg) use ($setting, $sale) {
                    $msg->from($setting->get_setting('site_email'), 'Интернет-магазин Lab Oborud');
                    $msg->to($sale->email);
                    $msg->subject('Обновление цены на сайте Lab Oborud');
                });

                $sale->update(['status' => 'mail']);
            }
        }
    }


}
