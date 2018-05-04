{extends "pages/base.tpl"}
{block "content"}
    <article class="pages page">
        {block "custom_content"}{/block}
        <section class="page-container">
            <div class="row">
                <div class="column large-12">
                    <div class="large-2column">
                        {raw $model->content}
                        {block "form"}{/block}
                    </div>
                </div>
            </div>
        </section>
    </article>

{/block}