{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name=$section_title}
        <div>
            <b>Here indicate the address of the main distributor warehouse.</b>
            <br/>
            <br/>
        </div>
        {include 'admin/distributor/dx.tpl'}
    {/smarty_admin_block}
{/block}