$(function () {
    var urlVars = getUrlVars();
    var notif = urlVars["notif"];
    var type = urlVars["type"];

    if (typeof notif !== 'undefined') {
        notification(type, notif);
    }
});

// URL Parameter auslesen
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });

    //GET-Paramter löschen
    window.history.pushState("", "", '/');

    return vars;
}

// Notifications anzeigen
function notification(type = 'info', code) {

    var language = readLanguageCookie();
    
    // Standardsprache setzen
    if (language === null){
        language = 'DE';
    }

    // JSON einlesen
    $.getJSON("lang/notifications_" + language + ".json", function (data) {

        // Notification Parameter auslesen
        var notification = data[type][code];

        // Direkte Fehlerausgabe wenn kein Fehlertext hinterlegt ist
        if (typeof (notification) === 'undefined') {

            // Fehler ausgeben. Wird nicht von selbst ausgeblendet
            toastr['error']('Melde diesen Code dem Administrator:<br>' + code, 'Fehler', {
                "timeOut": "0",
                "extendedTimeout": "0"
            });

        } else {

            if (type === 'error') {
                // Notification anzeigen. Wird nicht von selbst ausgeblendet
                toastr[type](notification.text, notification.title, {
                    "timeOut": "0",
                    "extendedTimeout": "0"
                });
            } else {
                // Notification anzeigen. Wird von selbst ausgeblendet
                toastr[type](notification.text, notification.title);
            }
        }


    });
}

// Cockie auslesen
function readLanguageCookie() {
    var nameEQ = 'LANGUAGE=';
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1, c.length);
        }
        if (c.indexOf(nameEQ) == 0) {
            return c.substring(nameEQ.length, c.length);
        }
    }
    return null;
}
