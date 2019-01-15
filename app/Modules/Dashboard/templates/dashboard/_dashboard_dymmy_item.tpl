<a href="{$model->getAbsoluteUrl()}"
   class="empty dashboard-item {if $check_owners?}check-owner {$model->getTextClassOwner()}{/if}"
   target="_blank"
   data-id="{$model->id}"
   data-tooltip-action="{url 'dashboard:filter_subscription' id=$model->id}"
   data-count="0">
    <div class="row">
        <div class="columns large-12">
            {if $model->tag}
                <span style="background-color: {$model->color};" class="tag no-border"></span>
            {/if}
            <span class="name_events">
                <span class="name">
                    <span class="{if $model->bold}bold{/if} filter_name">{$model}</span>
                    (<span class="count">-</span>)
                    <span class="priority empty">
                        -
                    </span>
                </span>

            </span>
            {set $owners = $model->users->filter(['status' => 'Y'])->all()}
            {if $owners}
                <div class="filter_owner {if $mode!== 1}hide{/if}">
                    {foreach $model->users->filter(['status' => 'Y']) as $user}
                        {if $user->id != $model->id}
                            <div class="item">
                                <div class="row">
                                    <div class="columns large-12">
                                        {$user}
                                    </div>
                                </div>
                            </div>
                        {/if}
                    {/foreach}
                </div>
            {/if}
        </div>
    </div>
</a>