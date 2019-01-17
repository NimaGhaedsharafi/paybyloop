<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" media="(min-device-width: 320px)" type="text/css" href="{{ asset('css/valid-wide.css') }}">
    <link rel="stylesheet" media="only screen and (max-device-width : 450px)" type="text/css" href="{{ asset('css/valid.css') }}">
    <title>رسید پرداخت - لوپ</title>
</head>
<div>
    <img id="logo" src="{{ asset('img/icon.png') }}">
</div>
<body>
    <div id="box">
        <img id="tick" src="{{ asset('img/receipt.png') }}">
        <div id="status">
            رسید پرداخت موفق با لوپ
        </div>
        <div id="info">
            <div style="overflow: hidden">
                <div id="info-titles">
                    <div id="info-top">نام پذیرنده:</div>
                    <div id="info-top">مبلغ پرداخت شده:</div>
                </div>
                <div id="info-datas">
                        <div id="info-top">{{ $vendor }}</div>
                        <div id="info-top">{{ $amount }} تومان</div>
                </div>
            </div>
            <hr style="border-color: rgb(170,185,191);">
            <div style="overflow: hidden">
                <div id="info-titles">
                    <div id="info-bottom">مبلغ کل:</div>
                    <div id="info-bottom">تاریخ پرداخت:</div>
                    <div id="info-bottom">زمان پرداخت:</div>
                    <div id="info-bottom">کد رهگیری:</div>
                </div>
                <div id="info-datas">
                    <div id="info-bottom">{{ $total }} تومان</div>
                    <div id="info-bottom">{{ $date  }}</div>
                    <div id="info-bottom">{{ $time }}</div>
                    <div id="info-bottom">{{ $reference }}</div>
                </div>
            </div>
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