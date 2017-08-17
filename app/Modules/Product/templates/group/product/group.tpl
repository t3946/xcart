<tr class="{cycle ["", "TableSubHead"] index=$index}" data-brand-id="{$brand->brandid}">
    <td align="center">
        <div data-group-phrase="{$group_phrase}" data-level="1" data-url="{url 'product:group' id=$brand->brandid}" class="tree_cell"></div>
    </td>
    <td>
        {$group_phrase}
    </td>
    <td>
        {$count}
    </td>
</tr>