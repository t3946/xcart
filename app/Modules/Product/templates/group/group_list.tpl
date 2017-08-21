{extends 'group/layouts/group_list.tpl'}

{block 'group_list'}
    <form method="post">
        <table class="product_group" width="100%" cellspacing="1" cellpadding="3">
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
    {include 'group/product/new_group.tpl'}
{/block}

{block 'js'}
    <script src="/skin1_kolin/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.product_group').on('click', '.tree_cell', function () {
            var th = $(this);
            th.toggleClass('open');

            if (th.hasClass('open')) {
                $('.product_group').css('opacity', 0.4);
                var level = parseInt(th.data('level')) + 1;
                $.get(
                    th.data('url'),
                    {
                        level: level,
                        group_phrase: th.data('group-phrase'),
                        ajax: true
                    },
                    function (data) {
                        th.closest('tr')
                            .find('.checkbox').html($('<input class="tree-checkbox" type="checkbox">')).end()
                            .after($('<tr class="group-detail">')
                            .html($('<td colspan="4" class="level" data-level="' + level + '">').html($('<table cellpadding="3" cellspacing="1" width="100%">').html(data))));
                        $('.product_group').css('opacity', 1);
                    }
                );
            } else {
                th.closest('tr').next('tr').hide().remove();
            }
        }).on('change', '.tree-checkbox', function () {
            var th = $(this);
            th.closest('tr').next('tr').find('.products input[type=checkbox]').prop('checked', th.is(':checked')).change();
        }).on('change', '.products input[type=checkbox]', function(){
            var th = $(this);
            th.closest('.group-detail').prev('tr').attr('data-selected', th.is(':checked'));
        });

        $('input.button[name=group]').on('click', function (e) {
            e.preventDefault();

            var selected_phrase = $('.product_group tr[data-selected=true]').first().find('.tree_cell').data('group-phrase');

            var selected_products = $.uniqueSort($('.product_group .products tr').has('td input:checked').clone());

            $('#new-group')
                .find('textarea.description').html(selected_products.first().data('description')).end()
                .find('.selected-products tr:last-child').after(selected_products).end()
                .find('#o-group-title').val(selected_phrase).end()
                .find('#o-group-truncate').val(selected_phrase).end()
                .mmodal({
                    width: 1008,
                    onBeforeStart: function () {
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

                    },
                    onAfterClose: function () {
                    }
                });
        });


    </script>
{/block}