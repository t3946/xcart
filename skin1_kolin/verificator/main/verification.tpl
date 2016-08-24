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
        var sOriginalProduct =  {/literal}{$oVerificationBatch->getOriginalLinksJson()} {literal};

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
        function isShowOriginal() {
            var slocation = location.href,
                    str_param = slocation.match(/show_original=([^&]+)/),
                    id = 0;
            if (str_param != null && str_param.length > 0)
                id = str_param[1];
            return (id);
        }
        function isSplitScreen() {
            var slocation = location.href,
                    str_param = slocation.match(/split_screen=([^&]+)/),
                    id = 0;
            if (str_param != null && str_param.length > 0)
                id = str_param[1];
            return (id);
        }

        function iframeLoaded(ASIN) {
            if (ASIN != '') {
                $('.buttons-wrap-right').fadeIn();
                $('#action_buttons_block').attr('data-asin', ASIN);
            } else {
                $('.buttons-wrap-right').fadeOut();
                $('#action_buttons_block').attr('data-asin', '');
            }

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
                    $('#original_product').removeClass('active').blur();
                }
            });
            $('#original_product').on('click', '', function () {
                var sstep = '',
                idx = getLocationId(),
                check = $('#split-screen-checkbox').prop("checked");
                if (idx > 0) {
                    sstep = '&step=' + idx;
                }

                $(this).toggleClass('active');
                if ($(this).hasClass('active')) {
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + sstep + '&show_original=1');
                    if (!check) {
                        $('.iframediv.left').css('width', '0');
                        $('.iframediv.right').css('width', '100%');
                    }
                } else {
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + sstep );
                    if (!check) {
                        $('.iframediv.left').css('width', '100%');
                        $('.iframediv.right').css('width', '0');
                    }
                    $(this).blur();
                }
            });

            $('#split-screen-checkbox').on('change', '', function () {
                var check = $(this).prop("checked"),
                sstep = '',
                idx = getLocationId();
                if (idx > 0) {
                    sstep = '&step=' + idx;
                }
                if (check) {
                    $('.iframediv.right').css('width', '50%');
                    $('.iframediv.left').css('width', '50%');
                    $('#original_product').removeClass('active').blur().prop('disabled', true);
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + sstep + '&split_screen=1' );
                } else {
                    $('.iframediv.left').css('width', '100%');
                    $('.iframediv.right').css('width', '0');
                    $('#original_product').prop('disabled', false);
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + sstep);
                }
            });

            if (trans.length > 0) {
               $('.buttons-wrap-left button[data-step-id=' + getLocationId() + ']').click();
                if (isShowOriginal() == 1) {
                    $('#original_product').click();
                }
                else if (isSplitScreen() == 1) {
                    $('#split-screen-checkbox').prop( "checked", true ).change();
                }
            } else {
                $('#no_products').show();
            }

            $('#submit-product-match, #submit-product-not-sure, #submit-product-not-match').on('click', '', function () {
                var active_button_action = $(this).data('action'),
                    active_batch_id = $(this).parent().data('batch-id'),
                    active_product_id = $(this).parent().data('product-id'),
                    active_asin = $(this).parent().data('asin');
                $('.small.modal').modal({
                    onApprove: function () {
                        $('.buttons-wrap-right').fadeOut();
                        $.post('ajax_admin.php',{
                                    verify_status_id : active_button_action,
                                    batch_id: active_batch_id,
                                    product_id: active_product_id,
                                    note_text: $('#verify_comment').val(),
                                    asin: active_asin,
                                    ajax_action: 'change_verify_product_status'
                                },
                                function (data) {
                                    $('#verify_comment').val('');
                                    var split = isSplitScreen();
                                    var ssplit = '';
                                    if (split) {
                                        ssplit = '&split_screen=1';
                                    }
                                    location.href = spathName+'?batch=' + getBatchId()+ssplit;
                                }, 'json');
                    }
                }).modal('show');
            });

            loadOriginalProduct('ifrm2');

        });

        window.addEventListener("popstate", function () {
            $('.buttons-wrap-left button[data-step-id=' + getLocationId() + ']').click();
        });

        function loadOriginalProduct(frame_Name){
            if (sOriginalProduct.length > 0) {
                var i_frame = document.getElementById(frame_Name);
                if ($(i_frame).length) {
                    var frame = i_frame.cloneNode(false);
                    frame.src = sOriginalProduct;
                    i_frame.parentNode.replaceChild(frame, i_frame);
                } else {
                    $('<iframe>', {
                        src: sOriginalProduct,
                        id: frame_Name,
                        frameborder: 0
                    }).appendTo('.iframediv.right');
                }
            }
        }

        function loadRemoteFrame(frame_Name, url, idx_force = false, appendto='.iframediv.left') {
            var i_frame = document.getElementById(frame_Name),
                    len = trans.length,
                    idx = idx_force;
            var split = isSplitScreen();
            var ssplit = '';
            if (split) {
                ssplit = '&split_screen=1';
            }

            $('.ui.segment.dimmable').dimmer('show');
            setTimeout(function () {
                $('.ui.segment.dimmable').dimmer('hide')
            }, 3000);

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
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + '&step=' + idx + ssplit);
                }
                if (idx == 0) {
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + ssplit);
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
        <div class="wrap-left">
            <span style="line-height: 50px; margin-right: 15px; font-size: 18px;  position: relative; top: -4px;">Compare</span>
            <div class="three ui buttons">

                <button data-step-id="0" id="search_amazon_by_asin" class="ui left button attached active">Open product by
                    ASIN
                </button>
                <button data-step-id="1" id="search_amazon_by_upc" class="ui attached button">Search product by UPC</button>
                <button data-step-id="2" id="search_amazon_by_name" class="ui right attached button">Search product by
                    Product Name
                </button>
                <span style="font-size: 18px; margin-left: 25px; margin-top:14px;">AND</span>
                <span style="margin-left: 25px;">
                <button data-step-id="3" id="original_product" class="ui left attached button">Original product</button>
                </span>
            </div>
        </div>
        <div style="float: right; bottom: 48px; right: -70px;" class="form ui field">
            <div class="ui slider checkbox" style="margin-top: 10px; float:left; width: 138px; font-size: 14px;">
                <input autocomplete="off" name="newsletter" id="split-screen-checkbox" type="checkbox">
                <label>Split screen</label>
            </div>
        </div>
    </div>
    <div class="buttons-wrap-right" style="display:none;">
        <div class="buttons-right">
            <div id="action_buttons_block" class="ui buttons select" data-asin="" data-batch-id="{$oVerificationBatch->getBatchId()}" data-product-id="{$oVerificationBatch->getVerifiedProductId()}">
                <button data-action="match" id="submit-product-match" class="ui left positive button">Product match</button>
                <div class="or" data-text="or"></div>
                <button data-action="not_sure" id="submit-product-not-sure" class="ui yellow button">Not sure</button>
                <div class="or" data-text="or"></div>
                <button data-action="not_match" id="submit-product-not-match" class="ui negative button">Does not match</button>
            </div>

        </div>
    </div>
</header>
<div class="ui inverted dimmer transition hidden segment dimmable">
    <div class="ui text loader">Loading...</div>
</div>
<div id="no_products">
    Product for verification not found
</div>
<div class="iframediv left">
</div>
<div class="iframediv right">
</div>

<div class="ui small test modal transition">
    <div class="header">
        Comments
    </div>
    <div class="content">
        <div class="ui form">
            <h4 class="ui dividing header">Write your comments (if you have any)</h4>

            <div class="field">
                <textarea autocomplite="off" id="verify_comment"></textarea>
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