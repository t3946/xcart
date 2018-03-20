{if $column == '(string)'}
    {var $value = $.php.strval($item)}
{else}
    {var $value = $admin->getItemProperty($item, $column)}
{/if}

{if is_string($value)}
    {$value}
{elseif is_bool($value)}
    {if $value}
        {'Yes'}
    {else}
        {'No'}
    {/if}
{elseif is_object($value)}
    {set $class = get_class($value)}

    {if $class == 'DateTime'}
        {$value|humanizeDateTime}
    {else}
        {$class}
    {/if}
{else}
    {$value}
{/if}