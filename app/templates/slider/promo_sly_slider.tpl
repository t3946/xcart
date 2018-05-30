<div id='{$slider_name}' class="promo_slider sly_slider visibility__hidden">
    <div class="frame">
        <ul class="clearfix">
            {foreach $slides as $slide}
                <li class="float-left">
                    {include 'slider/_slide_cover_1.tpl' slide=$slide}
                </li>
            {/foreach}
        </ul>
    </div>
    {if $slides|length > 1}
    <ul class="pages">
        {set $length = $slides|length}
        {foreach 1..$length as $key}
            <li data-target="{$key}">{$key}</li>
        {/foreach}
    </ul>
    {/if}
</div>

{add_asset_block type="js"}
    <script>
        window.app.afterReady.push(function(){
            var slider_name = '{$slider_name}';
            var query = '#{$slider_name} .frame';
            var $wrap = $('#{$slider_name}');
            var $frame = $(query);
            {ignore}
            $frame.sly({
                horizontal: 1,
                itemNav: 'basic',
                speed: 300,
                mouseDragging: 1,
                touchDragging: 1,
                pagesBar: $wrap.find('.pages'),
                activatePageOn: 'click'
            });

            $frame.sly('on', 'load', function(){
                $wrap.removeClass('visibility__hidden');
            });

            var funcReload = function() {
                $(query + ' li').width(Math.floor($wrap.width()));
                $frame.sly('reload');
            };

            $(window).on('resize', funcReload);

            funcReload();

            {/ignore}
        });
    </script>
{/add_asset_block}