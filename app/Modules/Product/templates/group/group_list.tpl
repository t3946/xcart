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
                            .after($('<tr>')
                                .html($('<td colspan="4" class="level" data-level="' + level + '">').html($('<table cellpadding="3" cellspacing="1" width="100%">').html(data))));
                        $('.product_group').css('opacity', 1);
                    }
                );
            } else {
                th.closest('tr').next('tr').hide().remove();
            }
        }).on('change', '.tree-checkbox', function () {
            var th = $(this);
            th.closest('tr').next('tr').find('.products input[type=checkbox]').attr('checked', th.is(':checked'));
        });

        $('input.button[name=group]').on('click', function(e){
            e.preventDefault();
            $('#new-group').show().mmodal({
                width: 1008
            });
        });

    </script>
{/block}