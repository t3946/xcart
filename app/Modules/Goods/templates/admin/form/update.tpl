{extends 'admin/update.tpl'}

{block 'before_form'}
    <table width="100%">
        <tr>
            <td>
                <a style="color: #140BFC" href="{$model->getAbsoluteUrl(true)}" target="_blank">{$model->product}</a>
            </td>
            <td style="text-align: right">
                {if $model->getDistributorUrl()}
                    <a style="color: #140BFC" href="{$model->getDistributorUrl()}" target="_blank">Product on distributor's website: {$model->getMpn()}</a>
                {/if}
            </td>
        </tr>
    </table>
{/block}