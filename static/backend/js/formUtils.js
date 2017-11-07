window['forms'] = {
    loadForm: function(form) {
        var url = forms.extendUrl(window.location.toString(), 'form', form);

        $.ajax(url, {
            type: 'GET',
            success: (data) => {

                var $page = $('<div/>').append(data);
                var newForm = $page.find('.form-page');

                $(document).find('.form-page').replaceWith(newForm);
            }
        });
    },

    extendUrl: function(url, key, value) {
        var params = {};
        var cleanUrl = url;

        if (url.indexOf('?') !== -1) {
            cleanUrl = url.substr(0, url.indexOf('?'));
            var paramsString = url.substr(url.indexOf('?') + 1);
            params = $.deparam(paramsString);
        }
        params[key] = value;
        paramsString = $.param(params);

        return cleanUrl + '?' + paramsString;
    }
};