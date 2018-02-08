{* $Id: import_results.tpl,v 1.4 2004/07/08 08:15:59 mclap Exp $ *}
{if $import_stats.pass eq "test"}
{assign var=title value=$lng.lbl_import_products|cat:": "|cat:$lng.lbl_test_run}
{else}
{assign var=title value=$lng.lbl_import_products|cat:": "|cat:$lng.lbl_completed_small}
{/if}
{capture name="dialog"}

{if $import_stats.warnings}
<P class="AdminTitle">{$lng.lbl_warnings_generated_during_test}:</P>
{include file="provider/main/import_errors.tpl" data=$import_stats.warnings}
{/if}

{if $import_stats.errors}
<P class="AdminTitle">{$lng.lbl_errors_that_occured_during_test}:</P>
{include file="provider/main/import_errors.tpl" data=$import_stats.errors}
{/if}

{if $import_stats.errors or $import_stats.stop}
<HR>
<CENTER>
<FORM action="import.php" method="GET">
<INPUT type="submit" value="{$lng.lbl_go_back}">
</FORM>
</CENTER>
{elseif $import_stats.pass == "test"}
{* ready to import *}
<CENTER>
<P><B>{$lng.lbl_import_summary}</B></P>
<TABLE>
<TR><TD>{$lng.lbl_total_products_to_import}:</TD><TD>{$import_stats.total_products}</TD></TR>
<TR><TD>{$lng.lbl_new_products}:</TD><TD>{$import_stats.products}</TD></TR>
<TR><TD>{$lng.lbl_products_to_update}:</TD><TD>{$import_stats.products_updated}</TD></TR>
<TR><TD>{$lng.lbl_new_categories}:</TD><TD>{$import_stats.categories}</TD></TR>
<TR><TD>{$lng.lbl_products_to_delete}:</TD><TD>{$import_stats.products_deleted}</TD></TR>
</TABLE>
<HR>
<FORM action="import.php" method="POST">
<INPUT type="hidden" name="mode" value="import">
<INPUT type="submit" value="{$lng.lbl_continue_import}">
</FORM>
</CENTER>
{else}
<CENTER>
<P><B>{$lng.lbl_import_summary}</B></P>
<TABLE>
<TR><TD>{$lng.lbl_new_products_imported}:</TD><TD>{$import_stats.products}</TD></TR>
<TR><TD>{$lng.lbl_updated_products}:</TD><TD>{$import_stats.products_updated}</TD></TR>
<TR><TD>{$lng.lbl_new_categories_created}:</TD><TD>{$import_stats.categories}</TD></TR>
<TR><TD>{$lng.lbl_products_deleted}:</TD><TD>{$import_stats.products_deleted}</TD></TR>
</TABLE>
<HR>
<FORM action="import.php" method="GET">
<INPUT type="submit" value="{$lng.lbl_finish}">
</FORM>
</CENTER>
{/if}

{/capture}
{include file="dialog.tpl" title=$title content=$smarty.capture.dialog extra="width=100%"}
