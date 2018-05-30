$(document).ajaxComplete(function (e, xhr, settings) {
    if (xhr.status == 278) {
        let location = xhr.getResponseHeader("Location");
        if (location) {
            window.location.href = xhr.getResponseHeader("Location");
        }
    }
});