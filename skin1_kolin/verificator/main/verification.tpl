<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">
    <link rel="stylesheet" href="{$SkinDir}/verificator/css/main.css"/>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/dimmer.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/modal.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/transition.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/progress.min.js" type="text/javascript"></script>
    <script src="{$SkinDir}/js/semantic/components/popup.min.js" type="text/javascript"></script>

    {literal}
    <script type="text/javascript">
        // <![CDATA[
        var trans =  {/literal}{$oVerificationBatch->getSearchLinksJson()} {literal};
        var sOriginalProduct =  {/literal}{$oVerificationBatch->getOriginalLinksJson()} {literal};

        idx = 0;
        firstLoad = true;

        function getFirstAvailStep() {
            for (var prop in trans)
                return prop;
        }
        function initButtons() {
            var i = 0;
            for (var prop in trans) {
                i++;
                var b = $('#search_amazon_by_asin, #search_amazon_by_upc, #search_amazon_by_name').filter('[data-step-id=' + prop + ']').removeClass('active');
                if (i == 1) b.addClass('left');
                b.show();

            }
        }

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
                $('#product_not_found_button').hide();
                $('#conclusion_buttons').fadeIn();
                $('#action_buttons_block').attr('data-asin', ASIN);
            } else {
                $('#conclusion_buttons').hide();
                $('#product_not_found_button').fadeIn();
                $('#action_buttons_block').attr('data-asin', '');
            }

            $('.ui.segment.dimmable').dimmer('hide');
        }

        function clickOriginalProduct(button) {

            button.toggleClass('active');
            var check = $('#split-screen-checkbox').prop("checked");
            if (button.hasClass('active')) {
                if (!check) {
                    $('.iframediv.left').css('width', '0');
                    $('.iframediv.right').css('width', '100%');
                }
            } else {
                if (!check) {
                    $('.iframediv.left').css('width', '100%');
                    $('.iframediv.right').css('width', '0');
                }
                button.blur();
            }
        }

        function changeSplitScreen(split){
            var check = split.prop("checked"),
                    sstep = '',
                    idx = getLocationId();
            if (idx > 0) {
                sstep = '&step=' + idx;
            }
            if (check) {
                $('.iframediv.right').css('width', '50%');
                $('.iframediv.left').css('width', '50%');
                $('#original_product').removeClass('active').blur().prop('disabled', true);
            } else {
                $('.iframediv.left').css('width', '100%');
                $('.iframediv.right').css('width', '0');
                $('#original_product').prop('disabled', false);
            }
        }

        var spathName = location.pathname;

        $(document).ready(function () {
            $('#search_amazon_by_asin, #search_amazon_by_upc, #search_amazon_by_name').on('click', '', function () {
                if (!$(this).hasClass('active')) {
                    $(this).addClass('active').siblings().removeClass('active').blur();
                    $('#conclusion_buttons').fadeOut();

                    var currstep = $(this).data('step-id');

                    loadRemoteFrame('ifrm', false, currstep);
                    var check = $('#split-screen-checkbox').prop("checked");
                    if (!check) {
                        $('.iframediv.left').css('width', '100%');
                        $('.iframediv.right').css('width', '0');
                        $('#original_product').removeClass('active').blur();
                    }
                }
            });

            $('#make_conclusion_button').popup({on: 'click'}).click(function(){return false;});

            $('#original_product').on('click', '', function () {
                clickOriginalProduct($(this));
                var sstep = '',
                    idx = getLocationId();

                if (idx > 0) {
                    sstep = '&step=' + idx;
                }
                if ($(this).hasClass('active')) {
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + sstep + '&show_original=1');
                } else {
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + sstep);
                }
            });

            $('#split-screen-checkbox').on('change', '', function () {
                changeSplitScreen($(this));
                var check = $(this).prop("checked"),
                        sstep = '',
                        idx = getLocationId();
                if (idx > 0) {
                    sstep = '&step=' + idx;
                }
                if (check) {
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + sstep + '&split_screen=1');
                } else {
                    history.pushState(null, null, spathName + '?batch=' + getBatchId() + sstep);
                }
            });

            $('#negative_conclusion').on('click','',function(){
                $('.small.modal').modal('hide').parent().removeClass('active');
            });

            initButtons();
            $('.indicating.progress').progress();
            if (trans) {
                if (trans.length == 0) {
                    $('#no_products').show();
                } else {
                    $('.buttons-wrap-left button[data-step-id=' + Math.max(getLocationId(), getFirstAvailStep()) + ']').click();
                    if (isShowOriginal() == 1) {
                        clickOriginalProduct($('#original_product'));
                    }
                    else if (isSplitScreen() == 1) {
                        $('#split-screen-checkbox').prop("checked", true);
                        changeSplitScreen($('#split-screen-checkbox'));
                    }
                }
            }

            $('#submit-product-match, #submit-product-not-sure, #submit-product-not-match, #submit-product-not-found').on('click', '', function () {
                var active_button_action = $(this).data('action'),
                        active_batch_id = $(this).parent().data('batch-id'),
                        active_product_id = $(this).parent().data('product-id'),
                        active_asin = $(this).parent().data('asin');
                $('#conclusion_title').text($(this).text()).removeClass('positive yellow negative').addClass($(this).data('class'));
                $('.small.modal').modal('hide').modal({
                    onApprove: function () {
                        $('#conclusion_buttons').fadeOut();
                        $.post('ajax_admin.php', {
                                    verify_status_id: active_button_action,
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
                                    location.href = spathName + '?batch=' + getBatchId() + ssplit;
                                }, 'json');
                    },
                    onDeny: function() {
                        $(this).modal('hide').parent().removeClass('active');
                    }
                }).modal('show');
            });

            loadOriginalProduct('ifrm2');

        });

        window.addEventListener("popstate", function () {
            initButtons();
            firstLoad = true;
            if (isShowOriginal() == 1) {
                clickOriginalProduct($('#original_product'));
            }
            else if (isSplitScreen() == 1) {
                changeSplitScreen($('#split-screen-checkbox'));
            }
            else {
                $('.buttons-wrap-left button[data-step-id=' + getLocationId() + ']').click();
            }
        });

        function loadOriginalProduct(frame_Name) {
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

        function loadRemoteFrame(frame_Name, url, idx_force, appendto) {

            if (!(appendto)) appendto = '.iframediv.left';
            var i_frame = document.getElementById(frame_Name),
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
            if (!firstLoad) {
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
<body class="verifiaction">
<header id="header">
    <div class="buttons-wrap-left">
        <div class="form ui field">
            <div class="ui slider checkbox">
                <input autocomplete="off" name="newsletter" id="split-screen-checkbox" type="checkbox">
                <label>Split screen</label>
            </div>
        </div>
        <div class="wrap-left">
            <div class="three ui buttons">
                <span style="font-family:Verdana; line-height: 50px; margin-right: 15px; font-size: 18px;  position: relative; ">Compare</span>
                <button data-step-id="0" id="search_amazon_by_asin" class="ui button attached">Open product by ASIN
                </button>
                <button data-step-id="1" id="search_amazon_by_upc" class="ui attached button">Search product by UPC
                </button>
                <button data-step-id="2" id="search_amazon_by_name" class="ui right attached button">Search product by
                    Product Name
                </button>
                <span style="font-size: 18px; margin-left: 25px; margin-top:14px; font-family:Verdana;">AND</span>
                <span style="margin-left: 25px;">
                <button data-step-id="3" id="original_product" class="ui left attached button">Original product</button>
                </span>
            </div>
        </div>
    </div>
    <div class="buttons-wrap-right">
        <div class="progress-wrap">
            <div class="label">Batch progress</div>
            <div id="progress-indicator" class="ui indicating progress"
                 data-value="{$oVerificationBatch->getProductsInBatchCompletedCount()}"
                 data-total="{$oVerificationBatch->getBatchAmount()}">

                <div class="bar">
                    <div class="progress"></div>
                </div>
                <div class="label">{$oVerificationBatch->getProductsInBatchCompletedCount()}
                    /{$oVerificationBatch->getBatchAmount()}</div>
            </div>
        </div>
        <div class="buttons-right">
            <div id="conclusion_buttons" style="display:none;">
                <a data-html="{$config.Amazon_Verification.amazon_verification_make_conclusion_popup_message}" id="make_conclusion_button" href="#" style="border-bottom: 1px dotted; color: #0000ff; text-decoration: none; font-family:Verdana; line-height: 50px; margin-right: 15px; font-size: 18px;  position: relative; max-width: 475px;">Make a conclusion</a>

                <div id="action_buttons_block" class="ui buttons select" data-asin=""
                     data-batch-id="{$oVerificationBatch->getBatchId()}"
                     data-product-id="{$oVerificationBatch->getVerifiedProductId()}">
                    <button data-action="match" data-class="positive" id="submit-product-match" class="ui left positive button">Product
                        match
                    </button>
                    <div class="or" data-text="or"></div>
                    <button data-action="not_sure" data-class="yellow" id="submit-product-not-sure" class="ui yellow button">Not sure
                    </button>
                    <div class="or" data-text="or"></div>
                    <button data-action="not_match" data-class="negative" id="submit-product-not-match" class="ui negative button">Does NOT
                        match
                    </button>
                </div>
            </div>
            <div data-asin="" data-batch-id="{$oVerificationBatch->getBatchId()}"
                 data-product-id="{$oVerificationBatch->getVerifiedProductId()}" class="ui buttons select"
                 id="product_not_found_button">
                <button data-action="not_found" data-class="negative" id="submit-product-not-found" class="ui left negative button">Product not found
                </button>
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
        Conclusion: <button style="position: relative; bottom: 2px;" id="conclusion_title" class="ui positive button">Product match </button>
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
        <a id="negative_conclusion">Cancel</a>
        <div class="ui positive right labeled icon button">
            Submit
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>
</body>
</html>