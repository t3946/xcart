{extends "wrapper.tpl"}
{block "wrapper"}
    <div id="main_wrapper" class="off-canvas-wrapper">
        <div class="off-canvas-content" data-off-canvas-content>
            <div id="content-wrapper">
                {block "header"}
                    <div id="header-target"></div>
                {/block}

                <div id="content">
                    {block "search-menu"}
                        <div id="header-target"></div>
                    {/block}

                    <div class="before-content container">
                        {block "before-content"}
                            <div class="row">
                                <div class="col-12">
                                    {insert "base/_breadcrumbs.tpl"}
                                </div>
                            </div>
                        {/block}
                    </div>

                    {block "content-wrapper"}
                        <div class="content">
                            {block "content"}{/block}
                        </div>
                    {/block}

                    <div class="after-content container">
                        {block "after-content"}
                            <div class="row">
                                <div class="col-12 slider-viewed">
                                    {set $link}{url 'catalog:viewed'}{/set}
                                    {set $lbl}{t 'You recently viewed items'}{/set}
                                    {include 'slider/base_product_slider.tpl' title=$lbl link=$link hide=false hide_link=true}
                                </div>
                            </div>
                        {/block}
                    </div>
                </div>
            </div>

            {insert "_parts/_footer.tpl"}
        </div>


        {block 'offcanvas-menu-left'}
            <div class="off-canvas position-left hide" id="offCanvasLeft" data-off-canvas data-transition="push">
                {insert "_parts/_menu_mobile.tpl"}
            </div>
        {/block}
    </div>
{/block}