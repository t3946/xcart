{if $item->active}
    {set $next_dt = $item->getNextRunning()}
    {if $next_dt}
        {$next_dt->format('m-d H:i')}
        <br/>
        ({$next_dt->diff(date_create())->format('%M-%D %H:%I:%S')})
    {/if}
{/if}