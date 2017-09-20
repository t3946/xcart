{extends 'group/layouts/group_list.tpl'}

{block 'group_list'}
    <form method="post">
        <table class="product_group" width="100%" cellspacing="1" cellpadding="3" data-storefront="{$sfid}">
            <tr class="TableHead">
                <td class="tree"></td>
                <td class="checkbox"></td>
                <td class="phrase">Group phrase</td>
                <td class="count">Products</td>
            </tr>

            {include 'group/product/group_rows.tpl' brands = $brands}

        </table>
        {include 'group/form/buttons.tpl'}
    </form>
    {include 'group/product/new_group.tpl' url='product:group'}
{/block}

{block 'js'}
    {parent}
    <script type="text/javascript">
        (function () {
            $('input.button[name=group]').on('click', function (e) {
                e.preventDefault();
                var arrP = [],
                    arrDist = [];

                var selected_block = $('.product_group tr[data-selected=true]').first();
                var selected_phrase = selected_block.find('.tree_cell').data('group-phrase');
                var selected_products = $('.product_group .products tr')
                    .has('td input:checked')
                    .clone()
                    .filter(function (i) {
                        if (arrP.indexOf($(this).data('product-id')) < 0) {
                            arrP.push($(this).data('product-id'));
                            return true;
                        } else {
                            return false;
                        }
                    }).each(function () {
                        if (arrDist.indexOf($(this).data('manufacturer-id')) < 0) {
                            arrDist.push($(this).data('manufacturer-id'));
                        }
                    });

                if (arrDist.length > 1) {
                    alert('You trying to group products of different distributors');
                    return;
                }

                selected_block.css('opacity', 0.4).next('.group-detail').css('opacity', 0.4);

                $('#new-group')
                    .find('textarea.description').html(selected_products.first().data('description')).end()
                    .find('.selected-products tr:not(.TableHead)').remove().end()
                    .find('.selected-products tr.TableHead').after(selected_products).end()
                    .find('#o-group-title').val(selected_phrase).end()
                    .find('#o-group-truncate').val(selected_phrase).end()
                    .find('#o-group-option').val(selected_phrase).end()
                    .find('#o-group-sku').val(selected_products.first().data('prefix')).end()
                    .find('#o-group-manufacturer').val(arrDist[0]).end()
                    .find('#o-group-storefront').val($('.product_group').data('storefront')).end()
                    .mmodal({
                        width: 1008,
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
                                    products: arrP
                                }, function (data) {
                                    $('.mmodal-content #o-category-selector').html(data)
                                }
                            );
                        },
                        onSubmit: function (s) {
                            var self = this;
                            var $form = $(s).closest('form');
                            var $data = $form.serialize();
                            $form.off();
                            self.close();
                            $.ajax({
                                url: $form.attr('action'),
                                type: "post",
                                cache: false,
                                data: $data,
                                success: function (data, textStatus, jqXHR) {
                                    for (var p in arrP) {
                                        $('.product_group').find('tr[data-product-id=' + arrP[p] + ']').remove();
                                    }
                                    selected_block.css('opacity', 1).find('.tree_cell').click();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $.mnotify({
                                        title: 'Group product error',
                                        message: jqXHR.responseText
                                    });
                                    return self.close();
                                }
                            });
                        },
                        onAfterClose: function () {
                            tinymce.remove();
                        }
                    });
            });
        })();

    </script>
{/block}