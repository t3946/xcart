{*
$Id: tabs_menu.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $checkout_tabs}
  <div class="tabs-menu">
    <div data-role="navbar" data-iconpos="right">
      <ul>
        {foreach item=step name=checkout_tabs from=$checkout_tabs}
          <li>
            <a data-icon="arrow-r" href="{if $step.link ne "" and $step.selected_before}{$current_location}/{$step.link|amp}{else}#{/if}"{if $step.selected eq "Y"} class="ui-btn-active ui-state-persist"{/if}{if !($step.link ne "" and $step.selected_before)} class="ui-disabled"{/if}>{$step.title}</a>
          </li>
        {/foreach}
      </ul>
    </div>
  </div>
{/if}