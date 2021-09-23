{*
$Id: customer_orders_button.tpl 188 2011-06-04 16:36:19Z max $
vim: set ts=2 sw=2 sts=2 et:
*}
&nbsp;&nbsp;&nbsp;&nbsp;
{include file="customer/buttons/button.tpl" button_title=$lng.lbl_xpdf_invoices_for_selected href="javascript: if (!checkMarks(document.processorderform, new RegExp('orderids\[[0-9]+\]', 'gi'))) return false; document.processorderform.target = 'invoices'; submitForm(this, 'xpdf_invoice'); document.processorderform.target = '';"}
