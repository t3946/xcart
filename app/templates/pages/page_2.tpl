{extends "pages/base_2.tpl"}
{block "content"}
    <article class="page_s_content">
        {block "custom_content"}{/block}
        <section class="page-container">
            <div class="row">
                <div class="col-12">
                        {raw html_entity_decode($model->content)}
                        {block "form"}{/block}
                </div>
            </div>
        </section>
    </article>

{/block}