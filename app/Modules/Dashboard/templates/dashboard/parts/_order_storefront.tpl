<li>
    <div class="row">
        <div class="columns large-4">
            <label for="o_sf">Storefront:</label>
        </div>

        <div class="columns large-5">
            <select name="search[order][storefront][]" id="o_sf" class="big" multiple>
                {foreach $storefronts as $id => $name}
                    <option value="{$id}" {if $form_data.order.storefront && $id in list $form_data.order.storefront}selected{/if}>
                        {$name}
                    </option>
                {/foreach}
            </select>
        </div>

        <div class="columns large-3 not">
            <input type="checkbox" value="1" name="search[not][order][storefront]" id="nosf" {if $form_data.not.order.sf}checked{/if}>
            <label for="nosf">Invert selection</label>
        </div>
    </div>
</li>