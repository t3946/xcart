<form action="{url 'amazon:batch' id=$batch_id}" method="GET" class="filter-form">
<ul class="ul-main">
    <li>
        <div class="row">
            <div class="columns large-4">
                <label for="o_cost_to_us">Cost to us:</label>
            </div>

            <div class="columns large-6">
                <input type="text" name="filter[cost_to_us][from]" id="o_cost_to_us" value="{$filter_data.cost_to_us.from}">
                <span>to</span>
                <input type="text" name="filter[cost_to_us][to]" value="{$filter_data.cost_to_us.to}">
            </div>
        </div>
    </li>
    <li>
        <div class="row">
            <div class="columns large-4">
                <label for="o_r_avail">Dx stock qty:</label>
            </div>

            <div class="columns large-6">
                <input type="text" name="filter[r_avail][from]" id="o_r_avail" value="{$filter_data.r_avail.from}">
                <span>to</span>
                <input type="text" name="filter[r_avail][to]" value="{$filter_data.r_avail.to}">
            </div>
        </div>
    </li>
    <li>
        <div class="row">
            <div class="columns large-4">
                <label for="o_restocking_qty">Restocking qty:</label>
            </div>

            <div class="columns large-6">
                <input type="text" name="filter[restocking_qty][from]" id="o_restocking_qty" value="{$filter_data.restocking_qty.from}">
                <span>to</span>
                <input type="text" name="filter[restocking_qty][to]" value="{$filter_data.restocking_qty.to}">
            </div>
        </div>
    </li>
    <li>
        <div class="row">
            <div class="columns large-4">
                <label for="o_restocking_competitive_price">Items with competitive price:</label>
            </div>

            <div class="columns large-6">
                <input type="checkbox" name="filter[restocking_competitive_price]" id="o_restocking_competitive_price" {if $filter_data.restocking_competitive_price}checked="checked"{/if}">
            </div>
        </div>
    </li>
    <li>
        <div class="row">
            <div class="columns large-4">
                <input type="submit" value="Filter">
                <input type="submit" name="filter[reset]" value="Reset">
            </div>
        </div>
    </li>
</ul>
</form>