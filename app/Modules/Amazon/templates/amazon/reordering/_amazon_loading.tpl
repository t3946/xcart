<form action="{url 'amazon:index'}" method="post">
    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_batch_assortment">Assortment:</label>
                </div>

                <div class="columns large-6">
                    <select id="o_batch_assortment" name="batch_assortment">
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_external_link">Teamwork task link:</label>
                </div>

                <div class="columns large-6">
                    <input style="width:100%" id="o_external_link" name="external_link" type="text"/>
                </div>
            </div>
        </li>
    </ul>
    <input type="submit" name="calculate_shipping" value="Create Amazon reorder batch" />
</form>