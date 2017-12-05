{if $admin->getItemProperty($item, $column) }
    {$.t('Yes', 'admin')}
{else}
    {$.t('No', 'admin')}
{/if}