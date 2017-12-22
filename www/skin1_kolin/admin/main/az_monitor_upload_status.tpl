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
                <th>Products submitted</th>
                <th>Products success</th>
                <th>Products failed</th>
                <th>Upload Status</th>
            </tr>
            {if $aFeeds}
                {foreach from=$aFeeds item=aFeed}
                    <tr data-feed-id="{$aFeed.feed_id}" {cycle values=", class='TableSubHead'"} {if $aFeed.status == '_SUBMITTED_'}data-status="new"{/if}>
                        <td>
                            {$aFeed.feed_date}
                        </td>
                        <td>
                            {$aFeed.Customer->getCustomerFullName()}
                            <br/>{if $aFeed.Customer->getCustomerLogin()}({/if}{$aFeed.Customer->getCustomerLogin()}{if $aFeed.Customer->getCustomerLogin()}){/if}
                        </td>
                        <td class="amazon_submition_id" data-amazon-submition-id="{$aFeed.amazon_submition_id}">
                            <a href="https://sellercentral.amazon.com/listing/status?reference_id={$aFeed.amazon_submition_id}#{$aFeed.amazon_submition_id}" target="_blank"> {$aFeed.amazon_submition_id}</a>
                        </td>
                        <td class="stats listing_total" data-type="total" align="center">
                            {if $aFeed.total}<a href="/">{/if}
                                {$aFeed.total}
                            {if $aFeed.total}</a>{/if}
                        </td>
                        <td class="stats listing_success" data-type="success" align="center">
                            {if $aFeed.success}<a href="/">{/if}
                                {$aFeed.success}
                            {if $aFeed.success}</a>{/if}
                        </td>
                        <td class="stats listing_failed" data-type="failed" align="center">
                            {if $aFeed.error}<a href="/">{/if}
                                {$aFeed.error}
                            {if $aFeed.error}</a>{/if}
                        </td>
                        <td class="feed_status stats" data-type="log" align="center">
                            <a href="/">
                            {$aFeed.status}
                            </a>
                        </td>
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
            var feed_id = $(this).find('.amazon_submition_id').data('amazon-submition-id');
            row.find('.feed_status').addClass('ui centered inline small loader active').text('');
            $.post('ajax_admin.php', {
                        ajax_action: 'get_amazon_feed_status',
                        feed_id: feed_id
                    },
                    function (data) {
                        if (data.result) {
                            row.attr('data-status', 'updated')
                            .find('.listing_total').text(data.total)
                            .end().find('.listing_success').text(data.success)
                            .end().find('.listing_failed').text(data.failed)
                            .end().find('.feed_status').removeClass('ui centered inline mini loader active').text(data.status);
                        }
                    }, 'json');
        }).end().on('click', '.stats > a', function () {
            var row = $(this).parent().parent();
            var feed_id = row.data('feed-id');
            var type = $(this).parent().data('type');
            var old_rows = row.parent().find('tr.listing_products').css('opacity', 0.4);
            $.post('ajax_admin.php', {
                        ajax_action: 'get_amazon_listing_products',
                        feed_id: feed_id,
                        type: type
                    },
                    function (data) {
                        if (data) {
                            old_rows.remove();
                            row.after(data);
                        }
                    });
            return false;
        })
    </script>
{/literal}