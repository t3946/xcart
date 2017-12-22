{*
$Id$ 
vim: set ts=2 sw=2 sts=2 et:
*}
<div class="content-primary">
  <ul data-role="listview" data-type="categories-list">
    {foreach from=$categories item=subcat name=subcategories}
{if $subcat.order_by ge 0 && $subcat.order_by le 500 && ($subcat.product_count gt 0 || $subcat.global_product_count gt 0)}
      <li>
        <a href="home.php?cat={$subcat.categoryid}" {*TODO: check speed and wrokflow of data-prefetch, too much data here.*}>
          {$subcat.category}
{*
          {if $subcat.product_count}
            <span class="ui-li-count">{$subcat.product_count}</span>
          {/if}
*}
        </a>
      </li>
{/if}
    {/foreach}
  </ul>
</div>
