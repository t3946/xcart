{extends "admin/base.tpl"}

{block 'wrapper_block_class'}admin{/block}

{block 'breadcrumbs'}{/block}

{block 'content'}
    {smarty_admin_block name='Filter'}
        <div class="{block 'page_class'}create{/block}">
            <form action="{$.request->getUrl()}" enctype="multipart/form-data" method="get">
                <div class="form-data">
                    {include 'admin/form/_form.tpl'}
                    <div class="row" style="margin-top: 15px;">
                        <div class="column">
                            <button name="filter" type="submit" value="filter">Filter</button>
                            <button id="clear_filter" name="clear" type="submit" value="clear">Clear filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <script>
            (() => {
                document.getElementById('clear_filter')
                    .addEventListener('click', e => {
                        e.preventDefault()
                        location.href = location.href.split('?')[0]
                    })
            })()
        </script>
    {/smarty_admin_block}
{/block}
