{extends 'layout/landing_page.tpl'}
{block 'head'}
    {*<link rel='stylesheet' href='product/landing.css'>*}
    <style>
        {include "product/landing.css"}
    </style>
{/block}
{block 'content'}
    <div id="top">
        <div class="head_logo">
            <img class="logo" height="62" src="{$helper->getLogoImage()}" >
        </div>
        <span class="name">{$model->getFrontendName()}</span>
        <span class="content after_name">Тут неизвестная строка</span>
    </div>
    <img class="detail_img" src="{$helper->getImage()}" alt="{$model->product}">
    <span class="content description">{$model->getFrontendDescription()}</span>
    <div class="main-content" id="bottom"></div>

{/block}