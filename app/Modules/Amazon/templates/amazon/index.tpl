{extends 'amazon/layouts/amazon_layout.tpl'}

{block 'heading'}
    <h1 align="center">Amazon reordering</h1>
{/block}


{block 'content'}
    {smarty_admin_block name='Create Amazon reorder batch'}
        {include 'amazon/_errors.tpl'}
        {include 'amazon/reordering/_amazon_loading.tpl'}
    {/smarty_admin_block}

    {smarty_admin_block name='Amazon Batches'}
        {include 'amazon/reordering/_amazon_batches.tpl'}
    {/smarty_admin_block}

{/block}

{block 'js'}
    {parent}
    <script type="text/javascript">
        (function(){
            var url_restocking_batch_delete = '{url 'amazon:batch_delete'}';
            $('.delete-batch').click(function(){
                if (confirm('Are You Sure?')) {
                    var a = $(this),
                    row = a.closest('tr');
                    row.css('opacity', 0.4);
                    $.post(url_restocking_batch_delete, {
                            batch_id: a.data('batch-id')
                        }, function (data) {
                            if (data.status == 'ok') {
                                row.fadeOut();
                            }
                        }, 'json'
                    );
                }
                return false;
            });
        })();
    </script>
{/block}