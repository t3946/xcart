{*
$Id: navigation.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $total_pages gt 2}
  
  {assign var="navigation_script" value=$navigation_script|amp}
  
  {if $sort}
    {assign var="navigation_script" value=$navigation_script|cat:"&sort=`$sort`&sort_direction=`$sort_direction`"}
  {/if}
  {strip}
    <div class="ui-grid-b nav-grid">
      <div class="ui-block-a">
        {if $navigation_arrow_left}
          <a data-role="button" class="custom-arrow" data-icon="custom-arrow-l" data-theme="a" data-iconpos="notext" data-mini="false" data-inline="true" href="{$navigation_script}&amp;page={$navigation_arrow_left}">{$lng.lnl_prev}</a>
        {/if}
      </div>
      <div class="ui-block-b">
        <div class="nav-pages" data-inline="true">
          {$first_item} - {$last_item} {$lng.lbl_of} {if $total_items}{$total_items}{else}{func_mobile_get_total_items}{/if}
        </div>
      </div>
      <div class="ui-block-c">
        {if $navigation_arrow_right}
          <a class="custom-arrow" data-icon="custom-arrow-r" data-role="button" data-theme="a" data-iconpos="notext" data-inline="true" data-mini="false" href="{$navigation_script}&amp;page={$navigation_arrow_right}">{$lng.lnl_next}</a>
        {/if}
      </div>
    </div>
  {/strip}

{/if}