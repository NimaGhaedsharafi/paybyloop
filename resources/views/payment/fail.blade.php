<!DOCTYPE html>
<html dir="rtl" style="height: 100%">
<!--<meta http-equiv="refresh" content="3">-->
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/failure.css') }}">
    <script type="text/javascript" src="{{ asset('js/redirect.js') }}"></script>

    <title>تراکنش ناموفق</title>
</head>
<body>
<img id="logo" src="{{ asset('img/loop.png') }}">
<div id="statusContainer">
    <img id="successTick" src="{{ asset('img/fail.png') }}">
    <div id="caption">
        عملیات ناموفق
        <div>کد رهگیری: {{ $ref }}</div>
    </div>
    <div id="statusText">
        متاسفانه عملیات شارژ با خطا مواجه گردید.
    </div>
    <div id="callToAction">
        <a onclick="redirectToApp()" class="button" type="button">بازگشت به لوپ</a>
    </div>
</div>
<div style="flex-grow: 1;"></div>
<footer>
    <div id="footerTelephone">
        تلفن پشتیبانی: ۲۸۴۲۵۳۱۵-۰۲۱
        <hr>
        شرکت راه نو پردازان آسانا - ۱۳۹۷
    </div>
</footer>
</body>
</html>
