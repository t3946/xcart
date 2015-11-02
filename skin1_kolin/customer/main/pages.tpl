{* $Id: pages.tpl,v 1.5 2005/11/17 06:55:37 max Exp $ *}
<br>
{capture name=dialog}
{if $page_content ne ''}
{if $config.General.parse_smarty_tags eq "Y"}
{eval var=$page_content}
{else}
<span class="SPItems-description">{$page_content|replace:"<linktous-text-sample>":"`$linktous_text_sample`"|replace:"<linktous-banner-sample>":"`$linktous_banner_sample`"|replace:"<linktous-text-code>":"`$linktous_text_code`"|replace:"<linktous-banner-code>":"`$linktous_banner_code`"}</span>
{/if}
{/if}
{/capture}
{include file="dialog.tpl" title=$page_data.title content=$smarty.capture.dialog extra='width="100%"'  use_h1="Y"}
