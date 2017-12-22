{*
$Id: top_links.tpl 63 2012-10-30 11:56:13Z skot $ 
vim: set ts=2 sw=2 sts=2 et:
*}
{if $tabs}
  <div class="tabs-menu">
    <div data-role="navbar" data-iconpos="right">
      <ul>
        {foreach from=$tabs item=tab key=ind}
          {inc value=$ind assign="ti"}
          <li>
            <a href="{if $tab.url}{$current_location}/{$tab.url|amp}{else}#{$prefix}{$ti}{/if}"{if $tab.selected} class="ui-btn-active ui-state-persist"{/if} rel="external">{$tab.title|wm_remove|escape}</a>
          </li>
        {/foreach}
      </ul>
    </div>
  </div>
{/if}