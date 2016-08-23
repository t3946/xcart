<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">
    <link rel="stylesheet" href="{$SkinDir}/verificator/css/main.css"/>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/dimmer.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/modal.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/transition.min.js" type="text/javascript"></script>
    {literal}
    <script type="text/javascript">
        // <![CDATA[
        var trans =  {/literal}{$oVerificationBatch->getSearchLinksJson()} {literal};

        idx = 0;
        firstLoad = true;

        function getLocationId() {
            var slocation = location.href,
                    str_param = slocation.match(/step=([^&]+)/),
                    id = 0;
            if (str_param != null && str_param.length > 0)
                id = str_param[1];
            return (id);
        }
        function getBatchId() {
            var slocation = location.href,
                    str_param = slocation.match(/batch=([^&]+)/);
            if (str_param != null && str_param.length > 0)
                id = str_param[1];
            return id;
        }

        function iframeLoaded(ASIN) {
            if (ASIN != '') {
                $('.buttons-wrap-right').fadeIn();
            } else
                $('.buttons-wrap-right').fadeOut();

            $('.ui.segment.dimmable').dimmer('hide');
        }

        var spathName = location.pathname;

        $(document).ready(function () {
            $('#search_amazon_by_asin, #search_amazon_by_upc, #search_amazon_by_name').on('click', '', function () {
                $(this).addClass('active').siblings().not('#original_product').removeClass('active');
                $('.buttons-wrap-right').fadeOut();
                loadRemoteFrame('ifrm', false, $(this).data('step-id'));
                var check = $('#split-screen-checkbox').prop("checked");
                if (!check) {
                    $('.iframediv.left').css('width', '100%');
                    $('.iframediv.right').css('width', '0');
                    $('#original_product').removeClass('active');
                }
            });
            $('#original_product').on('click', '', function () {
                $(this).toggleClass('active');
                if ($(this).hasClass('active')) {
                    loadRemoteFrame('ifrm2', false, $(this).data('step-id'), '.iframediv.right');
                    var check = $('#split-screen-checkbox').prop("checked");
                    if (!check) {
                        $('.iframediv.left').css('width', '0');
                        $('.iframediv.right').css('width', '100%');
                        $('#search_amazon_by_asin, #search_amazon_by_upc, #search_amazon_by_name').removeClass('active');
                    }
                }
            });

            $('#split-screen-checkbox').on('change', '', function () {
                var check = $(this).prop( "checked");
                if (check) {
                    var opb = $('#original_product');
                    if (!opb.hasClass('active')) {
                        opb.click();
                    }
                    $('.iframediv.right').css('width','50%');
                    $('.iframediv.left').css('width','50%');
                } else {
                    $('.iframediv.left').css('width','100%');
                    $('.iframediv.right').css('width','0');
                }
            });

            $('.buttons-wrap-left button[data-step-id=' + getLocationId() + ']').click();

            $('#submit-prouct-match, #submit-prouct-not-sure, #submit-prouct-not-match').on('click', '', function () {
                $('.small.modal').modal('show');
            });

        });

        window.addEventListener("popstate", function () {
            $('.buttons-wrap-left button[data-step-id=' + getLocationId() + ']').click();
        });

        function loadRemoteFrame(frame_Name, url, idx_force = false, appendto='.iframediv.left') {
            var i_frame = document.getElementById(frame_Name),
                    len = trans.length,
                    idx = idx_force;


            $('.ui.segment.dimmable').dimmer('show');
            setTimeout(function(){$('.ui.segment.dimmable').dimmer('hide')}, 3000);

            if ($(i_frame).length) {

                var frame = i_frame.cloneNode(false);
                frame.src = trans[idx][0];
                i_frame.parentNode.replaceChild(frame, i_frame);


            } else {
                $('<iframe>', {
                    src: trans[idx][0],
                    id: frame_Name,
                    frameborder: 0
                }).appendTo(appendto);
            }
            if (!url && !firstLoad) {
                if (idx > 0) {
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + '&step=' + idx);
                }
                if (idx == 0) {
                    history.pushState(null, null, spathName + '?batch=' + getBatchId());
                }
            }
            firstLoad = false;

            document.title = trans[idx][1];

            return false;
        }
        // ]]>
    </script>
    {/literal}
</head>
<body>
<header id="header">
    <div class="buttons-wrap-left">
        <div class="three ui buttons">
            <button data-step-id="0" id="search_amazon_by_asin" class="ui left button attached active">Open product by
                ASIN
            </button>
            <button data-step-id="1" id="search_amazon_by_upc" class="ui attached button">Search product by UPC</button>
            <button data-step-id="2" id="search_amazon_by_name" class="ui right attached button">Search product by
                Product Name
            </button>
            <div style="margin-left:50px;" class="form ui field">
                <button data-step-id="3" id="original_product" class="ui left attached button">Original product</button>
                <div class="ui slider checkbox" style="margin-top: 10px; float:left; width: 138px; font-size: 14px;">
                    <input autocomplete="off" name="newsletter" id="split-screen-checkbox" type="checkbox">
                    <label>Split screen</label>
                </div>
            </div>
        </div>
    </div>
    <div class="buttons-wrap-right" style="display:none;">
        <div class="buttons-right">
            <div class="ui buttons select">
                <button id="submit-prouct-match" class="ui left positive button">Product match</button>
                <div class="or" data-text="or"></div>
                <button id="submit-prouct-not-sure" class="ui yellow button">Not sure</button>
                <div class="or" data-text="or"></div>
                <button id="submit-prouct-not-match" class="ui negative button">Not match</button>
            </div>

        </div>
    </div>
</header>
<div class="ui inverted dimmer transition hidden segment dimmable">
    <div class="ui text loader">Loading...</div>
</div>
<div class="iframediv left">
</div>
<div class="iframediv right">
</div>
<div class="ui small test modal transition">
    <div class="header">
        Confirm your choice
    </div>
    <div class="content">
        <div class="ui form">
            <h4 class="ui dividing header">Comments</h4>

            <div class="field">
                <textarea placeholder="Comments if product is not matched"></textarea>
            </div>
        </div>
    </div>
    <div class="actions">
        <div class="ui negative button">
            Cancel
        </div>
        <div class="ui positive right labeled icon button">
            Submit
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>
</body>
</html>