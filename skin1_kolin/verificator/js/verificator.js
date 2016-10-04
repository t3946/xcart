idx = 0;
firstLoad = true;
var spathName = location.pathname;

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
        $('.conclusion_submit_button').attr('data-asin', ASIN);
    } else {
        $('#conclusion_buttons').hide();
        $('#product_not_found_button').fadeIn();
        $('.conclusion_submit_button').attr('data-asin', '');
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

function changeSplitScreen(split) {
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

function submitConclusion(verify_status_id, batch_id, product_id, note_text, asin, conclusion_buttons) {
    $.post('ajax.php', {
            verify_status_id: verify_status_id,
            batch_id: batch_id,
            product_id: product_id,
            note_text: note_text,
            asin: asin,
            ajax_action: 'change_verify_product_status',
            conclusion_buttons: conclusion_buttons
        },
        function (data) {
            var ssplit = '';
            $('#verify_comment').val('');
            if (isSplitScreen()) {
                ssplit = '&split_screen=1';
            }
            location.href = spathName + '?batch=' + getBatchId() + ssplit;
        }, 'json');
}

function submitConclusionWithComment(verify_status_id, batch_id, product_id, asin, conclusion_buttons) {
    $('.small.modal').modal('hide').modal({
        onApprove: function () {
            $('#conclusion_buttons').fadeOut();
            submitConclusion(verify_status_id, batch_id, product_id, $('#verify_comment').val(), asin, conclusion_buttons);
        },
        onDeny: function () {
            obj.modal('hide').parent().removeClass('active');
        }
    }).modal('show');
}

function getFormValues(id) {
    var $inputs = $(id).find('input');
    var values = {};
    $inputs.each(function () {
        values[this.name] = $(this).val();
    });
    return values;
}

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

$(document).ready(function () {
    initButtons();
    loadOriginalProduct('ifrm2');

    $('#search_amazon_by_asin, #search_amazon_by_upc, #search_amazon_by_name').click(function () {
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

    $('.popup_drop_link').popup({on: 'click'}).click(function () {
        return false;
    });

    $('#original_product').click(function () {
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

    $('#split-screen-checkbox').change(function () {
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

    $('#negative_conclusion').click(function () {
        $('.small.modal').modal('hide').parent().removeClass('active');
    });


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

    $('#submit-product-not-found').click(function () {
        var obj = $(this),
            conclusion_buttons = getFormValues('#conclusion_buttons'),
            active_asin = obj.parent().data('asin'),
            active_button_action = obj.data('action'),
            active_batch_id = obj.parent().data('batch-id'),
            active_product_id = obj.parent().data('product-id');
            submitConclusionWithComment(active_button_action, active_batch_id, active_product_id, active_asin, conclusion_buttons);
    });

    $('#conclusion_buttons').find('input[type=radio]').change(function () {
        $(this).closest('div.step').addClass('completed');
        var completed_steps = $(this).closest('#conclusion_buttons').find('.step.completed');
        if (completed_steps.length == 3) {
            $('#conclusion_buttons').find('.submit-amazon-button').removeClass('disabled').siblings().removeClass('disabled');
        }
    }).end().find('.submit-amazon-button').on('click', '', function () {
        var obj = $('#submit-product-not-found'),
            conclusion_buttons = getFormValues('#conclusion_buttons'),
            active_asin = $(this).closest('.conclusion_submit_button').data('asin'),
            active_button_action = 'submit',
            active_batch_id = obj.parent().data('batch-id'),
            active_product_id = obj.parent().data('product-id');
        switch ($(this).data('action')) {
            case 'submit':
                $(this).addClass('loading disabled').siblings().addClass('disabled');
                submitConclusion(active_button_action, active_batch_id, active_product_id, '', active_asin, conclusion_buttons);
                break;
            case 'submit_with_comment':
                submitConclusionWithComment(active_button_action, active_batch_id, active_product_id, active_asin, conclusion_buttons);
                break;
        }
    });

    $('.dropdown').dropdown();


});