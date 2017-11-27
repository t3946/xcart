{extends 'amazon/layouts/amazon_layout.tpl'}

{block 'heading'}
    <h1 align="center">Amazon reordering</h1>
{/block}

{block 'content'}
    {if $filter_data}
    {smarty_admin_block name=''}
        Some filtering applied to list. Change filters to look at all products.
    {/smarty_admin_block}
    {/if}

    {smarty_admin_block name='Products Filter'}
        {include 'amazon/reordering/_filter_products.tpl'}
    {/smarty_admin_block}

    {smarty_admin_block name='Products for amazon reordering'}
        {if $batch_model}
            {if $batch_model->status == 'done'}
                <form name="amazon_shipping_form" method="post">
                    {foreach $amazon_products as $distributor => $products}
                        <fieldset>
                            <legend style="width: 100%;">
                                <span></span>
                                <span>{$distributor} ({count($products)})</span>
                                <span class="distributor-total" style="float:right"></span></legend>
                                {include 'amazon/reordering/_distributor_products.tpl'}
                        </fieldset>
                    {/foreach}
                    <div class="row">
                        <div class="columns large-12 batch-total" style="text-align: right"></div>
                    </div>
                   {include 'amazon/_buttons.tpl'}
                </form>
            {elseif $batch_model->status == 'processing' || $batch_model->status == 'lock'}
                <div class="row" style="text-align: center">
                    <div class="columns large-12">
                        Processing Amazon data...
                    </div>
                    <div class="columns large-12 load"></div>
               </div>
            {/if}
        {/if}
    {/smarty_admin_block}

{/block}

