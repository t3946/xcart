<div class="subscription-container">
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

    {if !$model->getIsGuest()}
    <div class="item">
        <div class="row">
            <div class="columns large-12">
                <form action="{url 'dashboard:filter_subscription' id=$id}" method="post">
                    <div class="item">
                        <input type="checkbox" name="{$class}[id]" value="{$model->id}" id="sf_id" {if $model->id|in:$ids }checked{/if}>
                        <label for="sf_id">
                            {$model}
                        </label>
                    </div>
                    <input type="submit" value="Ok">
                </form>
            </div>
        </div>
    </div>
    {/if}
</div>