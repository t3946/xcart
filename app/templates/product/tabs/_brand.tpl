{add $brand = $model->brand}

<div class="row">
    <div class="column small-12 large-2 block image-title">
        {set $image = $brand->getImage()}
        {if $image}
            <div class="image">
                <img data-src="//cdn.{$.getSite->getBaseDomain()}{$image->getURL()}" alt="{$brand.brand}" itemprop="image" class="lazy lazy-img">
            </div>
        {/if}

        <h2 class="hide-for-large small-title title">
            <span class="multiline">
                {$brand->brand}
            </span>
        </h2>

        <a href="{$brand->getAbsoluteUrl()}" class="show-for-large link-to-all">
            See all {$brand->brand} products
        </a>
    </div>
    <div class="column small-12 large-10 block">
        <h2 class="show-for-large title">
            <span class="multiline">
                {$brand->brand}
            </span>
        </h2>

        <div class="content">
            {raw $brand->descr}

            <div>
                <a href="{$brand->getAbsoluteUrl()}" class="hide-for-large link-to-all">
                    See all {$brand->brand} products
                </a>
            </div>
        </div>
    </div>
</div>