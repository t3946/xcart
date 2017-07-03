<div class="filter_price">
    <div class="inputs">
        <span class="currency">US$</span>

        <input type="number"
               id="filter_{$key}_min"
               class="min fv-group-{$key}"
               name="filter[{$key}][min]"
               min="{$values.prices.min}"
               max="{$values.prices.max}"
               step="{$values.prices.step}"
               value="{$values.selected.min}"/>
        &mdash;
        <input type="number"
               id="filter_{$key}_max"
               class="max fv-group-{$key}"
               name="filter[{$key}][max]"
               min="{$values.prices.min}"
               max="{$values.prices.max}"
               step="{$values.prices.step}"
               value="{$values.selected.max}"/>
    </div>



    <div class="range_wrapper">
        <div class="prices">
            <span class="price min">{$values.prices.min}</span>
            <span class="price max">{$values.prices.max}</span>
        </div>

        <div id="filter_{$key}_range" class="price_range"></div>
    </div>
</div>

{use 'base.tpl'}
{block 'js'}
{parent}

<script type="text/javascript">
    $(document).ready(function(){
        var price_min = {$values.prices.min};
        var price_max = {$values.prices.max};
        var start_min = {$values.selected.min};
        var start_max = {$values.selected.max};
        var keypressSlider = document.getElementById('filter_{$key}_range');
        var inputs = [document.getElementById('filter_{$key}_min'), document.getElementById('filter_{$key}_max')];

        {ignore}
        new window.FilterPriceSlider(keypressSlider, inputs, {
            start: [start_min, start_max],
            step: 1,
            range: {
                min:price_min,
                max:price_max
            }
        });
        {/ignore}
    });
</script>
{/block}