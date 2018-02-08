
<div class="static-messages-block">
    <div class="messages-list">
        {foreach $models as $model}
            <div data-id="{$model->pk}" style="background-color: {$model->bg_color}; color: {$model->text_color};" class="message-block">
                <div class="message">
                    {if $model->title}
                    <div class="title">
                        {$model->title}
                    </div>
                    {/if}
                    <div class="description"  >
                        {$model->description}
                    </div>
                    <a href="#" class="close" data-id="{$model->pk}"><i class="icon-delete_in_filter"></i></a>
                </div>
            </div>
        {/foreach}
    </div>
</div>