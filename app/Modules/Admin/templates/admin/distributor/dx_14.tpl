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

            {raw $form->getField('request_avail_emails')->render()}

            {raw $form->getField('request_avail_template')->render()}

            {raw $form->getField('template_1_subj')->render()}

            {raw $form->getField('template_1')->render()}

            {raw $form->getField('d_message_body_14')->render()}

            <tr>
                <td colspan="3">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        {raw $form->getField('d_availability_request_schedule')->render()}
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