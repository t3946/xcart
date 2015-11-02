<br />
{capture name=dialog}
{if $productqueries_page_arr ne ""}

{foreach from=$productqueries_page_arr item=v key=k}

<B>Question:</B><br />
From: {$v.username}<br />
Details:<br />
<a href="{$v.url}" target="_blank">{$v.name}</a><br />
{$v.content}<br />
<br />
{if $v.answers ne ""}
	<B>Answers:</B><br />
	{foreach from=$v.answers item=vv key=kk}
		From: {$vv.username}<br />
		Details:<br />
		{$vv.content}<br />
		{if $vv.comments ne ""}
			&nbsp;&nbsp;&nbsp;&nbsp;Comments:<br />
			{foreach from=$vv.comments item=vvv key=kkk}
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From: {$vvv.username}<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Details:<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$vvv.content}<br /><br />
			{/foreach}
			<br />
		{/if}
	{/foreach}
{/if}
<br />
{/foreach}

{else}
no data available
{/if}
{/capture}
{include file="dialog.tpl" title="Answers" content=$smarty.capture.dialog extra='width="100%"'}

