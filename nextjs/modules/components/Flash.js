(()=> {
    let flashOutTime = 7000;

    let $flashList = $('.flash-messages-block .flash-list');

    $(document).on('click', '.close-flash', function (e) {
        e.preventDefault();
        $(this).closest('.flash-message').fadeOut(400, function () {
            $(this).remove();
        });
        return false;
    });

    window.addFlashMessage = (message, type, html, time) => {
        type = type ||'success';
        html = html || false;
        let outTime = (time && time > flashOutTime) ? time : flashOutTime;

        let $item = $('<div class="flash-message"></div>').addClass(type);
        let $closer = $('<a class="close-flash right"><i class="icon-delete_in_filter"></i></a>');
        let $text;
        if(!html) {
            $text = $('<span/>').addClass('message').text(message);
        } else {
            $text = $('<span/>').addClass('message').html(message);
        }


        $item.append([$closer, $text]);
        $flashList.append($item);

        setTimeout(function () {
            if ($item && $item.length > 0) {
                $item.fadeOut(400, function () {
                    $(this).remove();
                });
            }
        }, outTime);
    };

    if (window['flashStack'] && window['flashStack'].length) {
        for (let i in window['flashStack']) {
            let f = window['flashStack'][i];
            addFlashMessage(f.message, f.type);
        }
    }
})();