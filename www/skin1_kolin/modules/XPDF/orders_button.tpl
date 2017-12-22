{*
$Id: orders_button.tpl 188 2011-06-04 16:36:19Z max $
vim: set ts=2 sw=2 sts=2 et:
*}
<input type="button" value="{$lng.lbl_xpdf_invoices_for_selected|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) {ldelim} document.processorderform.target='invoices'; submitForm(this, 'xpdf_invoice'); document.processorderform.target=''; {rdelim}" />
&nbsp;&nbsp;&nbsp;&nbsp;

