<tr class="{cycle ["", "TableSubHead"] index=$index}"
    data-product-id="{$model->productid}"
    data-title="{$model->product}"
    data-group-option="{$model->group_option}"
    data-group-mask="{$model->group_mask}"
    data-sku="{$model->productcode}"
    data-manufacturer-id="{$model->manufacturerid}">
    <td class="tree" align="center">
        <div data-url="{url 'product:group_product' id=$model->productid}" data-level="{Modules\Product\Helpers\ProductHelper::getGroupLevel($model->group_option)}" class="tree_cell single"></div>
    </td>
    <td class="checkbox">
        <input type="checkbox" class="tree-checkbox"/>
    </td>
    <td>
        <a target="_blank" href="{$model->getAdminUrl()}"><b>{$model->product}</b></a>
    </td>
    <td>
        {$model->brand->brand}
    </td>
    <td>
        {$model->getFromQueryAttribute('group_phrase')}
    </td>
    <td align="center">
        {$model->getFromQueryAttribute('count')}
    </td>
</tr>