<table width="100%" cellspacing="1" cellpadding="3">
<tr class="TableHead">
    <td>Batch #</td>
    <td>Date created</td>
    <td>User</td>
    <td>Items count</td>
    <td>Status</td>
    <td>Action</td>
</tr>
    {if $batches}
    {foreach $batches as $batch}
        <tr class="{cycle ["", "TableSubHead"]}">
            <td align="center"><a target="_blank" href="{url 'amazon:batch' id=$batch->batch_id}">{$batch->batch_id}</a></td>
            <td align="center">{$batch->created_at}</td>
            <td align="center">{$batch->user->login}</td>
            <td align="center">{$batch->getItemsCount()}</td>
            <td align="center">{$batch->getField('status')->toText()}</td>
            <td align="center"><a data-batch-id="{$batch->batch_id}" class="delete-batch" title="Delete" href="#"><i class="fa fa-remove"></i></a></td>
        </tr>
    {/foreach}
    {else}
        <tr>
            <td colspan="6" align="center">No data found</td>
        </tr>
    {/if}
</table>