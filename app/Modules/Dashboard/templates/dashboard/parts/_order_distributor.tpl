<li>
    <div class="row">
        <div class="columns large-4">
            <label for="o_distributor">Distributors:</label>
        </div>

        <div class="columns large-5">
            <select name="search[order][distributor][]" id="o_distributor" class="big" multiple>

                {foreach $distributors as $distributor}
                    <option value="{raw $distributor.manufacturerid}" {if $form_data.form_data.order.distributor && $distributor.manufacturerid in list $form_data.form_data.order.distributor}selected{/if}>{raw $distributor.manufacturer}</option>
                {/foreach}
            </select>
        </div>

        <div class="columns large-3 not">
            <input type="checkbox" value="1" name="search[not][order][distributor]" id="nod" {if $form_data.not.order.distributor}checked{/if}>
            <label for="nod">Invert selection</label>
        </div>
    </div>
</li>