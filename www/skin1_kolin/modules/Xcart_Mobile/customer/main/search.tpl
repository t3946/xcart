{*
$Id: search.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<div class="search">
  <div class="valign-middle">
{*    <form method="post" action="search.php" name="productsearchform"> *}
    <form method="post" action="home.php" name="productsearchform">
{*
      <input type="hidden" name="simple_search" value="Y" />
      <input type="hidden" name="mode" value="search" />
      <input type="hidden" name="posted_data[by_title]" value="Y" />
      <input type="hidden" name="posted_data[by_descr]" value="Y" />
      <input type="hidden" name="posted_data[by_sku]" value="Y" />
      <input type="hidden" name="posted_data[search_in_subcategories]" value="Y" />
      <input type="hidden" name="posted_data[including]" value="all" />
*}

            <input type="hidden" name="e_mode" value="e_search" />
            {if $cat gt 0 || $clean_url_data.resource_type eq "K"}
            <input type="hidden" name="e_current_url" value="{if $main eq "product"}/home.php?cat={$cat}{else}{$action_notify_url}{/if}" />
            {/if}
            <input type="hidden" name="cat" value="0" />


      {strip}
{*
        <input placeholder="{$lng.lbl_enter_keyword|escape}" type="search" name="posted_data[substring]" class="text{if not $search_prefilled.substring} default-value{/if}" value="{$search_prefilled.substring|escape}" />
*}

        <input type="search" name="e_posted_data[substring]" class="text{if not $search_prefilled.substring} default-value{/if}" value="{if $e_search_data.substring ne ""}{$e_search_data.substring|stripslashes|escape}{else}{$e_search_data_orig_substring}{/if}" title="Search For" id="twotabsearchtextbox" placeholder="{$config.Company.cidev_header_code}" {* autocomplete="off" *} />


      {/strip}
      <div class="ui-grid-a">
{*
        <div class="ui-block-a">
          {include file="customer/buttons/button.tpl" button_title=$lng.lbl_go_advanced href="search.php?mode=advanced_search" data_inline="false"}
        </div>
*}
        <div class="ui-block-b">
          {include file="customer/buttons/button.tpl" button_title=$lng.lbl_submit type="input" data_inline="false" data_theme="b"}
        </div>
      </div>
    </form>
  </div>
</div>
