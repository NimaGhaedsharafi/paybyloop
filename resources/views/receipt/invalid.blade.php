<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" media="(min-device-width: 320px)" type="text/css" href="{{ asset('css/invalid-wide.css') }}">
    <link rel="stylesheet" media="only screen and (max-device-width : 450px)" type="text/css" href="{{ asset('css/invalid.css') }}">
    <title>پرداخت غیرمجاز - لوپ</title>
</head>
<div>
    <img id="logo" src="{{ asset('img/icon.png') }}">
</div>
<body>
    <div id="box">
        <img id="cross" src="{{ asset('img/fail.png') }}">
        <div id="status">
            این پرداخت مورد تایید لوپ نیست
        </div>
    </div>
    <div id="footer">
        تماس با پشتیبانی لوپ
        <div id="footer-caption">
            ۰۲۱-۲۸۴۲۵۳۱۵
        </div>
    </div>
</body>

</html>