<section id="group_result_block">
    <div class="row">
        <div class="columns large-4">
            <div class="thumbnails">
                {raw $images}
            </div>
        </div>
        <div class="columns large-8 info">
            <p class="message">
                Group product has been successfully created!
            </p>
            <p class="title">
                <a target="_blank" href="{$model->getUrl()}">{$model->getTitle()|escape}</a>
            </p>
            <p class="code">
                <a target="_blank" href="{$model->getAdminUrl()}">{$model->productcode}</a>
            </p>
        </div>
    </div>
</section>