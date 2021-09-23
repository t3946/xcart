{*
$Id: more.tpl 78 2012-12-28 13:59:37Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{*
<h2>{$lng.lbl_your_account}</h2>
<ul data-role="listview" data-inset="true">
  <li>
    <a href="{$current_location}/{if $login}register.php?mode=update{else}login.php{/if}" rel="external">
      {if $login}
        <span class="property-name">{$lng.lbl_account}</span>
        <span class="property-value">{$login}</span>
      {else}
        <span class="property-name">{$lng.lbl_login}</span>
      {/if}
    </a>
  </li>
  <li>
    <a href="{$current_location}/{if $login}login.php?mode=logout{else}register.php{/if}" rel="external">
      {if $login}
        <span class="property-name">{$lng.lbl_logoff}</span>
      {else}
        <span class="property-name">{$lng.lbl_register}</span>
      {/if}
    </a>
  </li>
</ul>
*}
{if $login}
  {capture name="submenu"}
    {if $active_modules.Wishlist and $wlid ne ""}
      <li><a href="cart.php?mode=friend_wl&amp;wlid={$wlid|escape}">{$lng.lbl_friends_wish_list}</a></li>
      {/if}
    {if $active_modules.Wishlist}
      <li><a href="cart.php?mode=wishlist">{$lng.lbl_wish_list}</a></li>
      {if $active_modules.Gift_Registry}
        <li><a href="giftreg_manage.php">{$lng.lbl_gift_registry}</a></li>
        {/if}
    {/if}
    {if $active_modules.Quick_Reorder}
      {include file="modules/Quick_Reorder/quick_reorder_link_menu_cart.tpl"}
    {/if}
  {/capture}
  {if $smarty.capture.submenu|trim}
    <h2>{$lng.lbl_special}</h2>
    <ul data-role="listview" data-inset="true">
      {$smarty.capture.submenu}
    </ul>
  {/if}
{/if}
{* Explore if we need this menu in mobile verson *}
{capture name=submenu}
  {if $active_modules.News_Management}
    {insert name="gate" func="news_exist" assign="is_news_exist" lngcode=$shop_language}
    {if $is_news_exist}
      <li><a href="news.php">{$lng.lbl_news}</a></li>
      {/if}
    {/if}
  {if $active_modules.Manufacturers ne "" && $manufacturers_menu}
    <li><a href="manufacturers.php">{$lng.lbl_manufacturers}</a></li>
    {/if}
  {if $active_modules.Gift_Certificates ne ""}
    {include file="modules/Gift_Certificates/gc_menu.tpl"}
  {/if}
  {if $active_modules.Gift_Registry ne ""}
    {include file="modules/Gift_Registry/giftreg_menu.tpl"}
  {/if}
  {if $active_modules.Feature_Comparison ne ""}
    {include file="modules/Feature_Comparison/customer_menu.tpl"}
  {/if}
  {if $active_modules.Survey ne ""}
    {include file="modules/Survey/menu_special.tpl"}
  {/if}
  {if $active_modules.Special_Offers ne ""}
    {include file="modules/Special_Offers/menu_special.tpl"}
  {/if}
  {if $active_modules.New_Arrivals ne ""}
    {include file="modules/New_Arrivals/new_arrivals_link.tpl"}
  {/if}
  {if $active_modules.On_Sale ne ""}
    {include file="modules/On_Sale/on_sale_link.tpl"}
  {/if}
  {if $active_modules.EU_Cookie_Law ne ""}
    {include file="modules/EU_Cookie_Law/menu_item_special.tpl"}
  {/if}

{/capture}
<h2>{$lng.lbl_more_information}</h2>
<ul data-role="listview" data-inset="true">
  {if $smarty.capture.submenu|trim}
    {$smarty.capture.submenu|trim}
  {/if}
  <li><a href="help.php?section=contactus&amp;mode=update" rel="external">{$lng.lbl_contact_us}</a></li>
    {foreach from=$pages_menu item=p}
      {if $p.show_in_menu eq 'Y'}
      <li><a href="pages.php?pageid={$p.pageid}">{$p.title|amp}</a></li>
      {/if}
    {/foreach}
</ul>
