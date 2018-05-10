{extends "pages/base_2.tpl"}
{block "content"}
    <article class="page_s_content">
        {block "custom_content"}{/block}
        <section class="page-container">
            <div class="row">
                <div class="column large-12">
                        {raw $model->content}
                        {block "form"}{/block}
                </div>
            </div>
        </section>
    </article>

{/block}