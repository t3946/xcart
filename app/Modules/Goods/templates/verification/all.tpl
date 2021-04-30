{extends "admin/base.tpl"}

{block 'heading'}
    <h1>{$admin->name}</h1>
{/block}

{block 'main_block'}
    <div class="admin-page all-page">
        <div>{$.call.Modules.Core.Models.LanguageModel::translate('lbl_product_verification_explanation')}</div>
        {include 'admin/list/_list.tpl'}
    </div>
{/block}

{block 'js'}
    <div id="send_note_for_product" class="ajax_note_field" style="display: none;">
        <input id="verified_product_id" type="hidden" value=""/>
        <input id="verified_product_status_id" type="hidden" value=""/>
        <textarea rows="3" style="width: 100%;" cols="70" name="payment_note" id="notes"></textarea>
        <br/>
        <div style="margin-top:10px">
            <input type="button" id="post_message" value="Send">
            <input type="button" id="cancel_message_button" value="Cancel">
        </div>

    </div>
    <script>
        $(function () {
            $('.admin-page').on('change', 'select[id$=verification_status]', function () {
                const status_id = parseInt($(this).val());
                const product_id = parseInt($(this).closest('tr').data('pk'));
                const note_form = $('#send_note_for_product');
                const textarea = note_form.find('textarea');
                if (status_id > 0 && status_id < 3) {
                    const position = $(this).offset();
                    note_form.css('left', position.left - 342).css('top', position.top);
                    textarea.val('');
                    if (status_id === 1) {
                        textarea.attr('placeholder', "Please describe the problem and explain why you didn't fix it.");
                    }
                    if (status_id === 2) {
                        textarea.attr('placeholder', "Please describe what was the problem and how did you fix it.");
                    }
                    note_form.find('#verified_product_id').val(product_id);
                    note_form.find('#verified_product_status_id').val(status_id);
                    note_form.show();
                    textarea.focus();
                } else {
                    {var $id = $admin->getId()}
                    let list = $('[data-id="{$id}-list"]').data('object');
                    list.setLoading();
                    $.post('/api/products/verify', {
                            product_id: product_id,
                            status_id: status_id,
                        },
                        data => {
                            if (data && data.result) {
                                list.update();
                            } else {
                                list.unsetLoading();
                            }
                        });
                }
            });

            $('#send_note_for_product').on('click', '#cancel_message_button', () => {
                $('#send_note_for_product').hide();
            }).on('click', '#post_message', () => {
                const product_id = parseInt($('#verified_product_id').val());
                const status_id = parseInt($('#verified_product_status_id').val());
                const form = $('#send_note_for_product');
                const textarea = form.find('textarea');
                form.hide();
                {var $id = $admin->getId()}
                let list = $('[data-id="{$id}-list"]').data('object');
                list.setLoading();
                $.post('/api/products/verify', {
                        product_id: product_id,
                        status_id: status_id,
                        note_text: textarea.val(),
                    },
                    data => {
                        if (data && data.result) {
                            list.update();
                        } else {
                            list.unsetLoading();
                        }
                    });
            });
        })
    </script>
{/block}