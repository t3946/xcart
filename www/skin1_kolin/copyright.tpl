<font color="#000000" size=2>
    {$lng.lbl_copyright}
    &copy; {$config.Company.start_year}{if $config.Company.start_year lt $config.Company.end_year}-{$smarty.now|date_format:"%Y"}{/if} {$config.Company.holding_company_name}
    All Rights Reserved.
    {if $usertype eq "C"}&nbsp;&nbsp;
        <a href="pages.php?pageid=39" class="NavigationPath">{$lng.lbl_terms_n_conditions}</a>
        | <a href="pages.php?pageid=40" class="NavigationPath">{$lng.lbl_privacy_statement}</a>
    {/if}
    {if $usertype eq "A"}
        {$smarty.now|date_format:'%d-%b-%Y %H:%M:%S'}
    {/if}
</font>
