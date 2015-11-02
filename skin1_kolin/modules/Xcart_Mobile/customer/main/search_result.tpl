{*
$Id: search_result.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $mode ne "search" or $products eq ""}
  {*<h1>{$lng.lbl_advanced_search}</h1>*}
  <script type="text/javascript" src="{$SkinDir}/reset.js"></script>
  <script type="text/javascript">
    //<![CDATA[
    var searchform_def = [
      ['posted_data[substring]', ''],
      ['posted_data[including]', 'all'],
      ['posted_data[search_in_subcategories]', true],
      ['posted_data[by_title]', true],
      ['posted_data[by_descr]', true],
      ['posted_data[by_keywords]', true],
      ['posted_data[by_sku]', true],
      ['posted_data[price_min]', '{$search_prefilled_default.price_min}'],
      ['posted_data[price_max]', '{$search_prefilled_default.price_max}'],
      ['posted_data[avail_min]', '0'],
      ['posted_data[weight_min]', '{$search_prefilled_default.weight_min}'],
      ['posted_data[weight_max]', '{$search_prefilled_default.weight_max}'],
    {if $active_modules.Extra_Fields and $extra_fields ne ''}
      {foreach from=$extra_fields item=v}
          ['posted_data[extra_fields][{$v.fieldid}]', false],
      {/foreach}
    {/if}
    {if $active_modules.Manufacturers and $manufacturers ne '' and $config.Search_products.search_products_manufacturers eq 'Y'}
        ['posted_data[manufacturers][]', '{$search_prefilled_default.manufacturerids}'],
    {/if}
        ['posted_data[categoryid]', '{$search_prefilled_default.categoryid}']
      ];
      //]]>
  </script>
  {capture name=dialog}
    <form name="searchform" action="search.php" method="post">
      <input type="hidden" name="mode" value="search" />
      <ul data-role="listview" data-inset="true">
        <li data-theme="b">
          <div class="search-holder">
            <input placeholder="{$lng.lbl_search_for_pattern}" type="search" name="posted_data[substring]" value="{$search_prefilled.substring|escape}" />
          </div>
          {*include file="customer/buttons/button.tpl" button_title=$lng.lbl_search type="input" additional_button_class="main-button"*}
        </li>
        <li>
          <fieldset data-role="controlgroup">
            <label>
              <input type="radio" name="posted_data[including]" value="all"{if $is_empty_search_prefilled or $search_prefilled.including eq '' or $search_prefilled.including eq 'all'} checked="checked"{/if} />
              {$lng.lbl_all_word}
            </label>
            <label>
              <input type="radio" name="posted_data[including]" value="any"{if $search_prefilled.including eq 'any'} checked="checked"{/if} />
              {$lng.lbl_any_word}
            </label>
            <label>
              <input type="radio" name="posted_data[including]" value="phrase"{if $search_prefilled.including eq 'phrase'} checked="checked"{/if} />
              {$lng.lbl_exact_phrase}
            </label>
          </fieldset>
        </li>
        <li>
          <fieldset data-role="controlgroup">
            <legend>{$lng.lbl_search_in}:</legend>
            <label>
              <input type="checkbox" name="posted_data[by_title]"{if $is_empty_search_prefilled or $search_prefilled.by_title} checked="checked"{/if} />
              {$lng.lbl_product_title}
            </label>
            <label>
              <input type="checkbox" id="posted_data_by_descr" name="posted_data[by_descr]"{if $is_empty_search_prefilled or $search_prefilled.by_descr} checked="checked"{/if} />
              {$lng.lbl_description}
            </label>
            <label>
              <input type="checkbox" id="posted_data_by_sku" name="posted_data[by_sku]"{if $is_empty_search_prefilled or $search_prefilled.by_sku} checked="checked"{/if} />
              {$lng.lbl_sku}
            </label>
          </fieldset>
        </li>
        {if $active_modules.Extra_Fields and $extra_fields ne ''}
          <li>
            <fieldset data-role="controlgroup">
              <legend>{$lng.lbl_search_also_in}:</legend>
              {foreach from=$extra_fields item=v}
                <label>
                  <input type="checkbox" name="posted_data[extra_fields][{$v.fieldid}]"{if $v.selected eq "Y"} checked="checked"{/if} />
                  {$v.field}
                </label>
              {/foreach}
            </fieldset>
          </li>
        {/if}
      </ul>
      {if $config.Search_products.search_products_category eq 'Y' or ($active_modules.Manufacturers and $config.Search_products.search_products_manufacturers eq 'Y') or $config.Search_products.search_products_price eq 'Y' or $config.Search_products.search_products_weight eq 'Y'}

        <div data-role="collapsible" data-theme="c" data-content-theme="c" {if $search_prefilled.need_advanced_options} data-collapsed="false"{/if}>
          <h3>{$lng.lbl_advanced_search_options}</h3>
          <div>
            <ul data-role="listview" data-inset="false">
              {if $config.Search_products.search_products_category eq 'Y'}
                <li>
                  <fieldset data-role="controlgroup">
                    <select name="posted_data[categoryid]" data-inline="false" class="adv-search-select">
                      <option value="">{$lng.lbl_search_in_category}</option>
                      {foreach from=$search_categories item=v key=k}
                        <option value="{$k}"{if $search_prefilled.categoryid eq $v.categoryid} selected="selected"{/if}>{if $config.UA.browser eq "MSIE"}{$v|truncate:60:'...':true:true|amp}{else}{$v|amp}{/if}</option>
                      {/foreach}
                    </select>
                    <label>
                      <input type="checkbox" name="posted_data[search_in_subcategories]"{if $is_empty_search_prefilled or $search_prefilled.search_in_subcategories} checked="checked"{/if} />
                      {$lng.lbl_search_in_subcategories}
                    </label>
                  </fieldset>
                </li>
              {/if}
              {if $active_modules.Manufacturers and $manufacturers ne '' and $config.Search_products.search_products_manufacturers eq 'Y'}
                {capture name=manufacturers_items}
                  <option>{$lng.lbl_manufacturers}</option>
                  {section name=mnf loop=$manufacturers}
                    <option value="{$manufacturers[mnf].manufacturerid}"{if $manufacturers[mnf].selected eq 'Y'} selected="selected"{/if}>{$manufacturers[mnf].manufacturer}</option>
                  {/section}
                {/capture}
                <li>
                  <select multiple="multiple" name="posted_data[manufacturers][]" id="select-choice-manufacturers" data-native-menu="false">
                    {$smarty.capture.manufacturers_items}
                  </select>
                </li>
              {/if}
              {if $config.Search_products.search_products_price eq 'Y'}
                <li>
                  <fieldset data-role="controlgroup">
                    <legend>{$lng.lbl_price} ({$config.General.currency_symbol}):</legend>
                    <div class="ui-grid-b">
                      <div class="ui-block-a">
                        <input placeholder="{$lng.lbl_from}" type="number" size="10" maxlength="15" name="posted_data[price_min]" value="{$search_prefilled.price_min|escape}" />
                      </div>
                      <div class="ui-block-b">
                      </div>
                      <div class="ui-block-c">
                        <input placeholder="{$lng.lbl_to}" type="number" size="10" maxlength="15" name="posted_data[price_max]" value="{$search_prefilled.price_max|escape}" />
                      </div>
                    </div>
                  </fieldset>
                </li>
              {/if}
              {if $config.Search_products.search_products_weight eq 'Y'}
                <li>
                  <fieldset data-role="controlgroup">
                    <legend>{$lng.lbl_weight} ({$config.General.weight_symbol}):</legend>
                    <div class="ui-grid-b">
                      <div class="ui-block-a">
                        <input placeholder="{$lng.lbl_from}" type="number" size="10" maxlength="10" name="posted_data[weight_min]" value="{$search_prefilled.weight_min|escape}" />
                      </div>
                      <div class="ui-block-b">
                      </div>
                      <div class="ui-block-c">
                        <input placeholder="{$lng.lbl_to}" type="number" size="10" maxlength="10" name="posted_data[weight_max]" value="{$search_prefilled.weight_max|escape}" />
                      </div>
                    </div>
                  </fieldset>
                </li>
              {/if}
            </ul>
            <br />

            {include file="customer/buttons/button.tpl" button_title=$lng.lbl_reset_filter style="link" href="javascript: reset_form('searchform', searchform_def); $('select').selectmenu('refresh'); $('input[type=radio], input[type=checkbox]').checkboxradio('refresh')" data_inline="false"}
          </div>
        </div>
      {/if}
      {include file="customer/buttons/button.tpl" button_title=$lng.lbl_search type="input" additional_button_class="main-button" data_inline="false"}
    </form>
  {/capture}
  {include file="customer/dialog.tpl" title='' content=$smarty.capture.dialog additional_class="adv-search"}
{/if}
{if $mode eq "search"}
  <div class="ui-grid-a">
    <div class="ui-block-a">
      <p>
        {if $total_items gt "1"}
          {$lng.txt_N_results_found|substitute:"items":$total_items}. <br />{$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
        {elseif $total_items eq "0"}
          {$lng.txt_N_results_found|substitute:"items":0}
        {/if}
      </p>
    </div>
    <div class="ui-block-b">
      {include file="customer/buttons/button.tpl" button_title=$lng.lbl_advanced_search href="search.php?mode=advanced_search" additional_button_class="main-button right-block" style="link"}
    </div>
  </div>
  <br />
{/if}
{if $mode eq "search" and $products ne ""}
  {if $total_pages gt 2}
    {assign var="navpage" value=$navigation_page}
  {/if}
  {include file="customer/main/navigation.tpl"}
  {include file="customer/main/products.tpl"}
  {include file="customer/main/navigation.tpl"}

{/if}
