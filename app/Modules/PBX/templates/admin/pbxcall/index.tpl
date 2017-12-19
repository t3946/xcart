{extends "base/admin.tpl"}
{block "heading"}
    <h1>{$page_title}</h1>
{/block}
{block "content"}
    {smarty_admin_block name='Calls'}



        <form method="get" action="/admin/pbx/pbxcalls" class="search-form">

            <fieldset class="collapsible expanded " rel="0">
                <legend>Filter</legend>
               {$form->render($form->getTemplateFromType('ul'))}
                <input type="submit" value="Find">
                <input type="submit" name="reset" value="Clean filter">
            </fieldset>
        </form>

        <hr>

        <table width="100%" border="1" cellpadding="14" cellspacing="0" style="table-layout: auto;">
            <thead>
            <tr>
                <th>
                    Order #
                </th>
                <th>
                    Party Tel #
                </th>
                <th>
                    Party Details
                </th>
                <th>
                    Direction
                </th>
                <th>
                    Operator Name
                </th>
                <th>
                    Starting Time
                </th>
                <th>
                    Duration
                </th>
                <th>
                    Audio
                </th>
            </tr>
            </thead>
            <tbody>
            {foreach $mass as $value}
                <tr>
                    <td align="center">
                        <a href="{$value.order_url}" target="_blank">{$value.order_id}</a>
                    </td>
                    <td align="center">
                        {$value.e164}
                    </td>
                    <td align="center">
                        {$value.cx_name}
                    </td>
                    <td align="center">
                        {$value.direction}
                    </td>
                    <td align="center">
                        {$value.name}
                    </td>
                    <td align="center">
                        {$value.start_at}
                    </td>
                    <td align="center">
                        {$value.diff}
                    </td>
                    <td align="center" id="{$value.call_id}">
                        {if $value.url?}
                            <a href="{$value.url}" target="_blank" ">Listen</a>
                        {else}
                        Not defined
                        {/if}
                    </td>
                </tr>
            {/foreach}
            </tbody>

        </table>
    {/smarty_admin_block}

    {$pager}
{/block}