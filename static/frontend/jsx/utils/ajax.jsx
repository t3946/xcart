import $ from 'jquery';

export default (url, data, success, error) => {
    $.ajax( url, {
        dataType: 'json',
        type: 'POST',
        cache: false,
        data: data,
        success: success,
        error: error,
    });
}