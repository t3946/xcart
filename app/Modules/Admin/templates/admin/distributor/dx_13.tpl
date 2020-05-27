{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name=$section_title}
        {include 'admin/distributor/dx.tpl'}
    {/smarty_admin_block}
{/block}