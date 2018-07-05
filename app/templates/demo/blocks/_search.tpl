<div class="search-form-container">
    <form action="{$.app->router->url('catalog:search')}" method="get" itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction">
        <input type="text" name='q' class="search" placeholder="{$config.cidev_header_code}" value="{$.app->request->get->get('q', '')}" itemprop="query-input"/>
        <meta itemprop="target" content="{$.app->router->url('catalog:search')}?q={ignore}{query}{/ignore}"/>

        <button class="button-search show-for-large"></button>
        <a class="button-clear {if $.app->request->get->get('q')}active{/if}"></a>


        <div class="hide hidden-search">

            <div class="block">
                <span class="search__category">Search suggestions</span>
                <div class="clear"></div>
                <ul>
                    <li><a><b>Brushes</b> oil </a></li>
                    <li><a><b>Brushes</b> for  </a></li>
                    <li><a><b>Brushes</b> acrylic </a></li>
                    <li><a><b>Brushes</b> scrubber  </a></li>
                </ul>
            </div><!--end block-->

            <div class="block search__categories_block">
                <span class="search__category">Categories</span>
                <div class="clear"></div>
                <ul>
                    <li><a>Air<b>brush</b>ing</a></li>
                    <li><a><b>Brush</b> Accessories </a></li>
                    <li><a><b>Brush</b> Furniture for Artists</a></li>
                    <li><a>Paintings and Painting <b>Brush</b> Accessories</a></li>
                </ul>
            </div><!--end block-->

            <div class="block with-icons">
                <span class="search__category">Products</span>
                <div class="clear"></div>
                <ul>
                    <li>
                        <a>
                            <i class="image-block">
                                <i>
                                    <img src="/static/frontend/dist/images/home/1280/item1.jpg" alt="">
                                </i>
                            </i>
                            Reeves Artist <b>Brush</b> Roll
                        </a>
                    </li>
                    <li><a><i class="image-block"><i><img src="/static/frontend/dist/images/home/1280/item1.jpg" alt=""></i></i>Reeves <b>Brush</b> Set: Watercolor </a></li>
                    <li><a><i class="image-block"><i><img src="/static/frontend/dist/images/home/1280/item1.jpg" alt=""></i></i>Mod Podge <b>Brush</b> Applicator</a></li>
                    <li><a><i class="image-block"><i><img src="/static/frontend/dist/images/home/1280/item1.jpg" alt=""></i></i>Copic Multiliner <b>Brush</b>: Medium </a></li>
                    <li><a><i class="image-block"><i><img src="/static/frontend/dist/images/home/1280/item1.jpg" alt=""></i></i>Alvin Mini Dusting <b>Brush</b></a></li>
                </ul>
            </div>

        </div>
    </form>
</div>