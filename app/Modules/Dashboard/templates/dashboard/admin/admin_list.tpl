{extends 'base/admin.tpl'}
{block 'heading'}
    <h1 align="center">Filters list.
        <a href="{url 'dashboard:create_filter'}" class="button">
            <i class="icon-plus-thin">+</i>
        </a>
    </h1>
{/block}

{block 'content'}
    {smarty_admin_block name= 'Filters'}
        <div class="admin-dashboard-filters-list">

            {include 'dashboard/admin/_dashboard_group.tpl' models=$models|get_filtered:null group=null title='Not in group'}

            {foreach $groups as $group}
                {include 'dashboard/admin/_dashboard_group.tpl' models=$models|get_filtered:$group->id group=$group->id title=$group}
            {/foreach}
        </div>
    {/smarty_admin_block}
{/block}

{block 'js'}
    {parent}
    <script>
        $(function(){
            var url_dashboard_sort = '{url 'dashboard:sort_filters'}';

            {ignore}
            $('.admin .admin-dashboard-filters-list').tablePositions({
                draggableSelector: '.button',
                dropSelector: '.container',

                onMove: function (el, to) {
                    var def = $.Deferred();
                    $.ajax({
                        type: 'POST',
                        url: url_dashboard_sort,
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
            {/ignore}
        })();
    </script>
{/block}