<link rel="stylesheet" href="{$SkinDir}/js/semantic/components/loader.min.css">
<br>
<br>
{capture name=az_monitor_upload_status}
    {include file="customer/main/navigation.tpl"}
    <form action="az_monitor_upload_status.php" method="post" name="az_monitor_upload_status">
        <table cellpadding="3" cellspacing="1" width="100%" id="az_monitor_upload_status_table">
            <tr class="TableHead">
                <th style="width: 120px; overflow: hidden; white-space: nowrap;">Date/Time of Upload</th>
                <th style="width: 120px; overflow: hidden; white-space: nowrap;">Login</th>
                <th style="width: 250px; overflow: hidden; display: inline-block; white-space: nowrap;">Feed ID</th>
                <th>Products submited</th>
                <th>Products success</th>
                <th>Products failed</th>
                <th>Upload Status</th>
            </tr>
            {if $aFeeds}
                {foreach from=$aFeeds item=aFeed}
                    <tr {cycle values=", class='TableSubHead'"} {if $aFeed.status == '_SUBMITTED_'}data-status="new"{/if}>
                        <td>
                            {$aFeed.feed_date}
                        </td>
                        <td>
                            {$aFeed.Customer->getCustomerFullName()}
                            <br/>{if $aFeed.Customer->getCustomerLogin()}({/if}{$aFeed.Customer->getCustomerLogin()}{if $aFeed.Customer->getCustomerLogin()}){/if}
                        </td>
                        <td class="amazon_submition_id" data-feed-id="{$aFeed.amazon_submition_id}">
                            {$aFeed.amazon_submition_id}
                        </td>
                        <td align="center">
                            {$aFeed.total}
                        </td>
                        <td align="center">
                            {$aFeed.success}
                        </td>
                        <td align="center">
                            {$aFeed.error}
                        </td>
                        <td class="feed_status" align="center">{$aFeed.status}</td>
                    </tr>
                {/foreach}
            {/if}
        </table>
        {include file="customer/main/navigation.tpl"}
    </form>
{/capture}

{include file="dialog.tpl" title='Monitor Upload Status' content=$smarty.capture.az_monitor_upload_status extra='width="100%"'}

{literal}
    <script type="text/javascript">
        $('#az_monitor_upload_status_table').find('tr[data-status=new]').each(function () {
            var row = $(this);
            var feed_id = $(this).find('.amazon_submition_id').data('feed-id');
            row.find('.feed_status').addClass('ui centered inline small loader active').text('');
            $.post('ajax_admin.php', {
                        ajax_action: 'get_amazon_feed_status',
                        feed_id: feed_id
                    },
                    function (data) {
                        if (data.result) {
                            row.attr('data-status', 'updated');
                            row.find('.feed_status').removeClass('ui centered inline mini loader active');
                        }
                    }, 'json');
        });
    </script>
{/literal}