{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name=$section_title}
    {raw $form->renderBegin([
    'action' => $.app->router->url('admin:section', ['mid' => $distributorModel->manufacturerid, 'section' => $section]),
    'method' => 'POST',
    ])}
        <table class="dx_form" cellpadding="3" cellspacing="1" width="100%">
            {raw $form->getField('d_availability_must_be_checked')->render()}
            <tr class="click_hide" {if !$distributorModel->d_availability_must_be_checked}style="display:none;"{/if}>
                <td colspan="3">
                    {Modules\Core\Models\LanguageModel::translate('txt_distributor_section_14')}
                </td>
            </tr>
            {raw $form->getField('d_send_to_email_14')->render()}
            {raw $form->getField('d_email_subject_14')->render()}
            {raw $form->getField('d_message_body_14')->render()}
            {raw $form->getField('add_ca_status_id')->render()}
            <tr class="click_hide" id="tr_d_webpage_properties" {if !$distributorModel->d_availability_must_be_checked}style="display: none;"{/if}>
                <td colspan="3">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="color: #000000;">
                                <b>{ignore}{{webpagebutton}}{/ignore} webpage properties</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="SubHeaderBlackLine"></td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="">&nbsp;</td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Corresponding template names<B></td>
                        </tr>
                        {raw $form->getField('d_sec14_show_header')->render()}
                        {raw $form->getField('d_sec14_show_items_stock')->render()}
                        {raw $form->getField('d_sec14_show_shipto')->render()}
                        {raw $form->getField('d_sec14_show_items_cost')->render()}
                        {raw $form->getField('d_sec14_show_footer')->render()}
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="color: #000000;"><B>Availability request schedule</B></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="SubHeaderBlackLine">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3">{Modules\Core\Models\LanguageModel::translate('lbl_server_min_distributor_time')}</td>
                        </tr>
                        {raw $form->getField('d_server_min_distributor_time')->render()}
                    </table>
                </td>
            </tr>
        </table>
        <div class="row" style="margin-top: 15px;">
            <div class="column text-center">
                <button type="submit">Save</button>
            </div>
        </div>
    {raw $form->renderEnd()}
    {/smarty_admin_block}
{/block}