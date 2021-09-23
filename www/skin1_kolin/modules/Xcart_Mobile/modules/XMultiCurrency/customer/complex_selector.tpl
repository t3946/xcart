{*
$Id: complex_selector.tpl 78 2012-12-28 13:59:37Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{foreach from=$all_languages item=l name=languages}
  {if $store_language eq $l.code}
    {if $config.Appearance.line_language_selector eq 'Y'}
      {assign var="cur_lng_dspl" value=$l.code3}
    {elseif $config.Appearance.line_language_selector eq 'A'}
      {assign var="cur_lng_dspl" value=$l.code}
    {elseif $config.Appearance.line_language_selector eq 'L'}
      {assign var="cur_lng_dspl" value=$l.language}
    {/if}
    {assign var="curlng" value=$l}
  {/if}
{/foreach}
{assign var="mc_selector_lines" value=0}
{if $smarty.foreach.languages.total gt 1}
  {math assign="mc_selector_lines" equation="x+1" x=$mc_selector_lines}
{/if}
{if $mc_allow_currency_selection}
  {math assign="mc_selector_lines" equation="x+1" x=$mc_selector_lines}
{/if}
{if $config.mc_allow_select_country eq "Y"}
  {math assign="mc_selector_lines" equation="x+1" x=$mc_selector_lines}
{/if}
<div class="mc-config" data-role="collapsible" data-theme="b" data-content-theme="c" data-inset="false">
  <h3>
    {if $config.Appearance.line_language_selector eq 'F'}
      <img src="{if not $curlng.is_url}{$current_location}{/if}{$curlng.tmbn_url|amp}" alt="{$curlng.language|escape}" title="{$lng.lbl_language|escape}: {$curlng.language|escape}" />
    {else}
      '{$cur_lng_dspl}'
    {/if}
    &nbsp;
  {$store_currency}{if $store_currency_data.symbol ne "" and $store_currency ne $store_currency_data.symbol} ({$store_currency_data.symbol}){/if}
  &nbsp;
  {if $config.mc_allow_select_country eq "Y"}
    {assign var="store_country_name" value="country_`$store_country`"}
    {$lng.$store_country_name}
  {/if}
</h3>
<div>
  <form action="home.php" method="get" data-ajax="false">
    <ul>
      {* Language selector *}
      {if $smarty.foreach.languages.total gt 1}
        <li>
          <label>{$lng.lbl_select_language}:</label>
          <select name="sl" id="mc-selector-language">
            {foreach from=$all_languages item=l}
              {if $store_language eq $l.code}
                {assign var="mc_current_language" value=$l.language}
              {/if}
              <option value="{$l.code}"{if $store_language eq $l.code} selected="selected"{/if}>{$l.language}</option>
            {/foreach}
          </select>
        </li>
      {/if}
      {* Currency selector *}
      {if $mc_allow_currency_selection}
        <li>
          <label>{$lng.mc_lbl_select_surrency}:</label>
          <select name="mc_currency" id="mc-currency">
            <option>{$lng.mc_lbl_select_surrency}<option>
              {foreach from=$mc_all_currencies item=cur}
              <option value="{$cur.code}"{if $store_currency eq $cur.code} selected="selected"{/if}>{$cur.code} - {$cur.name}</option>
            {/foreach}
          </select>
        </li>
      {/if}
      {* Country selector *}
      {if $config.mc_allow_select_country eq "Y"}
        <li>
          <label>{$lng.mc_lbl_select_coountry}:</label>
          <select name="mc_country" id="mc-country" onchange="javascript: setCurrencyByCountry($('#mc-country option:selected').val()); setLanguageByCountry($('#mc-country option:selected').val());">
            {foreach from=$mc_all_countries item=cnt}
              {if not $cnt.excluded}
                <option value="{$cnt.country_code}"{if $store_country eq $cnt.country_code} selected="selected"{/if}>{$cnt.country}</option>
              {/if}
            {/foreach}
          </select>
        </li>
      {/if}
    </ul>
    <br />
    <div>
      <input data-role="button" data-theme="b" type="submit" value="{$lng.lbl_apply}" />
    </div>
  </form>
</div>
</div>