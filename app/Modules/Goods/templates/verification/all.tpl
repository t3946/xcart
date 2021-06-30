{extends "admin/base.tpl"}

{block 'heading'}
    <h1>{$admin->name}</h1>
{/block}

{block 'main_block'}
    <div class="admin-page all-page qwe123">
        <div>{$.call.Modules.Core.Models.LanguageModel::translate('lbl_product_verification_explanation')}</div>
        {include 'admin/list/_list.tpl'}
        {store data=$admin->getId() key='id' ctx="goodsModule"}
    </div>
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
{/block}