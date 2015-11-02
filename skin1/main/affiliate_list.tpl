{* $Id: affiliate_list.tpl,v 1.5.2.3 2005/08/05 06:59:47 max Exp $ *}
{math assign="next_level" equation="x+1" x=$level}
{count assign="count" value=$affiliates}
{math assign="count" equation="x-1" x=$count}
<TABLE cellspacing="0" cellpadding="0" border="0" width="100%">
{foreach from=$affiliates item=v key=k}
{math assign="level_delta" equation="y-x+1" x=$parent_affiliate.level y=$v.level}
<TR height="19">
	{if $type eq 1}<TD width="19" valign="middle"{if $k < $count} background="{$ImagesDir}/tree_v.gif"{/if}><IMG src="{$ImagesDir}/tree_{if $k >= $count}end{else}point{/if}.gif" width="19" border="0"></TD>{/if}
	<TD nowrap
	{if $type eq 1}>&nbsp;{if $usertype ne 'B'}<A href="user_modify.php?user={$v.login|escape:"url"}&usertype=B">{if $level_delta <= $config.XAffiliate.partner_max_level}<B>{/if}{$v.firstname} {$v.lastname}{if $level_delta <= $config.XAffiliate.partner_max_level}</B>{/if}</A>{else}{if $level_delta <= $config.XAffiliate.partner_max_level}<B>{/if}affiliate (level: {$level_delta}){if $level_delta <= $config.XAffiliate.partner_max_level}</B>{/if}{/if}
	{elseif $type eq 2} align="right" valign="middle">{include file="currency.tpl" value=$v.sales|default:0}
	{elseif $type eq 3} align="right" valign="middle">{include file="currency.tpl" value=$v.childs_sales}
	{/if}
	</TD>
</TR>
{if $v.childs ne ''}
<TR>
	{if $type eq 1}<TD width="19"{if $k < $count} background="{$ImagesDir}/tree_v.gif">{else}><IMG src="{$ImagesDir}/spacer.gif" width="19" border="0">{/if}</TD>{/if}
	<TD colspan="2">{include file="main/affiliate_list.tpl" affiliates=$v.childs level=$next_level type=$type}</TD>
</TR>
{/if}
{/foreach}
</TABLE>
