<link rel="stylesheet" href="{$SkinDir}/verificator/css/main.css"/>

<br/>
{capture name=verification_results}
    {include file="modules/External_Product_Verification/verification_results.tpl"}
{/capture}
{include file="dialog.tpl" title='Verification results' content=$smarty.capture.verification_results extra='width="100%"'}