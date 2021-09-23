<div id="product_shipping_content"></div>
<button id="calculate_product_shipping">Calculate shipping</button>

{literal}
    <script type="text/javascript">
        $('#calculate_product_shipping').on('click', function () {
            $('#product_shipping_content').html('Please wait...');
            var url_calculate_shipping = '{/literal}{$url_calculate_shipping}{literal}';
            $.get(url_calculate_shipping, {}, function (data) {
                    $('#product_shipping_content').html(data);
                }
            );
        })
    </script>
{/literal}