{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name=$section_title}
    {raw $form->renderBegin([
    'action' => $.app->router->url('admin:section', ['mid' => $distributorModel->manufacturerid, 'section' => $section]),
    'method' => 'POST',
    ])}
        <table class="dx_form" cellpadding="3" cellspacing="1" width="100%">
            {raw $form->getField('d_our_dealer_account_n')->render()}
            {raw $form->getField('d_contact_name_for_templates')->render()}
            <tr>
                <td colspan="3" align="center"><h1>Login to distributor website</h1></td>
            </tr>
            {raw $form->getField('d_url_to_login_to_distributor_website')->render()}
            <tr class="link_unhide">
                <td>
                    <b>Login/Username</b>
                    <a title="{Modules\Core\Models\LanguageModel::translate('help_d_login_text')}" class="tooltip">
                        <i class="fa fa-question-circle pointer"></i>
                    </a>
                </td>
                <td><a style="color: blue; border-bottom: 1px dotted blue; text-decoration: none;"
                       href="javascript: void(0);"
                       onclick="$('.unhide').closest('tr').show();$('.link_unhide').hide();">Unhide</a>
                </td>

            </tr>
            <tr class="link_unhide">
                <td>
                    <b>Password</b>
                    <a title="{Modules\Core\Models\LanguageModel::translate('help_d_password_text')}" class="tooltip">
                        <i class="fa fa-question-circle pointer"></i>
                    </a>
                </td>
                <td></td>
            </tr>
            {raw $form->getField('d_login')->render()}
            {raw $form->getField('d_password')->render()}
            {raw $form->getField('submit_to_operator')->render()}
            <tr {if $distributorModel->submit_to_operator === 'by_email_or_and_fax'}style="display: none;"{/if}
                id="tr_email_to_order_entry_operator">
                <td class="by_site" colspan="3" align="center"><b>Email to order entry operator</b></td>
            </tr>
            {raw $form->getField('order_entry_template')->render()}
            {raw $form->getField('template_1_subj')->render()}
            {raw $form->getField('template_1')->render()}
            {raw $form->getField('order_entry_special_instructions')->render()}

            <tr {if $distributorModel->submit_to_operator === 'through_distributor_website'}style="display: none;"{/if}
                id="order_submission_by_email_or_and_fax1">
                <td class="by_email" colspan="3" align="center"><h1>Order submission by e-mail or/and fax</h1></td>
            </tr>

            {raw $form->getField('allow_dispatch_off_working_hours')->render()}
            {raw $form->getField('add_cost_to_us_column_to_dispatch_message')->render()}
            {raw $form->getField('email')->render()}
            {raw $form->getField('order_submit_template')->render()}
            {raw $form->getField('template_2_subj')->render()}
            {raw $form->getField('template_2')->render()}
            {raw $form->getField('order_submit_special_instructions')->render()}
            {raw $form->getField('d_shipping_options')->render()}
        </table>
        <div class="row" style="margin-top: 15px;">
            <div class="column text-center">
                <button type="submit">Save</button>
            </div>
        </div>
    {raw $form->renderEnd()}
    {/smarty_admin_block}
{/block}