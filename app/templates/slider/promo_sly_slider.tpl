<div id='{$slider_name}' class="promo_slider sly_slider">
    <div class="frame">
        <ul>
            {foreach $slides as $slide}
                <li>
                    {include 'slider/_slide_cover_1.tpl' slide=$slide}
                </li>
            {/foreach}
        </ul>
    </div>
    {if $data|length > 1}
    <ul class="pages">
        {set $length = $slides|length}
        {foreach 1..$length as $key}
            <li data-target="{$key}">{$key}</li>
        {/foreach}
    </ul>
    {/if}
</div>

{add_asset_block type="js"}
    <script type="text/javascript">
        window.app.afterReady.push(function(){
            var slider_name = '{$slider_name}';
            {ignore}
            $().sly({
                horizontal: 1,
                itemNav: 'basic',
                speed: 300,
                mouseDragging: 1,
                touchDragging: 1
            });

            console.log($('#promo_slider').toElement);
            {/ignore}
        });
    </script>
{/add_asset_block}