{* $Id: import_export.tpl,v 1.2.2.2 2006/12/25 12:16:36 svowl Exp $ *}

{if $mode eq "export"}
{include file="page_title.tpl" title=$lng.lbl_export_data}

{else}
{include file="page_title.tpl" title=$lng.lbl_import_data}
{/if}

{$lng.txt_import_data_top_text}

<br /><br />

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->

<br />

{if $mode eq "export"}
{include file="main/export.tpl"}

{else}
{include file="main/import.tpl"}
{/if}

