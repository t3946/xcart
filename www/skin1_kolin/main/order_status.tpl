{if !$status_type}
    {assign var="status_type" value="CB"}
{/if}
{if !$extended and !$status}
    {$lng.lbl_wrong_status}
{elseif !$limited || $extended}

    {assign var=avail_statuses value=$status->getAvailableStatuses()}

    <select name="{$name}" {$extra}>
        {if ($extended && !$limited) || $empty}
            <option value=""></option>
        {/if}

        {foreach from=$statuses[$status_type] key="code" item="o_status"}
            {if $code !== 'B'}
                <option
                        value="{$code}"
                        title="{Modules\Order\Models\OrderStatusModel::objects()->get(['code' => $code])->description}"
                        {if $status->code === $code}
                            selected
                        {/if}
                        {if ($status->code !== $code && (!$avail_statuses|count || !$code|in_array:$avail_statuses)) ||
                        ($code === "K" && $hide_pending_availability_check_status === 'Y') ||
                        ($code === 'C' && $hide_dispatched_status === 'Y')}
                            disabled
                        {/if}
                >
                    {$o_status}
                </option>
            {/if}
        {/foreach}
    </select>
{/if}
