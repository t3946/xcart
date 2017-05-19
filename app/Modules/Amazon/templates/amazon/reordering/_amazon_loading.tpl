<form action="{url 'amazon:index'}" method="post">
    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_cost_to_us">Assortment:</label>
                </div>

                <div class="columns large-6">
                    <select name="batch_assortment">
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                    </select>
                </div>
            </div>
        </li>
    </ul>
    <input type="submit" name="calculate_shipping" value="Create Amazon reorder batch" />
</form>