{render_static_notifications}

<script type="text/javascript" rel="static-messages">
    $(function(){
        var $block = $('.static-messages-block');

        if ($block.length) {
            var setCookie = function(name, value) {
                var date = new Date();

                date.setFullYear(date.getFullYear()+1);
                document.cookie = name+"="+JSON.stringify(value)+"; expires="+date.toUTCString() + "; path=/";
            };

            var getCookie = function(name, defaults) {
                if (typeof defaults == typeof undefined) {
                    defaults = false;
                }

                if (document.cookie.length > 0) {
                    var start, end;

                    start = document.cookie.indexOf(name + "=");

                    if (start != - 1) {
                        start = start + name.length + 1;
                        end = document.cookie.indexOf(";", start);

                        if (end == - 1) {
                            end = document.cookie.length;
                        }

                        var str = document.cookie.substring(start, end);

                        if (str) {
                            return JSON.parse(decodeURI(str));
                        }
                    }
                }

                return defaults;
            };

            var deleteCookie = function(name) {
                document.cookie = name+"=0; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
            };

            $block.find('a.close').on('click', function(e){
                e.preventDefault();

                var idx = getCookie('notification_hide_idx', []);


                idx.push(this.dataset.id);
                setCookie('notification_hide_idx', idx);

                $(this).closest('.message').remove();
            });
        }
    });
</script>
<style rel="static-messages">
    .static-messages-block {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
    }
    .static-messages-block .message {
        max-width: 940px;
        min-width: 320px;
        margin-left: auto;
        margin-right: auto;
        padding: 1em;
        padding-right: 3em;
        position: relative;
        box-sizing: border-box;
    }

    .static-messages-block .messages-list .message-block {
        position: relative;
    }

    .static-messages-block .message,
    .static-messages-block .message *,
    .static-messages-block .message .description *{
        color: inherit !important;
    }

    .static-messages-block .message .title {
        font-size: 1.5em;
        font-weight: 700;
        padding-bottom: .5em;
    }

    .static-messages-block .messages-list .close {
        position: absolute;
        display: block;
        width: 1em;
        height: 1em;
        right: .5em;
        top: 50%;
        margin-top: -.5em;
        text-decoration: none;
        color: inherit !important;
    }

    .static-messages-block .message > * {
        font-size: 1.17em;
    }

    .ui-mobile-viewport .static-messages-block .message > * {
        font-size: 1.5em;
        font-family: Helvetica, Arial, sans-serif ;
        text-shadow: none;
    }

    .ui-mobile-viewport .static-messages-block .message {
        max-width: 1280px;
    }

</style>
