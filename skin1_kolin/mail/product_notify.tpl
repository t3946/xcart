{$lng.lbl_product_notify_email_header}
<br />
<br />
{assign var=dataModel value=$productmodel->getDataModel()}
SKU: <a href="{$dataModel->getUrl('https://')}?origin=notify_when_in_stock" target="_blank">{$productmodel->productcode}</a>
<br />
Product page link: <a href="{$dataModel->getUrl('https://')}?origin=notify_when_in_stock" target="_blank">{$productmodel->product}</a>
<br />
Storefront home page: <a href="{$product_info.http_location}" target="_blank">{$product_info.http_location}</a>
<br />
<br />
{$lng.lbl_product_notify_email_bottom|replace:"`$ldelim``$ldelim`contactus_form`$rdelim``$rdelim`":"<a href='`$product_info.http_location`/help.php?section=contactus&mode=update' target='_blank'>S3 Stores Contact Us web form</a>"}
