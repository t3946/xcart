{extends  "catalog/base.tpl"}

{block 'content'}

{/block}

{block 'after-content'}
    <div class="row">
        <div class="small-12 column slider-viewed">
            {set $link}{url 'catalog:viewed'}{/set}
            {include 'slider/base_product_slider.tpl' title="You recently viewed items" link=$link hide=true hide_link=true}
        </div>
    </div>
{/block}