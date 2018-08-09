<fieldset class="expanded-force" rel="5">
    <legend>Report options</legend>
    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label>Accounting method:</label>
                </div>

                <div class="columns large-6">
                    <div class="columns large-3 padding-small">
                        <input name="search[report][accounting_method]" type="radio" value="" id="accounting_method_accural" {if !$form_data.report.accounting_method}checked{/if}>
                        <label for="accounting_method_accural">Accrual</label>
                    </div>
                    <div class="columns large-3 padding-small">
                        <input name="search[report][accounting_method]" type="radio" value="Cash" id="accounting_method_accural_cash" {if $form_data.report.accounting_method == 'Cash'}checked{/if}>
                        <label for="accounting_method_accural_cash">Cash</label>
                    </div>

                </div>
            </div>
        </li>
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label>Profit margin range:</label>
                </div>

                <div class="columns large-8">
                    <div class="columns large-12">
                        <input name="search[report][profit_margin]" type="radio" value="" id="profit_margin_all" {if !$form_data.report.profit_margin}checked{/if}>
                        <label for="profit_margin_all">Show all orders (look at sales volume)</label>
                    </div>
                    <div class="columns large-12">
                        <input name="search[report][profit_margin]" type="radio" value="profit" id="profit_margin_profit" {if $form_data.report.profit_margin == 'profit'}checked{/if}>
                        <label for="profit_margin_profit">Show orders with profit margin < 100 % (look at profit margin)</label>
                    </div>
                    <div class="columns large-12">
                        <input name="search[report][profit_margin]" type="radio" value="profit15" id="profit_margin_profit15" {if $form_data.report.profit_margin == 'profit15'}checked{/if}>
                        <label for="profit_margin_profit15">Show orders with profit margin ≤</label>
                        <input size="3" type="text" value="15" name="search[report][profit_margin_profit15_edit]" id="profit_margin_profit15_edit"/>
                        <label for="profit_margin_profit15_edit">%</label>
                    </div>
                    <div class="columns large-12">
                        <input name="search[report][profit_margin]" type="radio" value="profit_between" id="profit_margin_profit_between" {if $form_data.report.profit_margin == 'profit_between'}checked{/if}>
                        <label for="profit_margin_profit_between">Show orders with</label>
                        <input size="3" type="text" value="30" name="search[report][profit_margin_profitbetween_start]" id="profit_margin_profitbetween_start"/>
                        <label for="profit_margin_profitbetween_start">% ≤ profit margin <</label>
                        <input size="3" type="text" value="100" name="search[report][profit_margin_profitbetween_end]" id="profit_margin_profitbetween_end"/>
                        <label for="profit_margin_profitbetween_end">%</label>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label>Show Reconciled:</label>
                </div>
                <div class="columns large-8">
                    <div class="columns large-12">
                        <input name="search[report][show_reconciled]" type="checkbox" value="1" id="show_reconciled" {if $form_data.report.show_reconciled}checked{/if}>
                    </div>
                </div>
            </div>
        </li>
        <li {if !$edit}style="display: none"{/if}>
            <div class="row">
                <div class="columns large-4">
                    <label>Group settings:</label>
                </div>
                <div class="columns large-8 shapeshift">
                    <div class="columns large-6 shapeshift-wrapper">
                        <div class="columns large-12 group-drag-box shapeshift-container for-save" data-param-name="group_settings">
                            {if $form_data.report.group_settings}
                                {foreach $form_data.report.group_settings as $key => $group_model}
                                    <div data-index="{$key}" data-model="{$group_model}">{$group_models[$group_model].name}</div>
                                {/foreach}
                            {/if}
                        </div>
                    </div>
                    <div class="columns large-6 shapeshift-wrapper">
                        <div class="columns large-12 group-drag-box shapeshift-container">
                            {foreach $group_models as $key => $group_model index=$index}
                                {if !$form_data.report.group_settings || $key not in list $form_data.report.group_settings}
                                    <div data-index="{$index}" data-model="{$key}">{$group_model.name}</div>
                                {/if}
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li {if !$edit}style="display: none"{/if}>
            <div class="row">
                <div class="columns large-4">
                    <label>Aggregate settings:</label>
                </div>
                <div class="columns large-8 shapeshift">
                    <div class="columns large-6 shapeshift-wrapper">
                        <div class="columns large-12 group-drag-box shapeshift-container for-save" data-param-name="aggregate_settings">
                            {if $form_data.report.aggregate_settings}
                                {foreach $form_data.report.aggregate_settings as $key => $aggregate}
                                    <div data-index="{$key}" data-model="{$aggregate}">{$aggregate_settings[$aggregate].name}</div>
                                {/foreach}
                            {/if}
                        </div>
                    </div>
                    <div class="columns large-6 shapeshift-wrapper">
                        <div class="columns large-12 group-drag-box shapeshift-container">
                            {foreach $aggregate_settings as $key => $aggregate index=$index}
                                {if !$form_data.report.aggregate_settings || $key not in list $form_data.report.aggregate_settings}
                                    <div data-index="{$index}" data-model="{$key}">{$aggregate.name}</div>
                                {/if}
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label>Report comments:</label>
                </div>
                <div class="columns large-8">
                    <div class="columns large-12">
                        <textarea style="width:100%" name="search[report][comment]" id="report_comment">{$form_data.report.comment|strip:false}</textarea>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</fieldset>