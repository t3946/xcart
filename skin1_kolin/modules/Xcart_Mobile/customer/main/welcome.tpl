{*
$Id: welcome.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}

{*
{if $xcart_mobile_config.parse_smarty}
  {eval var=$lng.txt_xcart_mobile_homepage_text}
{else}
  {$lng.txt_xcart_mobile_homepage_text}
{/if}
{include file="customer/news.tpl"}
*}

<div class="welcome-page">

{if $e_products_found eq "Y"}

        {if $current_storefront eq "41"}
                {include file="customer/main/products_new_style.tpl" products=$products}
        {else}
                {include file="customer/main/products.tpl" products=$products do_not_use_load_more_function="Y"}
        {/if}

        { include file="customer/main/navigation.tpl" }

{else}


  {if $active_modules.New_Arrivals and $config.New_Arrivals.new_arrivals_home eq "Y" and $xcart_mobile_config.new_arrivals eq 'Y'}
    <div data-role="collapsible" data-theme="c" data-content-theme="c" data-inset="false">
      <h3>{$lng.lbl_new_arrivals}</h3>
      {include file="customer/main/products.tpl" products=$new_arrivals new_arrivals_show_date="Y" is_new_arrivals_products="Y" do_not_use_load_more_function="Y"}
      {include file="customer/buttons/button.tpl" button_title=$lng.lbl_mobile_show_all_new_arrivals href="new_arrivals.php" data_theme="a" style="link"}
    </div>
  {/if}
  {if $active_modules.On_Sale and $config.On_Sale.on_sale_home eq "Y" and $xcart_mobile_config.on_sale eq 'Y'}
    <div data-role="collapsible" data-theme="c" data-content-theme="c" data-inset="false">
      <h3>{$lng.lbl_on_sale}</h3>
      {include file="customer/main/products.tpl" products=$on_sale_products do_not_use_load_more_function="Y"}
      {include file="customer/buttons/button.tpl" button_title=$lng.lbl_mobile_show_all_on_sale href="on_sale.php" data_theme="a" style="link"}
    </div>
  {/if}
  {if $active_modules.Bestsellers and $xcart_mobile_config.bestsellers eq 'Y' and $bestsellers}
    <div data-role="collapsible" data-theme="c" data-content-theme="c" data-inset="false">
      <h3>{$lng.lbl_bestsellers}</h3>
      {include file="customer/main/products.tpl" products=$bestsellers do_not_use_load_more_function="Y"}
    </div>
  {/if}
  {if $f_products and $xcart_mobile_config.featured eq 'Y'}
    <div data-role="collapsible" data-theme="c" data-content-theme="c" data-inset="false">
      <h3 class="ui-collapsible-heading">{$lng.lbl_featured_products}</h3>
      {include file="customer/main/products.tpl" products=$f_products featured="Y" do_not_use_load_more_function="Y"}
    </div>
  {/if}

{/if}

</div>
{literal}
  <script type=text/javascript>
    //<![CDATA[
    $(function() {
      $('.welcome-page .ui-collapsible').filter(':first').contents().each(function() {
        $(this)
                .removeClass('ui-collapsible-heading-collapsed')
                .find('.ui-icon')
                .removeClass('ui-icon-plus')
                .addClass('ui-icon-minus');
        $(this).removeClass('ui-collapsible-content-collapsed');
      }).andSelf().removeClass('ui-collapsible-collapsed');
    });
    //]]>
  </script>
{/literal}


{$config.Company.cidev_main_page_code}


<div style="margin: 9px 10px 0px 10px; padding: 8px; background-color: #EFEDDF;">

<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;" border="0">
<tr>
<td align="left" style="vertical-align: top;" colspan="4">
<span class="ProductPrice" style="font-size: 20px; font-weight: bold;">{$lng.lbl_help}</span>
</td>
</tr>

<tr>
<td>
        <table cellspacing="7" cellpadding="7" width="100%">
{section name=pg loop=$pages_menu}
                <tr>
                        <td align="left" valign="top">

{if $pages_menu[pg].new_link ne ""}
<a href="{$pages_menu[pg].new_link}" class="VertMenuItems" style="font-size: 18px;">{$pages_menu[pg].title}</a>
{else}
{if $smarty.get.pageid ne $pages_menu[pg].pageid}<a href="/pages.php?pageid={$pages_menu[pg].pageid}" class="VertMenuItems" style="font-size: 18px;">{else}<font class="VertMenuItems" style="font-size: 16px;">{/if}{$pages_menu[pg].title}{if $smarty.get.pageid ne $pages_menu[pg].pageid}</a>{else}</font>{/if}
{/if}
<br />

                        </td>
                </tr>
{/section}
        </table>
</td>
</tr>
</table>

</div>

