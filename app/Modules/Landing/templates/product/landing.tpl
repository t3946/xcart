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
            <img class="logo" layout="fixed-height" height="62" src="{$helper->getLogoImage()}" >
        </div>
    </div>
    <div class="main-content" id="bottom">
        <img class="detail_img" src="{$helper->getImage()}" alt="{$model->product}">
        
    </div>
{/block}