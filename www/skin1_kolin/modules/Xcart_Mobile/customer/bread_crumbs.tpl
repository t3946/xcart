{*
$Id: bread_crumbs.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $location and $location|@count gt 2 and $main eq 'catalog' or $main eq 'product'}
  <div class="location">
    <div data-role="navbar" data-iconpos="right">
      <ul>
        {foreach from=$location item=l name=location}
          {if $l.1 and not $smarty.foreach.location.last}
            <li>
              <a href="{$l.1|amp}" data-icon="arrow-r" data-theme="a">{$l.0|amp}</a>
            </li>
          {/if}
        {/foreach}
      </ul>
    </div>
  </div>
{/if}
