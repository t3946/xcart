{extends "pages/page_2.tpl"}
{block "custom_content"}
    <section class="heading sas-header-main">
        <div class="row">
            <div class="column head sas-header-main">
                <div class="hit-header">
                    <img class="sas-h1-img" src="/static/frontend/images/pages/lock.png"><h1 class="sas-h1-txt">{$model->name}</h1>
                </div>
            </div>
        </div>
    </section>

{/block}