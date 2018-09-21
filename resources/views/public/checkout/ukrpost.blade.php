<div id="ukrpost-checkout-selection">
    <div class="row">
        <div class="col-sm-4">
            <label for="checkout-step__city" class="checkout-step__label checkout-step__del-info">Адрес:</label>
        </div>
        <div class="col-sm-8">
            <div class="checkout-step__del-info clearfix">
                <input type="text" class="checkout-step__del-info-input checkout-step__del-info-input_long" style="width: 100%;" name="ukrpost[region]" placeholder="Область:" />
            </div>
            <div class="checkout-step__del-info clearfix">
                <input type="text" class="checkout-step__del-info-input checkout-step__del-info-input_long" name="ukrpost[city]" placeholder="Город:" />
                <input type="text" class="checkout-step__del-info-input checkout-step__del-info-input_medium" name="ukrpost[index]" placeholder="Индекс:">
            </div>
            <div class="checkout-step__del-info clearfix">
                <input type="text" class="checkout-step__del-info-input checkout-step__del-info-input_long" name="ukrpost[street]" placeholder="Улица:">
                <input type="text" class="checkout-step__del-info-input checkout-step__del-info-input_short" name="ukrpost[house]" placeholder="Дом:">
                <input type="text" class="checkout-step__del-info-input checkout-step__del-info-input_short" name="ukrpost[apart]" placeholder="Кв:">
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
            <option value="prepayment">Предоплата</option>
        </select>
    </div>
</div>