<li>
    <div class="row">
        <div class="columns large-4">
            <label for="o_date">Order date range:</label>
        </div>

        <div class="columns large-5">
            <input type="text" name="search[order][date]" id="o_date"
                   value="{$form_data.order.date}"
                   data-range="true"
                   data-toggle-selected="false"
                   data-multiple-dates-separator=" - "
                   data-language="en"
                   data-clear-button="1"
                   class="datepicker-here big">

            <a href="#help-dates" class="mmodal">
                <i class="fa fa-question-circle pointer" title="Click me!"></i>
            </a>

            <div class="templates as_a date_templates">
                <span data-range="this_month">This month</span>
                <span data-range="today">Today</span>
                <span data-range="last_31">Last 31 days</span>
                <span data-range="last_7">Last 7 days</span>
                <span data-range="clear">[ Clear ]</span>
            </div>
        </div>
        <div class="columns large-3 not">
            <input type="checkbox" value="1" name="search[not][order][date]" id="nod" {if $form_data.not.order.date}checked{/if}>
            <label for="nod">Invert selection</label>
        </div>
    </div>
</li>