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
                    <span class="events empty">
                        -
                    </span>
                </span>

            </span>
        </div>
    </div>
</a>