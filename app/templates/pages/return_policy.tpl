{extends "pages/page_2.tpl"}
{block "custom_content"}
    <img class="sas-lock" src="/static/frontend/images/shape_23.png"><h1>{$model->name|replace:" ":"<br>"}</h1>
{/block}