<div class="subscription-container">
    <div class="row">
        <div class="columns large-12">
            {foreach $users as $user}
                {if $user->id != $model->id}
                    <input type="checkbox" name="{$class}[id]" checked disabled>
                    {$user->login}
                {/if}
            {/foreach}

            <form action="{url 'dashboard:filter_subscription' id=$id}" method="post">
                <div class="item">
                    <input type="checkbox" name="{$class}[id]" value="{$model->id}" id="sf_id" {if $model->id|in:$ids }checked{/if}>
                    <label for="sf_id">
                        {$model->login}
                    </label>
                </div>
                <input type="submit" value="Ok">
            </form>
        </div>
    </div>
</div>