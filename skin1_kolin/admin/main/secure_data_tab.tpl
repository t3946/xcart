
{include file="main/subheader.tpl" title=$lng.lbl_secure_data_title}

{if ($oCustomer)}
    {if $oCustomer->isCustomerUseSecureData()}
        {assign var=aCustomerSecureData value=$oCustomer->getCustomerSecureData()}
        {foreach from=$aCustomerSecureData item=aSecureData}
            {$aSecureData.data} <br/><br/>
        {/foreach}
    {/if}
{/if}