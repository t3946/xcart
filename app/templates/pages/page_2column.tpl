{extends  "pages/base.tpl"}
{block 'content'}
    <article class="pages page">
        <section class="heading container">
            <div class="row">
                <div class="col-12">
                    <h1>{$model->name}</h1>
                </div>
            </div>
        </section>
        <section class="page-container container">
            <div class="row">
                <div class="col-12">
                    {raw html_entity_decode($model->content)}
                </div>
            </div>
        </section>
    </article>
{/block}