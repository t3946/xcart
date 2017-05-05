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
        function exportTableToCSV($table, filename) {
            var $rows = $table.find('tr:has(td)').not('.no-export'),
                tmpColDelim = String.fromCharCode(11), // vertical tab character
                tmpRowDelim = String.fromCharCode(0), // null character
                colDelim = '","',
                rowDelim = '"\r\n"',
                csv = '"' + $rows.map(function (i, row) {
                        var $row = $(row),
                            $cols = $row.find('td');
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
            if (window.Blob && window.URL) {
                // HTML5 Blob
                var blob = new Blob([csv], { type: 'text/csv;charset=utf8' });
                var csvUrl = URL.createObjectURL(blob);
                $(this)
                    .attr({
                        'download': filename,
                        'href': csvUrl
                    });
            } else {
                // Data URI
                var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
                $(this)
                    .attr({
                        'download': filename,
                        'href': csvData,
                        'target': '_blank'
                    });
            }
        }
        (function(){
            $('input.group-apply').click(function(){
                var fill_val = $(this).siblings('input.group-apply-val');
                $(this).closest('table').find('input.restocking-qty').each(function(){
                    $(this).val(fill_val.val());
                })
            });

            $('.csv-button').click(function(){
                var table =  $(this).closest('table');
                var filename = ' FB-00000' + table.data('batch-id') + table.data('manufacturer-code')+'1.txt';
                var args = [table, filename];
                exportTableToCSV.apply(this, args);
            });
        })();
    </script>
{/block}
