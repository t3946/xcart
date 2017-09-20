{add $brand = $model->brand}

<div class="tab-brand">
    <div class="row">
        <div class="column small-12 large-3 block">
            {set $image = $brand->getImage()}
            {if $image}
                <div class="image">
                    <img data-original="//cdn.{$.getSite->getBaseDomain()}{$image->getURL()}" alt="{$brand.brand}" itemprop="image" class="lazy lazy-img">
                </div>
            {/if}

            <h2 class="hide-for-large">{$brand->brand}</h2>

            <a href="{$brand->getAbsoluteUrl()}" class="show-for-large link-to-all">
                See all {$brand->brand} products
            </a>
        </div>
        <div class="column small-12 large-9 block">
            <h2 class="show-for-large">{$brand->brand}</h2>

            <div class="content">
                {raw $brand->descr}

                <a href="{$brand->getAbsoluteUrl()}" class="hide-for-large link-to-all">
                    See all {$brand->brand} products
                </a>
            </div>
        </div>
    </div>
</div>