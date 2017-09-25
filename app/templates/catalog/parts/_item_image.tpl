
{set $image = $model->images->limit(1)->get()}
{if $image!}
    {if $.isBot}
        <img src="//cdn.{$.getSite->getBaseDomain()}{$image->getURL()}" width="{$image->image_x}" height="{$image->image_y}" alt="{$model.product}" itemscope itemprop="image">
    {else}
        <img data-src="//cdn.{$.getSite->getBaseDomain()}{$image->getURL()}" width="{$image->image_x}" height="{$image->image_y}" alt="{$model.product}" class="lazy lazy-img" itemprop="image">
    {/if}
{else}

{*<img src="http://via.placeholder.com/200x200/efefef/a6a6a6/?text=No+image" alt="Image not available">*}
<div class="not-avail">
    <span class="text">
        Image not available
    </span>
</div>
{/if}