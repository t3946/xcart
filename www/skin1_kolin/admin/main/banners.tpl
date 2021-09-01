<br>

{if $section eq 'form'}
    {capture name=dialog}

        <a href="/admin/configuration.php?option=Banners&mode=new">
            Add
        </a>
        &shy;&nbsp;
        <a href="/admin/configuration.php?option=Banners">
            List
        </a>
        <br>
        <br>
        <style type="text/css">
            {literal}
            #i_html {
                min-width: 700px;
                min-height: 200px;
            }
            {/literal}
        </style>

        {*<script type="text/javascript" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>*}

        {if $errors}
            <div class="errors">
                <h3>Errors:</h3>
                {foreach from=$errors item=error}
                    <p style="font-size: 14px; color:darkred;">{$error}</p>
                {/foreach}
            </div>
        {/if}


        <form action="" enctype="multipart/form-data" method="post">
            {if $mode eq 'edit'}<input type="hidden" value="{$banner.id}" name="bannerid">{/if}
            <input type="hidden" value="html" name="type">
            <table width="100%">
                <tr>
                    <td>
                        <label for="i_name">Name *</label>
                    </td>
                    <td>
                        <input id="i_name" type="text" name="name" value="{$banner.name}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="i_start_at">DateTime start *</label>
                    </td>
                    <td>
                        <input id="i_start_at" type="datetime" name="start_at" value="{$banner.start_at}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="i_end_at">DateTime end</label>
                    </td>
                    <td>
                        <input id="i_end_at" type="datetime" name="end_at" value="{$banner.end_at}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="i_position">Position</label>
                    </td>
                    <td>
                        <select name="position" id="i_position">
                            <option value="top" {if $banner.position eq 'top'}selected{/if}>Top</option>
                            <option value="top_mobile" {if $banner.position eq 'top_mobile'}selected{/if}>Top mobile</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="i_start_at">Storefronts *</label>
                    </td>
                    <td>
                        <input id="i_start_at" type="text" name="storefronts" value="{$banner.storefronts}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="">Enabled</label>
                    </td>
                    <td>
                        {if $mode eq 'new'}
                            {assign var="enabled" value="Y"}
                        {else}
                            {assign var="enabled" value=$banner.enabled}
                        {/if}

                        <label for="i_enabled_y">Y</label>
                        <input type="radio" name="enabled" value="Y" id="i_enabled_y" {if $enabled eq 'Y'}checked{/if}>
                        <label for="i_enabled_n">N</label>
                        <input type="radio" name="enabled" value="N" id="i_enabled_n" {if $enabled eq 'N'}checked{/if}>

                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="i_html">html *</label>
                    </td>
                    <td>
                        <textarea name="html" id="i_html" >{$banner.html|html_entity_decode|stripslashes}</textarea>
                    </td>
                </tr>

            </table>

            {if $mode eq 'new'}
                <input type="submit" value="Create" name="action">
            {/if}
            {if $mode eq 'edit'}
                <input type="submit" value="Update" name="action">
                <input type="submit" value="Delete" name="action">
            {/if}
        </form>
        <script type="text/javascript">
            {literal}
            $(function() {
                $("input[type='datetime']").datepicker();
            });
            {/literal}
        </script>
    {/capture}
    {include file="dialog.tpl" title="Banner add" content=$smarty.capture.dialog extra="width=100%"}

{elseif $section eq 'list'}

    {capture name=dialog}
        <a href="/admin/banners.php?mode=new">
            Add
        </a>
        <br>
        <br>


        <table cellpadding="3" cellspacing="1" width="100%">
            <tr class="TableHead">
                <td>Name</td>
                <td>Start date</td>
                <td>End date</td>
                <td>Position</td>
                <td>Storefronts</td>
                <td>Enabled</td>
                <td>actions</td>
            </tr>
            {foreach from=$banners item=item}
                <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>

                    <td align="">
                        <a href="/admin/configuration.php?option=Banners&bannerid={$item.id}">
                            {$item.name}
                        </a>
                    </td>
                    <td nowrap="nowrap">
                        {$item.start_at|date_format:'%d-%b-%Y'}
                    </td>
                    <td nowrap="nowrap">
                        {if $item.end_at}
                            {$item.end_at|date_format:'%d-%b-%Y'}
                        {/if}
                    </td>
                    <td align="">{$item.position}</td>
                    <td align="">{$item.storefronts}</td>
                    <td align="">{$item.enabled}</td>
                    <td align="">
                        <form action="/admin/configuration.php?option=Banners&bannerid={$item.id}" method="post">
                            <input type="hidden" name="bannerid" value="{$item.id}">
                            {if $item.enabled eq 'Y'}
                                <button name="action" value="Disable"> Click to Disable </button>
                            {else}
                                <button name="action" value="Enable"> Click to Enable </button>
                            {/if}


                            <button name="action" value="Delete" style="float: right"> X </button>
                        </form>
                    </td>
                </tr>
            {/foreach}
        </table>

    {/capture}
    {include file="dialog.tpl" title="Banners" content=$smarty.capture.dialog extra="width=100%"}


{else}
    {include file="main/error_page_not_found.tpl"}
{/if}
