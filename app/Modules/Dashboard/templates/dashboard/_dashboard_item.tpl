<a href="{$model->getAbsoluteUrl()}"
   class="{if $model->getSearchStorage()->getCashedCount() == 0}empty{else}button{/if} dashboard-item"
   target="_blank"
   data-id="{$model->id}"
   data-tooltip-action="{url 'dashboard:filter_subscription' id=$model->id}"
   data-count="{$model->getSearchStorage()->getCashedCount()}">
    <div class="row">
        <div class="columns large-2">
            {if $model->tag}
                <span style="background-color: {$model->color};" class="tag no-border">{$model->tag|upper}</span>
            {else}
                <span class="tag"></span>
            {/if}
        </div>
        <div class="columns large-10">
            <span class="name_events">
                <span class="name">
                    <span class="{if $model->bold}bold{/if} filter_name">{$model}</span>
                    (<span class="count">{$model->getSearchStorage()->getCashedCount()}</span>)

                    <span class="priority {if !$model->getSearchStorage()->getCachedPrioritySHippingCount()}empty{/if}">
                        {if $model->getSearchStorage()->getCachedPrioritySHippingCount()}
                            {$model->getSearchStorage()->getCachedPrioritySHippingCount()}
                        {/if}
                    </span>
                    <span class="events {if !$model->getSearchStorage()->getCachedEventsCount()}empty{/if}">
                        {if $model->getSearchStorage()->getCachedEventsCount()}
                            +{$model->getSearchStorage()->getCachedEventsCount()}
                        {/if}
                    </span>
                </span>

            </span>
        </div>
    </div>
</a>