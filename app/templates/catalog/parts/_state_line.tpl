<section class="products_state_line pcont">
    <div class="row">
        <div class="columns large-3 show-for-large">
            <div class="page_count_wrap">
                {*{insert 'catalog/parts/_page_count.tpl'}*}
            </div>
        </div>
        <div class="columns small-12 large-9">
            <div class="actions">
                <div class="action_group">

                    {if !$hide_filter_button}
                    <div class="action_block  filter">
                        <a class="action_button filter" href="#filter_form" data-modal-class="filter default">
                            <span class="action">
                                Filter
                            </span>
                        </a>
                    </div>
                    {/if}
                    {if !$hide_sort}
                        <div class="action_block sort">
                            <div class="action_button sort">
                                <span class="action">
                                    Sort by
                                </span>
                                <span class="active_value show-for-large">
                                    {foreach $sort_arr as $key=>$item}
                                        {if $key == $sort}
                                            {$item}
                                        {/if}
                                    {/foreach}
                                </span>

                            </div>
                            <ul class="options no-bullet">
                                {foreach $sort_arr as $key=>$item}
                                    <li data-value="{$key}" {if $sort == $key} class="active"{/if}>{$item}</li>
                                {/foreach}
                            </ul>
                        </div>
                    {/if}

                </div>
                <div class="action_block view">
                    <span class="show-for-large">
                        View as
                    </span>
                    <a href="#" class="tile-view {if $.isBot}active{/if}" data-value="tile-view"></a>
                    <a href="#" class="list-view" data-value="list-view"></a>
                </div>
            </div>
        </div>
    </div>

</section>