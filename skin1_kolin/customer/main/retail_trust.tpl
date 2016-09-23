<link href="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.css" type="text/css"
      rel="stylesheet"/>
<script src="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.js" type="text/javascript"
        charset="utf-8"></script>
<link rel="stylesheet" href="{$SkinDir}/css/popup.css"/>
<div style='display:none;'>
    <div id='retail_trust_message' style='background:#fff;'>
        {include file='popups/retail_trust/retail_trust_popup_1.tpl'}
    </div>
</div>
<div style='display:none;'>
    <div id='retail_trust_message_after_close' style='background:#fff;'>
        {include file='popups/retail_trust/retail_trust_popup_2.tpl'}
    </div>
</div>
<script>
    {literal}
    $(document).ready(function () {

        setTimeout(function () {
            $.featherlight('#retail_trust_message', {
                closeIcon: null,
                otherClose: "a#close, a.thanks",
                persist: true
            })
        }, 2000);
        $('.green_btn').on ('click', '', function () {
            var current = $.featherlight.current();
            current.close();
            $.post('ajax.php',{
                        params: $('#retail_form').serializeArray(),
                        ajax_action: 'add_retail_trust'
                    },
                    function (data) {
                        if (data && data.retail_price !='') {
                            $('#retail_price_value').html(data.retail_price);
                            $.featherlight('#retail_trust_message_after_close', {
                                otherClose: "a#close",
                                closeIcon: null,
                                afterClose: function () {
                                }
                            })
                        }
                    }, 'json');

        });
    });
    {/literal}
</script>