{extends 'base/admin.tpl'}

{block "before-content"}
    {if $form}
        {set $distributorModel = $form->getDx()}
        {set $sections = $form->getSections()}
        {set $selected_section = $form->getSection($section)}
    {/if}
    {if $distributorModel && !$distributorModel->getIsNewRecord()}
        {set $prohibited = $distributorModel->getProhibitedProducts()}
        {set $approval = $distributorModel->getApprovalProducts()}
        {if $prohibited || $approval}
        <div class="enter_on_site align_left">
            <div style="padding: 12px 0 0; ">
                {if $prohibited}
                    <span style="margin-left: 1rem; font-size: 14px;">Dx offers the following products <b>prohibited by PayPal</b></span>
                    <ul>
                        {foreach $prohibited as $prp}
                        <li>{$prp}</li>
                        {/foreach}
                    </ul>
                {/if}
                {if $approval}
                    <span style="margin-left: 1rem; font-size: 14px;">Dx offers the following products requiring <b>approval by PayPal</b></span>
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
                    <h1 style="text-align: left">{$distributorModel} ({$distributorModel->code}) /
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
        {include 'admin/sections.tpl' current_section=$section sections=$sections}
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
    <h1 style="text-align: center">{$selected_section['title']}</h1>
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