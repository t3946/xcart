
{*{set $image = $model->images->limit(1)->get()}*}
{set $image = $model->images->filter(['active' => 'Y'])->order(['orderby'])->limit(1)->get()}
{if $image!}
    {set $img_url = "//cdn." ~ $.getSite->getBaseDomain() ~ $image->getURL(174)}
    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+P+/HgAFhAJ/wlseKgAAAABJRU5ErkJggg=="
         data-src="{$img_url}"
         alt="{$model->getFrontendName()|escape}"
         class="lazy lazy-img"
         {if !$schema_off}
         itemscope
         itemprop="image"{/if}>
{else}
    <div class="not-avail-thumb">
        <p>Image not available</p>
    </div>
{/if}