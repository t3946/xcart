{* $Id: menu_manufacturers.tpl,v 1.5.2.2 2008/07/15 12:07:41 ferz Exp $ *}
{if $manufacturers ne ''}
<select name="cmb_manufacturers" style="width:170px;">
<option value="">...</option>
{section name=mid loop=$manufacturers}
<option value="{$manufacturers[mid].manufacturerid}" {if $manufacturers[mid].manufacturerid eq $manid}selected{/if}>{$manufacturers[mid].manufacturer}</option>
{/section}
</select>
{/if}
