<script type="text/javascript">
//<![CDATA[
$(function() {ldelim}
  $('#{$prefix}container').tabs();
{rdelim});
//]]>
</script>

<div id="{$prefix}container">

  <ul>
  {foreach from=$tabs item=tab key=ind}
{*    {inc value=$ind assign="ti"} *}
    <li><a href="{if $tab.url}{$tab.url|amp}{else}#{$prefix}{$tab.anchor|default:$ti}{/if}">{$tab.title}</a></li> 
  {/foreach}
  </ul>

  {foreach from=$tabs item=tab key=ind}
    {if $tab.tpl}
{*      {inc value=$ind assign="ti"} *}
      <div id="{$prefix}{$tab.anchor|default:$ti}">
	{$tab.tpl}
      </div>
    {/if}
  {/foreach}

</div>

