{extends 'group/layouts/group_layout.tpl'}

{block 'heading'}
    <h1 align="center">
        Group products
    </h1>
{/block}

{block 'content'}

    {smarty_admin_block name='Group Products'}
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
            {block 'group_list'}

            {/block}

        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
    {/smarty_admin_block}
{/block}

{block 'js'}
    <script src="/skin1_kolin/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
    <script type="text/javascript">

        $(document).on('click', '.thumbnails > img:not(.not)', function() {
            $(this).after($(this).siblings('img.not').first().removeClass('not'));
            $(this).siblings('img').last().after($(this).addClass('not'));
        });

        $('.product_group')
            .on('click', '.tree_cell', function () {
                var th = $(this);

                if (th.hasClass('single')) {
                    $('.product_group .tree_cell.open').not($(this)).removeClass('open').closest('tr').attr('data-selected', false).next('tr').hide().remove();
                }

                th.toggleClass('open');

                if (th.hasClass('open')) {
                    var level = parseInt(th.data('level')) + 1;

                    $('.product_group').css('opacity', 0.4);

                    $.get(
                        th.data('url'),
                        {
                            level: level,
                            group_phrase: th.data('group-phrase')
                        },
                        function (data) {
                            var tr = th.closest('tr');
                                tr.find('.checkbox').html($('<input class="tree-checkbox" type="checkbox">')).end()
                                .after($('<tr class="group-detail">')
                                .html($('<td colspan="'+tr.find('td').length+'" class="level" data-level="' + level + '">').html($('<table cellpadding="3" cellspacing="1" width="100%">').html(data.html))));
                            $('.product_group').css('opacity', 1);
                            th.data('group-phrase', data.group_phrase);
                            tr.find('.phrase').text(data.group_phrase);
                        }
                    );
                } else {
                    var row = th.closest('tr');
                    row.attr('data-selected', false).next('tr').hide().remove();
                    row.find('.tree-checkbox').prop('checked', false);
                }
            })
            .on('change', '.tree-checkbox', function () {
                var th = $(this);
                th.closest('tr').next('tr').find('.products input[type=checkbox]').prop('checked', th.is(':checked')).change();
            })
            .on('change', '.products input[type=checkbox]', function () {
                var th = $(this);
                th.closest('.group-detail').prev('tr').attr('data-selected', th.is(':checked'));
            });

        $(document)
            .on('change', '.mmodal-content .group-truncate-checkbox', function () {
                $('.mmodal-content')
                    .find('#o-group-truncate')
                    .prop('disabled', !$(this).is(':checked'))
                    .change();
            })
            .on('change keyup', '.mmodal-content #o-group-truncate', function () {
                var regex = new RegExp("^" + $(this).val(), ""),
                    checkbox = $('.mmodal-content').find('.group-truncate-checkbox');

                $('.mmodal-content .selected-products .product-title').find('a').each(function () {
                    if (checkbox.is(':checked')) {
                        $(this).text($(this).closest('td').data('product').replace(regex, ''));
                    } else {
                        $(this).text($(this).closest('td').data('product'));
                    }
                })
            });
    </script>
{/block}