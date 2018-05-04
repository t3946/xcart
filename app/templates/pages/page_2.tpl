{extends "pages/base.tpl"}
{block "content"}
    <article class="pages page">
        <section class="heading sas-header">
            <div class="row">
                <div class="column head sas-header ">
                    <div class="hit-header">
                        {block "custom_content"}{/block}
                    </div>
                </div>
            </div>
        </section>
        <section class="page-container">
            <div class="row">
                <div class="column large-12">
                    {raw $model->content}
                </div>
            </div>
        </section>
    </article>

{/block}