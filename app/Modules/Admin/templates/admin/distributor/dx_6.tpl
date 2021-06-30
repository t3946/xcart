{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block is_show_title=false}
        <div>
            Enter the address of the main distributor warehouse.
            <br/>
            <br/>
        </div>
        {include 'admin/distributor/dx.tpl'}
    {/smarty_admin_block}
{/block}