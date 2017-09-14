{extends 'group/layouts/group_list.tpl'}

{block 'group_list'}
    {if $products}
        {raw $pager}
        <form method="post">
            <table width="100%" class="product_group" data-storefront="{$sfid}" cellspacing="1" cellpadding="3">

                <tr class="TableHead">
                    <td></td>
                    <td></td>
                    <td>Group product</td>
                    <td>Brand</td>
                    <td>Group option</td>
                    <td>New products count</td>
                </tr>
                {foreach $products as $product}
                    {include 'group/product/_group_product.tpl' model=$product index=$products@index}
                {/foreach}
            </table>
            {include 'group/form/buttons.tpl'}
        </form>
        {include 'group/product/new_group.tpl' url='product:group_product' id=$product->productid}

        {raw $pager}
    {else}
        <tr>
            <td align="center" colspan="5">No data found</td>
        </tr>
    {/if}

{/block}

{block 'js'}
    {parent}
    <script type="text/javascript">
        (function () {
            $('input.button[name=group]').on('click', function (e) {
                e.preventDefault();
                var arrDist = [];

                var selected_products = $('.product_group .products tr')
                    .has('td input:checked')
                    .clone()
                    .each(function () {
                        if (arrDist.indexOf($(this).data('manufacturer-id')) < 0) {
                            arrDist.push($(this).data('manufacturer-id'));
                        }
                    });
                var selected_group_product = $('.product_group .tree_cell.open').closest('tr');
                var selected_phrase = selected_group_product.data('group-option');
                if (arrDist.length > 1) {
                    alert('You trying to group products of different distributors');
                    return;
                }

                $('#new-group')
                    .find('textarea.description').html(selected_products.first().data('description')).end()
                    .find('.selected-products tr:not(.TableHead)').remove().end()
                    .find('.selected-products tr.TableHead').after(selected_products).end()
                    .find('#o-group-title').val(selected_group_product.data('title')).end()
                    .find('#o-group-truncate').val(selected_group_product.data('group-mask')).end()
                    .find('#o-group-option').val(selected_phrase).end()
                    .find('#o-group-sku').val(selected_group_product.data('sku')).end()
                    .find('#o-group-manufacturer').val(selected_group_product.data('manufacturer-id')).end()
                    .find('#o-group-storefront').val($('.product_group').data('storefront')).end()
                    .mmodal({
                        width: 1008,
                        onSubmit: function (s) {
                            $(s).closest('form').off().submit();
                        },
                        onAfterOpen: function () {
                            tinymce.init({
                                selector: ".mmodal-content textarea.new_editor",
                                height: 200,
                                resize: "both",
                                plugins: [
                                    "advlist autolink lists link image charmap print preview anchor",
                                    "searchreplace visualblocks code fullscreen",
                                    "insertdatetime media table contextmenu paste"
                                ],
                                toolbar: "insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                                forced_root_block: false,
                                force_br_newlines: true,
                                force_p_newlines: false,
                                convert_urls: false,
                                relative_urls: false
                            });
                            $.get('{url 'product:group_categories'}',
                                {
                                    products: [selected_group_product.data('product-id')]
                                }, function (data) {
                                    $('.mmodal-content #o-category-selector').html(data)
                                }
                            );
                        },
                        onAfterClose: function () {
                            tinymce.remove();
                        }
                    });

            });
        })();
    </script>
{/block}

