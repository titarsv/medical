<div id="newpost-checkout-selection">

    <div class="order-page__form-select-wrapper">
        <select id="checkout-step__region" class="order-page__form-select" name="newpost[region]" onchange="newpostUpdate('region', $(this).val());">
            <option value="0">Выберите область</option>
            @foreach($regions as $region)
                <option value="{!! $region->id !!}">{!! $region->name !!}</option>
            @endforeach
        </select>
    </div>

    <div class="order-page__form-select-wrapper">
        <select id="checkout-step__city" class="order-page__form-select" name="newpost[city]" onchange="newpostUpdate('city', $(this).val());">
            <option value="0">Сначала выберите город!</option>
        </select>
    </div>

    <div class="order-page__form-select-wrapper">
        <select id="checkout-step__warehouse" class="order-page__form-select" name="newpost[warehouse]">
            <option value="0">Сначала выберите город!</option>
        </select>
    </div>

    <div class="order-page__form-select-wrapper">
        <select name="payment" id="checkout-step__payment" class="order-page__form-select">
            <option disabled="" selected="">Выберите способ оплаты</option>
            <option value="cash">Наличными при самовывозе</option>
            <option value="prepayment">Предоплата</option>
        </select>
    </div>

</div>