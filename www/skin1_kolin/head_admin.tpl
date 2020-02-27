<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td class="HeadLogo_admin" width="*">
            <a href="/{if $usertype eq "P"}provider{elseif $usertype eq "V"}verificator{else}admin{/if}/">
            {if $site}
                <img width="186" src="/static/frontend/dist/images/logos/sites/{$site->code|lower}/logo.svg" alt=""/>
            {else}
                <img src="{$ImagesDir}/admin_xlogo.gif" width="244" height="67" alt=""/>
            {/if}
            </a>
        </td>
        {if $login ne ""}
            <td align="left" width="70%">
                {php}
                    $est_time = new DateTime("now", new DateTimeZone('EST') );
                    $ny_time = new DateTime("now", new DateTimeZone('America/New_York'));
                    $ca_time = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
                    $this->assign('est_time', $est_time);
                    $this->assign('ny_time', $ny_time);
                    $this->assign('ca_time', $ca_time);
                {/php}
                <div style="width:44%; float:right">
                    <div style="float:left; margin-right:7px;">
                        <div><a style="color: #140BFC" href="/admin/product_question_search.php?mode=search&status=all&from_dashboard=Y">Product questions</a></div>
                        <div><a style="color: #140BFC" href="/admin/pbx/pbxcalls">Call recordings</a></div>
                        <div><a style="color: #140BFC" href="/admin/reports">Order reports</a></div>
                    </div>
                    <div style="float:left; margin-right:7px;">
                        <div><a style="color: #140BFC" href="/admin/reconciliation.php">Reconciliation / AP & AR</a></div>
                        <div><a style="color: #140BFC" href="/admin/checks_deposited.php">Checks deposited</a></div>
                        <div><a style="color: #140BFC" href="/admin/reports.php">Reports</a></div>
                    </div>
                </div>
                <div style="float:right">
                    <div style="float:left; margin-right:16px;">
                        {if $usertype ne "V"}
                            <a href="{$xcartApp->router->url('dashboard:index')}">
                                <img src="{$ImagesDir}/cc_dashbord.png" alt=""/>
                            </a>
                        {/if}
                    </div>
                </div>
                <div style="float:right;">
                    <div style="float:left; margin-right:7px;">
                        <div style="margin-bottom: 3px;">{$est_time->format('F j, Y')}</div>
                        {php}
                            if ($holiday = Modules\Main\Helpers\WorkingTimeHelper::getNextHoliday(new DateTime))
                            {
                                $this->assign('next_holiday', $holiday);
                                $this->assign('next_holiday_days', $holiday->getDaysUntil());
                            }
                        {/php}
                        {if $next_holiday && $next_holiday_days !== null}
                            <div style="text-align:center; border: 2px solid red;">
                                {if $next_holiday_days > 0}
                                    <div>{$next_holiday_days} day{if $next_holiday_days > 1}s{/if} until</div>
                                {/if}
                                <div>{$next_holiday}</div>
                            </div>
                        {/if}
                    </div>
                    <div style="float:left; margin-right:7px;">
                        <div style="margin-bottom: 3px;">EST Time: {$est_time->format('H:i')}</div>
                        <div style="margin-bottom: 3px;">&nbsp;NY Time: {$ny_time->format('H:i')}</div>
                        <div style="margin-bottom: 3px;">&nbsp;CA Time: {$ca_time->format('H:i')}</div>
                    </div>
                    {if $order_store}
                    <div style="float:left; margin-right:7px;">
                        {assign var='cs_date' value=$order_store->model->getCxDateTime()}
                        {if $cs_date}
                        <div style="margin-bottom: 3px;">Cx Time: {$cs_date->format('H:i')}</div>{/if}
                        <div style="margin-bottom: 3px; float:left; margin-right:7px;">
                            Dx Time:&nbsp;
                            <span style="float:right">
                            {foreach from=$order_store->model->groups item=group}
                                {assign var=distributor value=$group->manufacturer}
                                {if $distributor}
                                    {assign var=distributor_time value=$distributor->getDistributorTime()}
                                    {$distributor_time->format('H:i')}<br/>
                                {/if}
                            {/foreach}
                            </span>
                        </div>
                    </div>
                    {/if}
                </div>
            </td>
            <td align="right" width="15%">
                {include file="authbox_top.tpl"}
            </td>
            <td width="10"><img src="{$ImagesDir}/spacer.gif" width="10" height="1" alt=""/></td>
        {/if}
    </tr>
