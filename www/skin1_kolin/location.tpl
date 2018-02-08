{* $Id: location.tpl,v 1.14 2005/11/17 06:55:36 max Exp $ *}
{if $location}
<br>
{*<font class="NavigationPath">*}
{strip}
{section name=position loop=$location}
	{if $location[position].1 ne "" }
		{if $cat_for_itemscope[position] ne ""}
			<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display: inline;">
		{/if}
		<a href="{$location[position].1|amp}" class="NavigationPath" {if $cat_for_itemscope[position] ne ""}itemprop="url"{/if}>
	{/if}
	{if $cat_for_itemscope[position] ne ""}
		<span itemprop="title">
	{/if}
	{$location[position].0}
        {if $cat_for_itemscope[position] ne ""}
                </span>
        {/if}
	{if $location[position].1 ne "" }
		</a>
		{if $cat_for_itemscope[position] ne ""}
			</div>
		{/if}
	{/if}
	{if not %position.last%}{$lng.lbl_location_delimiter}{/if}
{/section}
{/strip}
{*</font>*}
<br />
{/if}
