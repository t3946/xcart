let prepareUrl = url => (url += (url.indexOf('?') ? '?' : '&') + '__=' + (new Date()).getTime());
let isJsonResponse = (response, isJson = false) => (isJson || response.headers.get('Content-Type').toLowerCase() === 'application/json');

export default (url, data, success, error) => {
    let isJson = false;
    let options = {
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, same-origin, *omit
        headers: {
            'X-REQUESTED-WITH': 'XMLHttpRequest'
        },
        method: 'GET', // *GET, POST, PUT, DELETE, etc.
        mode: 'same-origin', // no-cors, cors, *same-origin
        redirect: 'follow', // manual, *follow, error
        referrer: 'no-referrer', // *client, no-referrer
    };

    if (data) {
        if (data.type) {
            isJson = data.type.toLowerCase() === 'json';
        }

        if (data.data) {
            options['body'] = data.data;
        }

        if (data.forceNoCache) {
            url = prepareUrl(url);
        }

        options['method'] = (data.method) ? data.method.toUpperCase() : 'GET';
        options['mode'] = (data.mode) ? data.method.toLowerCase() : 'same-origin';
    }

    return fetch(url, options)
        .then(
            response => {
                let pr = isJsonResponse(response, isJson)
                    ? response.json()
                    : response.text();

                pr.then(success, error);

                return pr;
            },
            response => error(response)
        )
}