{extends 'group/layouts/group_layout.tpl'}

{block 'heading'}
    <h1 align="center">
        Products group
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