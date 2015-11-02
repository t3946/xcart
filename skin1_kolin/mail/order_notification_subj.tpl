{if $d_email_subject_14 ne ""}
{$d_email_subject_14}
{else}
{assign var="orderid" value=$order.order_prefix|cat:$order.orderid}
{if $order_notification}
    {if $type eq 'C'}
        {assign var="subject" value=$order_notification.customer_subject|substitute:"orderid":$orderid}
    {else}
        {assign var="subject" value=$order_notification.copy_subject|substitute:"orderid":$orderid}
    {/if}
{else}
    {assign var="subject" value=$lng.eml_order_notification_subj|substitute:"orderid":$orderid}
{/if}
{* {config_load file="$skin_config"}{$config.Company.operating_company_name}: {$subject} *}
{config_load file="$skin_config"}{$subject}
{/if}
