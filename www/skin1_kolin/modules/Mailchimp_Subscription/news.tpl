{* $Id: news.tpl,v 1.30.2.5 2008/07/15 12:07:39 ferz Exp $ *}
{if $active_modules.News_Management}
{insert name="gate" func="news_exist" assign="is_news_exist" lngcode=$shop_language}
{/if}

{if $active_modules.News_Management}
<br />
{capture name=menu}
<div class="VertMenuItems">
<div style="font-size: 9px">
{include file="today_news.tpl"}
</div>
{insert name="gate" func="news_subscription_allowed" assign="is_subscription_allowed" lngcode=$shop_language}
{if $is_subscription_allowed or $active_modules.Mailchimp_Subscription}

<img src="{$ImagesDir}/spacer.gif" width="1" height="8" alt="" /><br />
<form action="mailchimp_news.php" name="subscribeform" method="post">
<input type="hidden" name="subscribe_lng" value="{$store_language|escape}" />

<table>
<tr>
	<td>
{$lng.lbl_your_email}
<br />
<input type="text" name="newsemail" size="16" />
	</td>
</tr>
{if $active_modules.Image_Verification and $show_antibot.on_news_panel eq 'Y'}
{include file="modules/Image_Verification/spambot_arrest.tpl" mode="simple_column" id=$antibot_sections.on_news_panel}
{/if}
<tr>
    <td>
{include file="buttons/subscribe_menu.tpl"}
	</td>
</tr>
</table>
</form>
{/if}{* $is_subscription_allowed *}
</div>
{/capture}
{ include file="menu.tpl" dingbats="dingbats_news.gif" menu_title=$lng.lbl_news menu_content=$smarty.capture.menu }
{/if}{* $active_modules.News_Management and $is_news_exist *}
