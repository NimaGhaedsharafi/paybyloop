function redirectToApp() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

      // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
        //console.log("Windows Phone");
        return;
    }

    if (/android/i.test(userAgent)) {
        //console.log("Android");
        window.location.href = 'intent:#Intent;scheme=openloop;package=app.paybyloop.loop;end';
        return;
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        window.location.href = 'paybyloop://';
        //console.log("iOS");
        return;
    }
    //console.log("Other");
    
}