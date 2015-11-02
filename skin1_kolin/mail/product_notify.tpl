{$lng.lbl_product_notify_email_header}
<br />
<br />
SKU: <a href="{$product_info.links.customer}" target="_blank">{$product_info.productcode}</a>
<br />
Product page link: <a href="{$product_info.links.customer}" target="_blank">{$product_info.product}</a>
<br />
Storefront home page: <a href="{$product_info.http_location}" target="_blank">{$product_info.http_location}</a>
<br />
<br />
{$lng.lbl_product_notify_email_bottom|replace:"`$ldelim``$ldelim`contactus_form`$rdelim``$rdelim`":"<a href='`$product_info.http_location`/help.php?section=contactus&mode=update' target='_blank'>S3 Stores Contact Us web form</a>"}
