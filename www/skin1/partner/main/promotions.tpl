{* $Id: promotions.tpl,v 1.10 2004/05/28 12:21:15 max Exp $ *}
{capture name=dialog}
<H3>{$lng.lbl_you_are_in_your_personal_partner_area}</H3>
<P align="justify">
{$lng.txt_partner_promotion_note}
<P>
<IMG src="{$ImagesDir}/rarrow.gif" width="9" height=9> <B><A href="banner_info.php">{$lng.lbl_banners_statistics}</A></B><BR>
{$lng.txt_partner_promotion_section_banners}
<P>
<IMG src="{$ImagesDir}/rarrow.gif" width="9" height=9> <B><A href="stats.php">{$lng.lbl_summary_statistics}</A></B><BR>
{$lng.txt_partner_promotion_section_summary}
<P>
<IMG src="{$ImagesDir}/rarrow.gif" width="9" height=9> <B><A href="payment_history.php">{$lng.lbl_payment_history}</A></B><BR>
{$lng.txt_partner_promotion_section_payments}
<P>
<IMG src="{$ImagesDir}/rarrow.gif" width="9" height=9> <B><A href="howto.php">{$lng.lbl_banner_html_code}</A></B><BR>
{$lng.txt_partner_promotion_section_banner_code}
<P>
<IMG src="{$ImagesDir}/rarrow.gif" width="9" height=9> <B><A href="search.php">{$lng.lbl_product_html_code}</A></B><BR>
{$lng.txt_partner_promotion_section_product_code}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_partner_menu content=$smarty.capture.dialog extra="width=100%"}
<P>
