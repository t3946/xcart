<div id="admin-hat-target" style="height: 101px"></div>

<table class="admin-hat" cellpadding="0" cellspacing="0" width="100%" style="display: none">
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
                {assign var=est_time value=date_create('now', timezone_open('EST'))}
                {assign var=ny_time value=date_create('now', timezone_open('America/New_York'))}
                {assign var=ca_time value=date_create('now', timezone_open('America/Los_Angeles'))}
                {if !$xcartApp->user->hasRoles(['vrs','vrv'])}
                <div style="width:44%; float:right">
                    <div style="float:left; margin-right:7px;">
                        <div><a style="color: #140BFC" href="/admin/product_question_search.php?mode=search&status=all&from_dashboard=Y">Product questions</a></div>
                        <div><a style="color: #140BFC" href="/admin/pbx/pbxcalls">Call recordings</a></div>
                        <div><a style="color: #140BFC" href="/admin/reports">Order reports</a></div>
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
                {/if}
                <div style="float:right;">
                    <div style="float:left; margin-right:7px;">
                        <div style="margin-bottom: 3px;">{$est_time->format('F j, Y')}</div>
                        {assign var=holiday value=Modules\Main\Helpers\WorkingTimeHelper::getNextHoliday(date_create('now', timezone_open('EST')))}
                        {if $holiday}
                            {assign var=next_holiday_days value=$holiday->getDaysUntil()}
                        {/if}
                        {if $holiday && $next_holiday_days !== null}
                            <div style="text-align:center; border: 2px solid red;">
                                {if $next_holiday_days > 0}
                                    <div>{$next_holiday_days} day{if $next_holiday_days > 1}s{/if} until</div>
                                {/if}
                                <div>{$holiday}</div>
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

{if (!$xcartApp->user->hasRoles(['vrs','vrv']))}
    <div id="admin-search-line-target" style="height: 45px"></div>
{/if}
