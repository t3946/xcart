{set $messages = $.app->flash->read()}

<script type="text/javascript" rel="flash">
    window['flashStack'] = [];

    {foreach $messages as $item}
        window['flashStack'].push({ 'message': {$item['message']|json_encode}, 'type': {$item['type']|json_encode} });
    {/foreach}

    {ignore}
    $(function () {
        var flashOutTime = 5000;

        var $flashList = $('.flash-messages-block .flash-list');

        $(document).on('click', '.close-flash, .flash-message', function (e) {
            e.preventDefault();
            $(this).closest('.flash-message').fadeOut(400, function () {
                $(this).remove();
            });
            return false;
        });

        window.addFlashMessage = function (message, type, time) {
            type = type ? type : 'success';
            var outTime = (time && time > flashOutTime) ? time : flashOutTime;

            var $item = $('<div class="flash-message"></div>').addClass(type);
            var $closer = $('<a class="close-flash right"><i class="icon-delete_in_filter"></i></a>');
            var $text = $('<span/>').addClass('message').text(message);

            $item.append($closer);
            $item.append($text);
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
            for (var i in window['flashStack']) {
                var f = window['flashStack'][i];
                addFlashMessage(f.message, f.type);
            }
        }
    });
    {/ignore}
</script>
<style rel="flash">
.flash-list {
    list-style: none;
    padding: 0;
    margin: 0;
    position: absolute;
    left: 0;
    right: 0;
    z-index: 1;
}
.flash-list .flash-message {
    margin: 1em 0;
    font-size: 16px;
    padding: 10px 15px;
    text-align: center;
    -moz-transform: translate3d(0, -3000px, 0);
    -ms-transform: translate3d(0, -3000px, 0);
    -webkit-transform: translate3d(0, -3000px, 0);
    transform: translate3d(0, -3000px, 0);
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    -webkit-animation: bounceInDown 1s;
    -khtml-animation: bounceInDown 1s;
    -moz-animation: bounceInDown 1s;
    -ms-animation: bounceInDown 1s;
    -o-animation: bounceInDown 1s;
    animation: bounceInDown 1s;
    -webkit-animation-delay: 0.5s;
    -khtml-animation-delay: 0.5s;
    -moz-animation-delay: 0.5s;
    -ms-animation-delay: 0.5s;
    -o-animation-delay: 0.5s;
    animation-delay: 0.5s;
    -webkit-animation-fill-mode: forwards;
    -khtml-animation-fill-mode: forwards;
    -moz-animation-fill-mode: forwards;
    -ms-animation-fill-mode: forwards;
    -o-animation-fill-mode: forwards;
    animation-fill-mode: forwards;
    -webkit-animation-iteration-count: 1;
    -khtml-animation-iteration-count: 1;
    -moz-animation-iteration-count: 1;
    -ms-animation-iteration-count: 1;
    -o-animation-iteration-count: 1;
    animation-iteration-count: 1;
}
.flash-list .flash-message.success {
    background-color: #58af42;
    color: #fff;
}
.flash-list .flash-message.error {
    background-color: #af4e3f;
    color: #fff;
}
.flash-list .flash-message.info {
    background-color: #055a93;
    color: #fff;
}
.flash-list .flash-message .close-flash {
    color: inherit;
}
.flash-list .flash-message .close-flash.right {
    float: right;
}

.flash-messages-block {
    position: relative;
    max-width: 1280px;
    margin: 0 auto;
}

@-moz-keyframes bounceInDown {
    0%, 60%, 75%, 90%, 100% {
        -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -khtml-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -moz-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -ms-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -o-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    }
    0% {
        opacity: 0;
        -moz-transform: translate3d(0, -3000px, 0);
        transform: translate3d(0, -3000px, 0);
    }
    60% {
        opacity: 1;
        -moz-transform: translate3d(0, 25px, 0);
        transform: translate3d(0, 25px, 0);
    }
    75% {
        -moz-transform: translate3d(0, -10px, 0);
        transform: translate3d(0, -10px, 0);
    }
    90% {
        -moz-transform: translate3d(0, 5px, 0);
        transform: translate3d(0, 5px, 0);
    }
    100% {
        -moz-transform: none;
        transform: none;
    }
}
@-webkit-keyframes bounceInDown {
    0%, 60%, 75%, 90%, 100% {
        -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -khtml-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -moz-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -ms-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -o-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    }
    0% {
        opacity: 0;
        -webkit-transform: translate3d(0, -3000px, 0);
        transform: translate3d(0, -3000px, 0);
    }
    60% {
        opacity: 1;
        -webkit-transform: translate3d(0, 25px, 0);
        transform: translate3d(0, 25px, 0);
    }
    75% {
        -webkit-transform: translate3d(0, -10px, 0);
        transform: translate3d(0, -10px, 0);
    }
    90% {
        -webkit-transform: translate3d(0, 5px, 0);
        transform: translate3d(0, 5px, 0);
    }
    100% {
        -webkit-transform: none;
        transform: none;
    }
}
@keyframes bounceInDown {
    0%, 60%, 75%, 90%, 100% {
        -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -khtml-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -moz-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -ms-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        -o-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    }
    0% {
        opacity: 0;
        -moz-transform: translate3d(0, -3000px, 0);
        -ms-transform: translate3d(0, -3000px, 0);
        -webkit-transform: translate3d(0, -3000px, 0);
        transform: translate3d(0, -3000px, 0);
    }
    60% {
        opacity: 1;
        -moz-transform: translate3d(0, 25px, 0);
        -ms-transform: translate3d(0, 25px, 0);
        -webkit-transform: translate3d(0, 25px, 0);
        transform: translate3d(0, 25px, 0);
    }
    75% {
        -moz-transform: translate3d(0, -10px, 0);
        -ms-transform: translate3d(0, -10px, 0);
        -webkit-transform: translate3d(0, -10px, 0);
        transform: translate3d(0, -10px, 0);
    }
    90% {
        -moz-transform: translate3d(0, 5px, 0);
        -ms-transform: translate3d(0, 5px, 0);
        -webkit-transform: translate3d(0, 5px, 0);
        transform: translate3d(0, 5px, 0);
    }
    100% {
        -moz-transform: none;
        -ms-transform: none;
        -webkit-transform: none;
        transform: none;
    }
}
</style>

<div class="flash-messages-block">
    <div class="flash-list"></div>
</div>