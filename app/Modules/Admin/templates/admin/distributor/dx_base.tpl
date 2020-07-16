{extends 'base/admin.tpl'}

{block "before-content"}
    {if $form}
        {set $distributorModel = $form->getDx()}
    {/if}
    {if $distributorModel && !$distributorModel->getIsNewRecord()}
        {set $prohibited = $distributorModel->getProhibitedProducts()}
        {set $approval = $distributorModel->getApprovalProducts()}
        {if $prohibited || $approval}
        <div class="enter_on_site align_left">
            <div class="enter_on_site__content text_left">
                {if $prohibited}
                    <span style="margin-left: 1rem;">Dx offers the following products <b>prohibited by PayPal</b></span>
                    <ul>
                        {foreach $prohibited as $prp}
                        <li>{$prp}</li>
                        {/foreach}
                    </ul>
                {/if}
                {if $approval}
                    <span style="margin-left: 1rem;">Dx offers the following products requiring <b>approval by PayPal</b></span>
                    <ul>
                        {foreach $approval as $prp}
                            <li>{$prp}</li>
                        {/foreach}
                    </ul>
                {/if}
            </div>
        </div>
        {/if}
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td width="33%">
                    <h1 style="text-align: left">
                        <a href="/admin/manufacturers.php?word=num" style="color: #0101F7;">Distributors</a>
                    </h1>
                </td>
                <td width="*">
                    <h1 style="text-align: left">{$distributorModel} /
                        <a style="color: #0101F7;" href="{$distributorModel->getAdminOrdersUrl(6)}" target="_blank">
                            Last 6 months of order history
                        </a>
                    </h1>
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td width="*" align="left" valign="top">
                    {Modules\Core\Models\LanguageModel::translate('txt_manufacturers_top_text')}
                </td>
                <td width="2%" align="center">&nbsp;</td>
                <td width="48%" align="left" valign="top">
                    <table>
                        <tr>
                            <td>
                                <B>Distributor time:</B> {$distributorModel->getDistributorTime()->format('H:i')}
                                <br/>
                                <B>Distributor phone:</B> {$distributorModel->getPhoneNormalized()}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="call_btn_distr_{if $distributorModel->isGoodTimeToSendEmail()}a{else}d{/if}">
                                    <a target="_blank" href="tel:{$distributorModel->getPhoneNormalized()}">
                                        <div style="width: 219px; height: 44px;"></div>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <table cellspacing="0" cellpadding="0" width="100%" class="NavDialogBox" style="BORDER: #FFCC33 1px solid;">
            <tr>
                <td class="NavDialogBorder" height="15"><B>Distributor sections:</B></td>
                <td class="NavDialogBorder" height="15" align="right">
                    <a href="" target="_blank" style="color: #0101F7"></a>
                </td>
            </tr>
            <tr>
                <td width="100%" valign="top" cellspacing="0" cellpadding="0"
                    style="display: grid; grid-template-columns: 1fr 1fr; grid-auto-flow: row dense; grid-gap: 0.5rem;">
                    {set $section_fileds = Modules\Admin\Forms\Dx\DistributorForm::getSections()}
                    {set $cnt = count($section_fileds)}
                    {set $split = round($cnt / 2)}
                    <div>
                    {foreach $section_fileds as $fN => $fieldset index=$index first=$f1}
                        {if $index == $split}
                            </div><div>
                        {/if}
                        <fieldset class="" style="margin-bottom: 0; background:inherit; grid-column-start: {if $cnt/2 > $index}1{else}2{/if};">
                            <legend><b style="font-size: 15px;color: red;">{$fN}</b></legend>
                            <ul class="ul-main" style="margin: 0">
                                {foreach $fieldset as $key => $item first=$first}
                                    <li>
                                        <a href="" class="VertMenuItems"><img alt="" src="/skin1_kolin/images/rarrow.gif"></a>
                                        {if $item.distributor_section != $section}
                                        <a style="color: #330000;"
                                           href="{url 'admin:section' params=['mid' => $distributorModel->pk, 'section' => $item.distributor_section]}">
                                        {/if}
                                        {if $item.distributor_section == $section}<b>{/if}
                                            {$item['title']}
                                        {if $item.distributor_section == $section}</b>{/if}
                                        {if $item.distributor_section != $section}
                                        </a>
                                        {/if}
                                    </li>
                                {/foreach}
                            </ul>
                        </fieldset>
                    {/foreach}
                    </div>
                    {*<table width="100%">
                        {set $sections = Modules\Admin\Forms\Dx\DistributorForm::getSections()}
                        {foreach $sections as $key => $item index=$index first=$first}
                            {if !$index % 2}
                                <tr>
                            {/if}
                            <td class="NavDialogCell">
                                <a href="" class="VertMenuItems"><img alt="" src="/skin1_kolin/images/rarrow.gif"></a>
                                {if $item.distributor_section != $section}
                                <a style="color: #330000;"
                                   href="{url 'admin:section' params=['mid' => $distributorModel->pk, 'section' => $item.distributor_section]}">
                                    {/if}
                                    {if $item.distributor_section == $section}<b>{/if}
                                        {$item.title}
                                        {if $item.distributor_section == $section}</b>{/if}
                                    {if $item.distributor_section != $section}
                                </a>
                                {/if}
                            </td>
                            {if ($index % 2)}
                                </tr>
                            {/if}
                        {/foreach}
                    </table>*}
                </td>

            </tr>
        </table>
        <br/>
        <br/>
    {/if}
{/block}

{block 'content'}
    <style type="text/css">
        a.admin_link {
            color: blue;
        }

        ,
        a.admin_link:hover {
            text-decoration: none !important;
            color: red;
        }

        .dx_form input, .dx_form textarea {
            width: 100%;
        }

        .admin .required {
            color: inherit;
        }
    </style>
    <div align="right">
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <a class="admin_link" href="/admin/manufacturers.php?&word=num">Distributor list</a>
                </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                    <a class="admin_link" href="{url 'admin:dx_add'}">Add Distributor</a>
                </td>
            </tr>
        </table>
    </div>
{/block}

{block 'js'}
    <script>
        $(function () {
            let t = $('.tooltip').tooltip({
                position: {
                    using: function (position, feedback) {
                        $(this).css(position);
                        $("<div>")
                            .addClass("tooltip__s3")
                            .appendTo(this);
                    }
                },
                content: function () {
                    return $(this).attr('title');
                },
                open: function (event, ui) {
                    ui.tooltip.css("max-width", "650px");
                }
            });
        });
    </script>
{/block}