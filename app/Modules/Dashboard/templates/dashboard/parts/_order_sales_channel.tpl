<li>
    <div class="row">
        <div class="columns large-4">
            <label for="o_source">Order sales channel:</label>
        </div>

        <div class="columns large-5">
            <select type="text" name="search[order][source][]" id="o_source" class="big" multiple>
                {foreach $sources as $code => $name}
                    <option value="{$code}" {if $form_data.order.source && $code in list $form_data.order.source}selected{/if}>{$name}</option>
                {/foreach}
            </select>
        </div>

        <div class="columns large-3 not">
            <input type="checkbox" value="1" name="search[not][order][source]" id="nos" {if $form_data.not.order.source}checked{/if}>
            <label for="nos">Invert selection</label>
        </div>
    </div>
</li>