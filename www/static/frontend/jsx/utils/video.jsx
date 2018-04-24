export function findVideoId (link, forceVideo)
{
    if (typeof link !== 'string') return link;
    let href = parseHref(link);

    let id, type;

    if (href.host.match(/youtube\.com/) && href.search) {
        id = href.search.split('v=')[1];

        if (id) {
            var ampersandPosition = id.indexOf('&');
            if (ampersandPosition !== -1) {
                id = id.substring(0, ampersandPosition);
            }
            type = 'youtube';
        }
    }
    else if (href.host.match(/youtube\.com|youtu\.be/)) {
        id = href.pathname.replace(/^\/(embed\/|v\/)?/, '').replace(/\/.*/, '');
        type = 'youtube';
    }
    else if (href.host.match(/vimeo\.com/)) {
        id = href.pathname.replace(/^\/(video\/)?/, '').replace(/\/.*/, '');
        type = 'vimeo';
    }

    if ((!id || !type) && forceVideo) {
        id = href.href;
        type = 'custom';
    }

    return id ? {id: id, type: type, href: link, s: href.search.replace(/^\?/, ''), p: getProtocol()} : false;
}

export function getVideoThumbs (videoFrame, callback)
{
    let img, thumb;

    if (videoFrame.type === 'youtube') {
        let base_uri = getProtocol() + 'img.youtube.com/vi/' + videoFrame.id;

        thumb = base_uri + '/default.jpg';
        img = base_uri + '/hqdefault.jpg';
    }
    else if (videoFrame.type === 'vimeo') {
        $.ajax({
            url: getProtocol() + 'vimeo.com/api/v2/video/' + videoFrame.id + '.json',
            dataType: 'jsonp',
            success: function (json) {
                videoFrame.images = {
                    img: json[0].thumbnail_large,
                    thumb: json[0].thumbnail_small
                };

                callback(videoFrame);
            }
        });
    }

    videoFrame.images = {
        img: img,
        thumb: thumb
    };

    callback(videoFrame);
}

export function getProtocol () {
    getProtocol.p = getProtocol.p || (location.protocol === 'https:' ? 'https://' : 'http://');
    return getProtocol.p;
}

export function parseHref (href) {
    var a = document.createElement('a');
    a.href = href;
    return a;
}

export function videoLinkToObject(href, callback)
{
    let frame = findVideoId(href, true);

    if (frame) {
        getVideoThumbs(frame,  callback);

        return true;
    }

    return false;
}