{block 'js'}
    {parent}
    <script type="text/javascript">
        (function(){
            {if $batch_model}
                var url_restocking_batch_processing = '{url 'amazon:batch_processing'}';
                var url_restocking_batch_processing_check = '{url 'amazon:batch_processing_check'}';
                var url_batch_redirect = '{url 'amazon:batch' id=$batch_id}';
                {if $batch_model->status == 'processing'}
                    $.get(url_restocking_batch_processing, {
                        batch_id: {$batch_id}
                    });
                {/if}
                {if $batch_model->status == 'processing' || $batch_model->status == 'lock'}
                var interval = setInterval(function () {
                    $.get(url_restocking_batch_processing_check, {
                            batch_id: {$batch_id}
                        }, function (data){
                            if (data.status == 'done') {
                                clearInterval(interval);
                                window.location = url_batch_redirect
                            }
                        }, 'json'
                    );
                }, 40000);
                {/if}
            {/if}

            function exportToFile(obj, filename, txt) {
                if (window.Blob && window.URL) {
                    // HTML5 Blob
                    var blob = new Blob([txt], { type: 'text/csv;charset=utf8' });
                    var csvUrl = URL.createObjectURL(blob);
                    obj
                        .attr({
                            'download': filename,
                            'href': csvUrl
                        });
                } else {
                    // Data URI
                    var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(txt);
                    obj
                        .attr({
                            'download': filename,
                            'href': csvData,
                            'target': '_blank'
                        });
                }
            }

            function exportTableToCSV($table, filename, selector, header, footer, colDelim, rowDelim) {
                var $rows = $table.find('tr:has(td)').not('.no-export'),
                    tmpColDelim = String.fromCharCode(11), // vertical tab character
                    tmpRowDelim = String.fromCharCode(0), // null character
                    csv = $rows.map(function (i, row) {
                            var $row = $(row),
                                $cols = $row.find(selector);
                            return $cols.map(function (j, col) {
                                var $col = $(col);
                                var text = '';
                                if($col.find('input').length !== 0){
                                    text = $col.find('input').val();
                                } else {
                                    text = $col.text().replace(/"/g, '""').trim();
                                    if ($col.hasClass('float')){
                                        text = text.match(/\d+\.*\d*/g)
                                    }
                                }
                                return text; // escape double quotes
                            }).get().join(tmpColDelim);
                        }).get().join(tmpRowDelim)
                            .split(tmpRowDelim).join(rowDelim)
                            .split(tmpColDelim).join(colDelim);
                exportToFile($(this), filename, header + csv + footer);
            }

            function recalculateBatchTotals()
            {
                var total = 0;
                $('table.restocking-table').each(function() {
                    total += recalculateDistributorTotal.apply(this, [$(this)]);
                });
                $('.batch-total').text('$'+total.toFixed(2));
            }

            function recalculateDistributorTotal(tbl)
            {
                var total = 0,
                    obj = $(tbl);
                $('tr', obj).not('.no-export').each(function(){
                    var cost_to_us = $(this).find('td.cost-to-us').text().replace('$', ''),
                    qty = $(this).find('input.restocking-qty').val();
                    if (qty > 0) {
                        total += round(parseFloat(cost_to_us) * parseInt(qty),2);
                    }
                });
                total = round(total,2);
                obj.siblings('legend').find('span.distributor-total').text('$'+total.toFixed(2));
                return total;
            }

            function inputValuesChanged(tdo, tdnv)
            {
                console.time('inputValuesChanged start');
                var original = tdo.data('original-value'),
                tdp = tdo.closest('td');
                if (original != tdnv){
                    tdp.addClass('changed');
                } else {
                    tdp.removeClass('changed');
                }
                console.time('inputValuesChanged end');
            }

            $('input.group-apply').click(function(){
                var fill_val = $(this).siblings('input.group-apply-val');
                $(this).closest('table').find('input.restocking-qty').each(function(){
                    $(this).val(fill_val.val());
                    inputValuesChanged($(this), fill_val.val());
                });
                recalculateBatchTotals();
            });

            $('input.restocking-qty').change(function(){
                inputValuesChanged($(this), $(this).val());
                recalculateBatchTotals();
            });

            $('.csv-button').click(function(){
                var table =  $(this).closest('table'),
                rowDelim = '"\r\n"',
                colDelim = '","',
                plan_name = 'Excel-00000' + table.data('batch-id') + table.data('manufacturer-code') + '1',
                filename = plan_name + '.txt',
                header = '"Amazon SKU to load'+ colDelim
                    + 'ASIN' + colDelim
                    + 'UPC' + colDelim
                    + 'Amazon FBA' + colDelim
                    + 'Last order days' + colDelim
                    + 'Items sold last 1m' + colDelim
                    + 'Instock days 3m' + colDelim
                    + 'Items sold last 1m of stock' + colDelim
                    + 'Instock days 1m' + colDelim
                    + 'Orders rate last 1 month' + colDelim
                    + 'Overall Orders rate' + colDelim
                    + 'Cost to us' + colDelim
                    + 'Current Amazon Price' + colDelim
                    + 'Min FBA price' + colDelim
                    + 'AVG comp price' + colDelim
                    + 'ADSa' + colDelim
                    + 'ADSx' + colDelim
                    + 'Dx stock qty' + colDelim
                    + 'Total stock' + colDelim
                    + 'Restocking qty'
                    + rowDelim,
                footer = '"',
                args = [table, filename, 'td', header, footer, colDelim, rowDelim];
                exportTableToCSV.apply(this, args);
            });

            $('.fba-button').click(function(){
                var table =  $(this).closest('table'),
                rowDelim = '\r\n',
                colDelim = '\t',
                plan_name = 'FB-00000' + table.data('batch-id') + table.data('manufacturer-code') + '1',
                filename = plan_name + '.txt',
                footer = '',
                header = 'PlanName'+ colDelim + plan_name + rowDelim
                    + 'ShipToCountry' + colDelim + rowDelim
                    + 'AddressName' + colDelim + table.data('manufacturer-name')  + rowDelim
                    + 'AddressFieldOne' + colDelim + table.data('manufacturer-address')  + rowDelim
                    + 'AddressFieldTwo' + colDelim + rowDelim
                    + 'AddressCity' + colDelim + table.data('manufacturer-city') + rowDelim
                    + 'AddressCountryCode' + colDelim + table.data('manufacturer-country') + rowDelim
                    + 'AddressStateOrRegion' + colDelim + table.data('manufacturer-state') + rowDelim
                    + 'AddressPostalCode' + colDelim + table.data('manufacturer-zip') + rowDelim
                    + 'AddressDistrict' + colDelim + rowDelim
                    + colDelim + rowDelim
                    + 'MerchantSKU' + colDelim + 'Quantity'+ rowDelim,
                args = [table, filename, 'td.fba-required', header, footer, colDelim, rowDelim];
                exportTableToCSV.apply(this, args);
            });

           recalculateBatchTotals();
        })();
    </script>
{/block}
