<fieldset class="expanded-force" rel="5">
    <legend>Report options</legend>
    <ul class="ul-main">
        {include 'reports/admin/_reports_block_edit.tpl'}
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label>Accounting method:</label>
                </div>

                <div class="columns large-6">
                    <div class="columns large-3 padding-small">
                        <input name="search[order][accounting_method]" type="radio" value="" id="accounting_method_accural" {if !$form_data.order.accounting_method}checked{/if}>
                        <label for="accounting_method_accural">Accrual</label>
                    </div>
                    <div class="columns large-3 padding-small">
                        <input name="search[order][accounting_method]" type="radio" value="Cash" id="accounting_method_accural_cash" {if $form_data.order.accounting_method == 'Cash'}checked{/if}>
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
                        <input name="search[order][profit_margin]" type="radio" value="" id="profit_margin_all" {if !$form_data.order.profit_margin}checked{/if}>
                        <label for="profit_margin_all">Show all orders (look at sales volume)</label>
                    </div>
                    <div class="columns large-12">
                        <input name="search[order][profit_margin]" type="radio" value="profit" id="profit_margin_profit" {if $form_data.order.profit_margin == 'profit'}checked{/if}>
                        <label for="profit_margin_profit">Show orders with profit margin < 100 % (look at profit margin)</label>
                    </div>
                    <div class="columns large-12">
                        <input name="search[order][profit_margin]" type="radio" value="profit15" id="profit_margin_profit15" {if $form_data.order.profit_margin == 'profit15'}checked{/if}>
                        <label for="profit_margin_profit15">Show orders with profit margin ≤</label>
                        <input size="3" type="text" value="15" name="search[order][profit_margin_profit15_edit]" id="profit_margin_profit15_edit"/>
                        <label for="profit_margin_profit15_edit">%</label>
                    </div>
                    <div class="columns large-12">
                        <input name="search[order][profit_margin]" type="radio" value="profit_between" id="profit_margin_profit_between" {if $form_data.order.profit_margin == 'profit_between'}checked{/if}>
                        <label for="profit_margin_profit_between">Show orders with</label>
                        <input size="3" type="text" value="30" name="search[order][profit_margin_profitbetween_start]" id="profit_margin_profitbetween_start"/>
                        <label for="profit_margin_profitbetween_start">% ≤ profit margin <</label>
                        <input size="3" type="text" value="100" name="search[order][profit_margin_profitbetween_end]" id="profit_margin_profitbetween_end"/>
                        <label for="profit_margin_profitbetween_end">%</label>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label>Group settings:</label>
                </div>
                <div class="columns large-8">
                    <div class="columns large-6">
                        <div class="columns large-12 group-drag-box">
                        </div>
                    </div>
                    <div class="columns large-6">
                        <div class="columns large-12 group-drag-box">
                            <div>1</div>
                            <div>2</div>
                            <div>3</div>
                            <div>4</div>
                            <div>5</div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</fieldset>