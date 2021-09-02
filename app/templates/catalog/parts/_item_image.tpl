{set $image = $model->getImages()[0]}
{if $image!}
    {set $img_url = $image->getCdnURL('thumb')}
    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+P+/HgAFhAJ/wlseKgAAAABJRU5ErkJggg=="
         data-src="{$img_url}"
         alt="{$model->getFrontendName()|escape}"
         class="lazy lazy-img{$class ? " $class" : ''}"
         {if !$schema_off}
         itemscope
         itemprop="image"{/if}>
{else}
    <div class="not-avail-thumb">
        <p>Image not available</p>
    </div>
{/if}