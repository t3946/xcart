{extends 'amazon/layouts/amazon_layout.tpl'}

{block 'heading'}
    <h1 align="center">Amazon reordering</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Products Filter'}
        {include 'amazon/reordering/_filter_products.tpl'}
    {/smarty_admin_block}

    {smarty_admin_block name='Products for amazon reordering'}
    {foreach $amazon_products as $distributor => $products}
        <fieldset {if $amazon_products@first}class="expanded"{/if}>
            <legend>{$distributor} ({count($products)})</legend>
            {include 'amazon/reordering/_distributor_products.tpl'}
        </fieldset>
    {/foreach}
    {/smarty_admin_block}
{/block}

{block 'js'}
    {parent}
    <script type="text/javascript">
        (function(){
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

            function exportTableToCSV($table, filename, selector, header, colDelim, rowDelim) {
                var $rows = $table.find('tr:has(td)').not('.no-export'),
                    tmpColDelim = String.fromCharCode(11), // vertical tab character
                    tmpRowDelim = String.fromCharCode(0), // null character
                    csv = '"' + $rows.map(function (i, row) {
                            var $row = $(row),
                                $cols = $row.find(selector);
                            return $cols.map(function (j, col) {
                                var $col = $(col);
                                var text = '';
                                if($col.find('input').length !== 0){
                                    text = $col.find('input').val();
                                } else {
                                    text = $col.text();
                                }
                                return text.replace(/"/g, '""').trim(); // escape double quotes
                            }).get().join(tmpColDelim);
                        }).get().join(tmpRowDelim)
                            .split(tmpRowDelim).join(rowDelim)
                            .split(tmpColDelim).join(colDelim) + '"';
                exportToFile($(this), filename, header + csv);
            }

            $('input.group-apply').click(function(){
                var fill_val = $(this).siblings('input.group-apply-val');
                $(this).closest('table').find('input.restocking-qty').each(function(){
                    $(this).val(fill_val.val());
                })
            });

            $('.csv-button').click(function(){
                var table =  $(this).closest('table'),
                rowDelim = '"\r\n"',
                colDelim = '","',
                plan_name = 'Excel-00000' + table.data('batch-id') + table.data('manufacturer-code') + '1',
                filename = plan_name + '.txt',
                header = '',
                args = [table, filename, 'td', header, colDelim, rowDelim];
                exportTableToCSV.apply(this, args);
            });

            $('.fba-button').click(function(){
                var table =  $(this).closest('table'),
                rowDelim = '\r\n',
                colDelim = '\t',
                plan_name = 'FB-00000' + table.data('batch-id') + table.data('manufacturer-code') + '1',
                filename = plan_name + '.txt',
                header = 'PlanName'+ colDelim + plan_name + rowDelim
                    + 'ShipToCountry' + colDelim + rowDelim
                    + 'AddressName' + colDelim + rowDelim
                    + 'AddressFieldOne' + colDelim + rowDelim
                    + 'AddressFieldTwo' + colDelim + rowDelim
                    + 'AddressCity' + colDelim + rowDelim
                    + 'AddressCountryCode' + colDelim + rowDelim
                    + 'AddressStateOrRegion' + colDelim + rowDelim
                    + 'AddressPostalCode' + colDelim + rowDelim
                    + 'AddressDistrict' + colDelim + rowDelim
                    + 'MerchantSKU' + colDelim + 'Quantity'+ rowDelim
                    + colDelim + rowDelim,
                args = [table, filename, 'td.fba-required', header, colDelim, rowDelim];
                exportTableToCSV.apply(this, args);
            });
        })();
    </script>
{/block}
