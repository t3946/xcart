{extends 'dashboard/layouts/menu_layout.tpl'}
{block 'heading'}
    <h1 align="center">Customer Care dashboard.</h1>
{/block}

{block 'content'}
    <div class="smarty-admin-block">
        <div class="tabs">
            <div class="tabs-title">
                <a href="#my_dashboard" class="link {if $myModels|count > 0}active{/if}">My dashboard</a>
                <a href="#dashboard" class=" link {if $myModels|count == 0}active{/if}">Order dashboard</a>
            </div>

            <div class="tabs-content">
                <div class="tab my_dashboard white-back orange-border content-block {if $myModels|count > 0}active{/if}" id="my_dashboard">
                    {include 'dashboard/dashboard_group.tpl' models=$myModels my_position=true row_col=['col'=> $row_col.col, 'row' => 25]}
                </div>
                <div class="tab white-back orange-border content-block {if $myModels|count == 0}active{/if}" id="dashboard">
                    {include 'dashboard/dashboard_group.tpl' models=$models|get_filtered:null group=null title='Not in group'}

                    {foreach $groups as $group}
                        {include 'dashboard/dashboard_group.tpl' models=$models|get_filtered:$group->id group=$group->id title=$group}
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
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