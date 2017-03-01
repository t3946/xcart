{extends 'dashboard/layouts/menu_layout.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Actions'}
        <a href="{url 'dashboard:create_filter'}" class="button">Create new filter</a>
    {/smarty_admin_block}

    {smarty_admin_block name= 'Filters'}
        <div class="admin-dashboard-filters-list">

            {include 'dashboard/admin/dashboard_group.tpl' models=$models|get_filtered:null group=null title='Not in group'}

            {foreach $groups as $group}
                {include 'dashboard/admin/dashboard_group.tpl' models=$models|get_filtered:$group->id group=$group->id title=$group}
            {/foreach}
        </div>
    {/smarty_admin_block}
{/block}

{block 'js'}
    {parent}
    <script>
        $('.admin .admin-dashboard-filters-list').tablePositions({
            draggableSelector: '.button',
            dropSelector: '.container',

            onMove: function (el, to) {


                console.log({
                    position_row: $(to).data('row'),
                    position_column: $(to).data('col'),
                    group_id: $(to).data('group'),
                    id: $(el).data('id')
                });
                var def = $.Deferred();
                $.ajax({
                    type: 'POST',
                    url: '{url 'dashboard:sort_filters'}',
                    data: {
                        position_row: $(to).data('row'),
                        position_column: $(to).data('col'),
                        group_id: $(to).data('group'),
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