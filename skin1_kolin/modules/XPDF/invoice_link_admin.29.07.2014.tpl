{*
$Id: invoice_link_admin.tpl 188 2011-06-04 16:36:19Z max $
vim: set ts=2 sw=2 sts=2 et:
*}

{include file="buttons/button.tpl" button_title=$lng.lbl_xpdf_pdf_invoice href="xpdf.php?mode=invoice&orderid=`$orderid`" substyle="link" additional_button_class="xpdf-button"}
