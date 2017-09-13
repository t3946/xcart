import $ from 'jquery';

let prepareUrl = (url) => {

    url += (url.indexOf('?') ? '?' : '&') + '__=' + (new Date()).getTime();

    return url;
};

export default (url, data, success, error) => {
    return $.ajax( prepareUrl(url), {
        dataType: 'json',
        type: 'POST',
        cache: false,
        data: data,
        success: success,
        error: error,
    })
    .promise();
}