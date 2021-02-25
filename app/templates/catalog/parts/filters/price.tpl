<div class="filter_price">
    <div class="inputs">
        <span class="currency">{$site_currency->symbol_prefix}{$site_currency}</span>

        <input type="number"
               id="filter_{$key}_min"
               class="min fv-group-{$key}"
               name="filter[{$key}][min]"
               min="{$values.prices.min}"
               max="{$values.prices.max}"
               step="{$values.prices.step}"
               value="{$values.selected.min|htmlentities}"/>
        &mdash;
        <input type="number"
               id="filter_{$key}_max"
               class="max fv-group-{$key}"
               name="filter[{$key}][max]"
               min="{$values.prices.min}"
               max="{$values.prices.max}"
               step="{$values.prices.step}"
               value="{$values.selected.max|htmlentities}"/>
    </div>



    <div class="range_wrapper">
        <div class="prices">
            <span class="price min">{$values.prices.min}</span>
            <span class="price max">{$values.prices.max}</span>
        </div>

        <div id="filter_{$key}_range" class="price_range"></div>
    </div>
</div>


{add_asset_block type="js"}
<script>
    window.app.afterReady.push(function(){
        const price_min = {$values.prices.min|floatval};
        const price_max = {$values.prices.max|floatval};
        const start_min = {$values.selected.min|floatval};
        const start_max = {$values.selected.max|floatval};
        const keypressSlider = document.getElementById('filter_{$key}_range');
        const inputs = [document.getElementById('filter_{$key}_min'), document.getElementById('filter_{$key}_max')];

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
{/add_asset_block}