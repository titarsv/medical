<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

use App\Http\Requests;
use App\Models\PersonalSale;

class PersonalSalesController extends Controller
{
    private $rules = [
        'name' => 'required',
        'email'     => 'required|email'
    ];

    private $messages = [
        'name.required' => 'Не заполнено имя!',
        'email.required'    => 'Не заполнен E-mail!',
        'email.email'       => 'Некорректный email-адрес!'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, PersonalSale $sales)
    {
        if($request->status == 'old')
            $personal_sales = $sales::where('status', '<>', 'new')->orderBy('updated_at', 'desc')->paginate(20);
        else
            $personal_sales = $sales::where('status', 'new')->orderBy('updated_at', 'desc')->paginate(20);

        return view('admin.personal_sales.index', [
            'sales'        => $personal_sales,
            'status'       => $request->status,
            'status_array' => [
                'new' => 'Новый',
                'analog' => 'Предложен аналог',
                'sale' => 'Дана скидка',
                'canceled' => 'Отклонён',
                'mail' => 'Отпревлен ответ'
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, PersonalSale $sales)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if($validator->fails()){
            $errors = $validator->messages();
            return response()->json(['error' => $errors]);
        }

        $sales->fill($request->except('_token', 'order', 'tagmanager'));
        $sales->lag = Carbon::now()->addDays(rand(3, 8))->format('Y-m-d H:i:s');

        $sales->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $sale = PersonalSale::find($id);
        $sale->update(['status' => 'canceled']);

        return redirect('/admin/personal_sales')
            ->with('message-success', 'Запрос успешно отклонён!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PersonalSale $sales)
    {
        if(!empty($request->id)){
            $sale = $sales->find($request->id);
            $product = $sale->product;
            if($request->unit == '%'){
                $sale_price = $product->price * (1 - $request->sale_size / 100);
            }elseif($request->unit == 'uah'){
                $sale_price = $product->price - $request->sale_size;
            }

            if(isset($sale_price))
                $sale->update(['status' => 'sale', 'sale_price' =>  $sale_price]);
        }

        return redirect('/admin/personal_sales')
            ->with('message-success', 'Запрос ' . $sale->id . ' успешно обновлён.');
    }
}
