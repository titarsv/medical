<div id="courier-checkout-selection">
    <div class="row">
        <div class="col-sm-4">
            <label for="checkout-step__city" class="checkout-step__label checkout-step__del-info">Адрес:</label>
        </div>
        <div class="col-sm-8">
            <div class="checkout-step__del-info clearfix">
                <input type="text" class="checkout-step__del-info-input checkout-step__del-info-input_long" name="courier[street]" placeholder="Улица:">
                <input type="text" class="checkout-step__del-info-input checkout-step__del-info-input_short" name="courier[house]" placeholder="Дом:">
                <input type="text" class="checkout-step__del-info-input checkout-step__del-info-input_short" name="courier[apart]" placeholder="Кв:">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <label for="checkout-step__payment" class="checkout-step__label">Способ оплаты</label>
    </div>
    <div class="col-sm-8">
        <select name="payment" id="checkout-step__payment" class="checkout-step__select">
            <option value="cash">Наличными при получении</option>
            <option value="prepayment">Предоплата</option>
        </select>
    </div>
</div>