{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name=$section_title}
        <p>
            Simplified, easy to understand PayPal Acceptable Use Policy: <br/>
            <a style="color: #0101F7;" target="_blank" href="https://docs.google.com/document/d/1Imu2t_YU3vqWQwBH_UOSUHIptFm95r8evezsc9HFZXw/edit?usp=sharing">
                https://docs.google.com/document/d/1Imu2t_YU3vqWQwBH_UOSUHIptFm95r8evezsc9HFZXw/edit?usp=sharing
            </a>
        </p>

        <p>
            Up-to-date complete PayPal Acceptable Use Policy: <br/>
            <a style="color: #0101F7;" target="_blank" href="https://www.paypal.com/us/webapps/mpp/ua/acceptableuse-full">
                https://www.paypal.com/us/webapps/mpp/ua/acceptableuse-full
            </a>
        </p>


    {raw $form->renderBegin([
    'action' => $.app->router->url('admin:section', ['mid' => $distributorModel->manufacturerid, 'section' => $section]),
    'method' => 'POST',
    ])}
        {raw $form->render()}

        <div class="row" style="margin-top: 15px;">
            <div class="column text-center">
                <button type="submit">Save</button>
            </div>
        </div>

    {raw $form->renderEnd()}
    {/smarty_admin_block}
{/block}