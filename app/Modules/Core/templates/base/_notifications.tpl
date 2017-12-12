
<div class="static-messages-block">
    <div class="messages-list">
        <ul>
            {foreach $models as $model}
                <li data-id="{$model->pk}" style="background-color: {$model->bg_color}; color: {$model->text_color};">
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
                </li>
            {/foreach}
        </ul>
    </div>
</div>