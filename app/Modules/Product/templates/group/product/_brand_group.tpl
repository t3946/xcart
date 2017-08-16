<tr class="{cycle ["", "TableSubHead"] index=$index}" data-brand-id="{$brand->brandid}">
    <td align="center">
        <a href="#">+</a>
    </td>
    <td>
        <a target="_blank" href="{$brand->getAdminUrl()}"><b>{$brand->brand}</b></a>
    </td>
    <td>
        {$brand->getNotModelAttribute('group_phrase')}
    </td>
    <td>
        {$brand->getNotModelAttribute('count')}
    </td>
</tr>