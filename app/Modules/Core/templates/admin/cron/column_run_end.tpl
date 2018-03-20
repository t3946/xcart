{$item->run_end}

{if $item->run_start && $item->run_end}
    ({date_create($item->run_start)->diff(date_create($item->run_end))->format('%H:%I:%S')})
{/if}

