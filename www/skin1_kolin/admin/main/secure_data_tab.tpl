
{include file="main/subheader.tpl" title=$lng.lbl_secure_data_title}

    <span style="color:red;">{$lng.lbl_secure_data_note}</span><br/><br/>

{if ($oCustomer)}
    {if $oCustomer->isCustomerUseSecureData()}
        {assign var=aCustomerSecureData value=$oCustomer->getCustomerSecureData()}
        {foreach from=$aCustomerSecureData item=aSecureData}
            {$aSecureData.data} <br/><br/>
        {/foreach}
    {/if}
{/if}