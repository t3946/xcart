<tr class="{cycle ["", "TableSubHead"] index=$index}" data-brand-id="{$brand->brandid}">
    <td>
        <a target="_blank" href="{url 'product:group' id = $brand->brandid}"><b>{$brand->brand}</b></a>
    </td>
    <td>
        {$brand->getFromQueryAttribute('group_phrase')}
    </td>
    <td>
        {$brand->getFromQueryAttribute('count')}
    </td>
</tr>