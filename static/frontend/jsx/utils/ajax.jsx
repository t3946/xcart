import $ from 'jquery';

let prepareUrl = (url) => {

    url += (url.indexOf('?') ? '?' : '&') + '__=' + (new Date()).getTime();

    return url;
};

export default (url, data, success, error) => {
    $.ajax( prepareUrl(url), {
        dataType: 'json',
        type: 'POST',
        cache: false,
        data: data,
        success: success,
        error: error,
    });
}