</table>

<table cellpadding="0" cellspacing="0" width="100%" border="0" class="headSearchLine">
<tr>
    <td class="HeadLine" height="22" width="33%">
            {include file="main/search.tpl"}
    </td>


    <td width="34%" align="center" class="HeadLine">
    {if $usertype eq 'A' && $login}
        <script type="text/javascript">
        {literal}

        $(document).ready(function () {
            $('#select_searchstring_by').change(function () {
                var select_searchstring_by = $('#select_searchstring_by').val();
                $('#searchstring').attr("name", "search" + select_searchstring_by);
            });
        });

        {/literal}
        </script>

        <form method="post" action="{$xcartApp->router->url('dashboard:search')}" name="productsearchform">
            <input type="hidden" name="fast_search" value="Y"/>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <select id="select_searchstring_by">
                            <option value="[order][id][from]">Order # / Amazon order ID</option>
                            <option value="[order][po]">PO #</option>
                            <option value="[customer][zip_code]">Zip code</option>
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               id="searchstring"
                               name="search[order][id][from]"
                               size="18"
                               value=""/>
                    </td>
                    <td>
                        <input type="submit" value="{$lng.lbl_search}"/>
                    </td>
                </tr>
            </table>
        </form>
    {/if}
    </td>


{*
<td class="HeadLine" align="right" height="22">
{if ($usertype eq "P" or $usertype eq "A") and $login and $all_languages_cnt gt 1}
<form action="{$smarty.server.REQUEST_URI|amp}" method="post" name="asl_form">
<table cellpadding="0" cellspacing="0">
<tr>
    <td><b>{$lng.lbl_current_language}:</b>&nbsp;</td>
    <td>
<input type="hidden" name="redirect" value="{$smarty.server.QUERY_STRING|amp}" />
<select name="asl" onchange="javascript: document.asl_form.submit()">
{section name=ai loop=$all_languages}
<option value="{$all_languages[ai].code}"{if $current_language eq $all_languages[ai].code} selected="selected"{/if}>{$all_languages[ai].language}</option>
{/section}
</select>
    </td>
</tr>
</table>
</form>
{else}
&nbsp;
{/if}
</td>
*}


    <td class="HeadLine" align="right" height="22" width="33%">

    {if $active_modules.Multiple_Storefronts && $usertype eq "A" && $login && $current_membership_flag ne 'FS'}

        {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER")}
            <div style="float: right;">
                <input type="button"
                       name="{$lng.lbl_sf_properties}"
                       value="{$lng.lbl_sf_properties}"
                       onclick="location.href='configuration.php?option=Multiple_Storefronts'">
</div>
        {/if}

    {else}
        &nbsp;
    {/if}

        {if $active_modules.Multiple_Storefronts && ($usertype eq 'A' && $current_membership_flag ne 'FS' || $usertype eq 'P') && $login}
            <div style="float: right;">
	<form action="{$smarty.server.REQUEST_URI|amp}" method="post" name="storefrontsform">
	<input type="hidden" name="mode" value="change_storefront"/>
		<select name="cur_sf" onchange="javascript: document.storefrontsform.submit();">
            {foreach from=$sd_selects key=key item=sf}
                <option value="{$key}"{if $current_storefront eq $key} selected="selected" disabled="disabled" {/if}>{$sf}</option>
            {/foreach}
		</select>
	</form>
</div>
        {else}
            &nbsp;
        {/if}

</td>



</tr>
</table>
