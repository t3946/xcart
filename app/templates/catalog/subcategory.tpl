{extends  "catalog/base.tpl"}

{block 'content'}
    <div class="page-departments">
        <div class="row all-departments-menu  small-up-2 medium-up-3 large-up-6">
            {foreach $categories as $category}
            <button class="item-title column">
                <div class="image-container">
                    {*<object type="image/svg+xml" data="" wmode="transparent"></object>*}
                    <img class="image"  src="/static/frontend/images/icons/category/icons/icon1.svg" alt="">
                </div>
                <div class="title"><span>{$category->category}</span></div>
            </button>
            {/foreach}
        </div>
        <div class="row departments-submenu-container">
            <div class="title"></div>
            <div class="row departments-submenu  small-up-1 medium-up-2 large-up-4">
                <div class="group-items column">12ssdfsdfdsdfsdgffdgdfgdfg sdfsdf sdfsdfsfd</div>
                <div class="group-items column">12data-equalizer-watch</div>
                <div class="group-items column">12data-equalizer-watch</div>
                <div class="group-items column">12data-equalizer-watch</div>
                <div class="group-items column">12data-equalizer-watch</div>
                <div class="group-items column">12data-equalizer-watch</div>
                <div class="group-items column">12data-equalizer-watch</div>
                <div class="group-items column">12data-equalizer-watch</div>
            </div>
        </div>

    </div>
{/block}