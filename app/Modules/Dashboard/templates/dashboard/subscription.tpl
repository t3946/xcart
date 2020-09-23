<div class="subscription-container">
    {if $filter->manual_url}
        <div class="row">
            <div class="columns large-12">
                <div class="title item" style="text-align: left;margin-left: 24px;">
                    <a href="{$filter->manual_url}" target="_blank" style="color: #140BFC;">Link to manual</a>
                </div>
            </div>
        </div>
    {/if}
    {if $users|count}
        <div class="row">
            <div class="columns large-12">
                <div class="title item" style="text-align: left;margin-left: 24px;">
                    Operators responsible
                </div>
            </div>
        </div>
        {foreach $users as $user}
            {if $user->id != $model->id}
                <div class="item">
                    <div class="row">
                        <div class="columns large-12">
                            <input type="checkbox" name="{$class}[id]" checked disabled>
                            {$user}
                        </div>
                    </div>
                </div>
            {/if}
        {/foreach}
    {/if}

    {if !$model->getIsGuest()}
        <div class="item">
            <div class="row">
                <div class="columns large-12">
                    <div class="title item" style="text-align: left;margin-left: 24px;">
                        Take responsibility
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns large-12">
                    <form action="{url 'dashboard:filter_subscription' id=$id}" method="post">
                        <div class="item">
                            <input type="checkbox" name="{$class}[id]" value="{$model->id}" id="sf_id"
                                   {if $model->id|in:$ids }checked{/if}>
                            <label for="sf_id">
                                {$model}
                            </label>
                        </div>
                        <input type="submit" value="Apply">
                    </form>
                </div>
            </div>
        </div>
    {/if}
</div>