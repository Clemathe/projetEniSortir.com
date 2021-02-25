var getHttpRequest = function () {
    var httpRequest = false;

    if (window.XMLHttpRequest) { // Mozilla, Safari,...
        httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
            httpRequest.overrideMimeType('text/xml');
        }
    }
    else if (window.ActiveXObject) { // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {}
        }
    }

    if (!httpRequest) {
        alert('Abandon :( Impossible de créer une instance XMLHTTP');
        return false;
    }

    return httpRequest
}
// var xhr = getHttpRequest()
// xhr.open('GET', 'http://localhost/demo', true)
// // On envoit un header pour indiquer au serveur que la page est appellée en Ajax
// xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest')
// // On lance la requête
// xhr.send()

var q = document.querySelector('q')
