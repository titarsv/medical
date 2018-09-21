<div class="header" style="text-align: center;">
    <img src="{!! url('/img/logo.jpg') !!}" alt="logo" title="Lab Oborud" width="217" height="67" />

    <p style="font-size: 20px;">Новое сообщение на сайте Lab Oborud!</p>

    @if(!empty($$phone))
    <p style="font-size: 20px;">Телефон:<b>{{ $phone }}</b></p>
    @endif
    @if(!empty($email))
        <p style="font-size: 20px;">Email:<b>{{ $email }}</b></p>
    @endif

    <p>Хочу получать информацию о скидках и специальных предложениях.</p>
</div>