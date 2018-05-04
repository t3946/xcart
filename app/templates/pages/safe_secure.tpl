{extends "pages/page_2.tpl"}
{block "custom_content"}
    <section class="heading sas-header">
        <div class="row">
            <div class="column head sas-header ">
                <div class="hit-header">
                    <img class="sas-lock" src="/static/frontend/images/lock.png"><h1>{$model->name|replace:"&":"&<br>"}</h1>
                </div>
            </div>
        </div>
    </section>

{/block}