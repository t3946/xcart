{extends 'dashboard/layouts/menu_layout.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='My dashboard'}
        <div class="my_dashboard">
            {include 'dashboard/dashboard_group.tpl' models=$myModels title='My Dashboard' my_position=true}
        </div>
    {/smarty_admin_block}

    {smarty_admin_block name='Order dashboard'}
        {include 'dashboard/dashboard_group.tpl' models=$models|get_filtered:null group=null title='Not in group'}

        {foreach $groups as $group}
            {include 'dashboard/dashboard_group.tpl' models=$models|get_filtered:$group->id group=$group->id title=$group}
        {/foreach}

    {/smarty_admin_block}
{/block}

{block 'js'}
    {parent}
    <script>
        $(document).dashboard({
            ajax: {
                url: '{url 'dashboard:index'}'
            }
        });

        $('.dashboard-filters.index a[data-id]').majaxtooltip({
            onAfterSubmit: function() {
                this.setContent("<div class='load'></div>")
            },
            onAfterSuccess: function() {
                $.mnotify({
                    title: '"My dashboard" changed',
                    message: 'Refresh the page to display\\hide the elements'
                });
            }
        });

        $('.my_dashboard .dashboard-filters ').tablePositions({
            draggableSelector: '.button, .empty',
            dropSelector: '.container',

            onMove: function (el, to) {
                var def = $.Deferred();
                $.ajax({
                    type: 'POST',
                    url: '{url 'dashboard:sort_my_filters'}',
                    data: {
                        position_row: $(to).data('row'),
                        position_column: $(to).data('col'),
                        id: $(el).data('id')
                    },
                    success: function (data) {
                        if (data) {
                            $.mnotify({
                                title: 'Position saved',
                                message: data.message
                            });

                            def.resolve(true, data);
                        }
                        def.reject(false);
                    },
                    error: function () {
                        def.reject(false);
                    }
                });

                return def.promise();
            }
        });

    </script>
{/block}