<!DOCTYPE html>
<html dir="rtl" style="height: 100%">
<!--<meta http-equiv="refresh" content="3">-->
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/success.css') }}">
    <title>شارژ موفق</title>
</head>
<body>
<img id="logo" src="{{ asset('img/loop.png') }}">
<div id="statusContainer">
    <img id="successTick" src="{{ asset('img/tick.png') }}">
    <div id="caption">
        عملیات موفق
        <div>کد رهگیری: {{ $ref }}</div>
    </div>
    <div id="topUpAmount">
        {{ $amount }}<div id="currency">
            تومان
        </div>
    </div>
    <div id="statusText">
        شارژ به کیف پول اضافه شد.
    </div>
    <div id="callToAction">
        <button class="button" type="button">بازگشت به لوپ</button>
    </div>
</div>
<div style="flex-grow: 1;"></div>
<footer>
    <div id="footerTelephone">
        تلفن پشتیبانی: ۲۲۲۲۲۲۲۲-۰۲۱
        <hr>
        شرکت راه نو پردازان آسیا - ۱۳۹۷
    </div>
</footer>
</body>
</html>
