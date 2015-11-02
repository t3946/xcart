{* $Id: menu.tpl,v 1.15 2004/06/04 12:52:42 svowl Exp $ *}
{capture name=menu}
<A href="banner_info.php" class="VertMenuItems">{$lng.lbl_banners_statistics}<BR>
<A href="referred_sales.php" class="VertMenuItems">{$lng.lbl_referred_sales}</A><BR>
<A href="stats.php" class="VertMenuItems">{$lng.lbl_summary_statistics}</A><BR>
<A href="payment_history.php" class="VertMenuItems">{$lng.lbl_payment_history}</A><BR>
<A href="howto.php" class="VertMenuItems">{$lng.lbl_banner_html_code}</A><BR>
<A href="search.php" class="VertMenuItems">{$lng.lbl_product_html_code}</A><BR>
{if $config.XAffiliate.partner_enable_level eq 'Y'}
<A href="affiliates.php" class="VertMenuItems">{$lng.lbl_affiliates_tree}</A><BR>
{/if}
{/capture}
{ include file="menu.tpl" dingbats="dingbats_categorie.gif" menu_title=$lng.lbl_management menu_content=$smarty.capture.menu }
