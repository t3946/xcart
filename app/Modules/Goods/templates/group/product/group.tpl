<tr class="{cycle ["", "TableSubHead"] index=$index}" data-brand-id="{$brand->brandid}">
    <td class="tree" align="center">
        {if $count > 1}
          <div data-group-phrase="{$group_phrase|escape}" data-level="{$level}" data-url="{url 'product:group' id=$brand->brandid}" class="tree_cell"></div>
        {/if}
    </td>
    <td class="checkbox"></td>
    <td class="phrase">
        {$group_phrase}
    </td>
    <td class="count">
        {$count}
    </td>
</tr>