{extends 'dashboard/layouts/dashboard_layout.tpl'}

{block 'heading'}
    <h1 align="center">Order search</h1>
{/block}

{block 'content'}
    {*{smarty_admin_block name='Search form'}*}
    <div class="row">
        <div class="columns large-12">
            <fieldset class="{if $form_collapse}collapsed-force collapsed{else}expanded{/if}">
                <legend>Order search form</legend>

                <form action="{url 'dashboard:search'}" method="GET">
                    {include 'dashboard/filter_fields.tpl'}


                    <ul class="ul-main">
                        <li>
                            <div class="row">
                                <div class="columns large-4">
                                    <label for="fo_nlist">New order list:</label>
                                </div>

                                <div class="columns large-6">
                                    <input type="hidden" name="search[new_list]" value="0">
                                    <input type="checkbox" name="search[new_list]" id="fo_nlist" value="1" {if $form_data.new_list}checked{/if}>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <button>Search</button>
                    <button name="search[reset]" value="reset">Reset</button>
                </form>
            </fieldset>
        </div>
    </div>
    {*{/smarty_admin_block}*}


    {if count($models) > 0}
        {*{smarty_admin_block name='Search results'}*}
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {if $new_template}
                    {include 'order/orders_list.tpl' orders=$models}
                {else}
                    {include 'order/orders_list_old.tpl' orders=$models}
                {/if}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
        {*{/smarty_admin_block}*}
    {/if}
{/block}

{block 'menu_block'}

{/block}