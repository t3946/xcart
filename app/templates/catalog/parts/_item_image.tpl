
{*{set $image = $model->images->limit(1)->get()}*}
{set $image = $model->preview->limit(1)->get()}
{if $image!}
    {set $img_url = "//cdn." ~ $.getSite->getBaseDomain() ~ $image->getURL()}
    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+P+/HgAFhAJ/wlseKgAAAABJRU5ErkJggg=="
         data-src="{$img_url}"
         width="{$image->image_x}"
         height="{$image->image_y}"
         alt="{$model->getFrontendName()|escape}"
         class="lazy lazy-img"
         itemscope
         itemprop="image">
{else}
    <img src="//via.placeholder.com/200x200/efefef/a6a6a6/?text=No+image" alt="Image not available">
{/if}