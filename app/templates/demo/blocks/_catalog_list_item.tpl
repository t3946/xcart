{set $productid = rand(0,800000)}
<div class="item" data-product="{$productid}">
        <div class="image_container container">
            <a href="#">
                {*{if rand(1,2) > 1}*}
                    {*<span class="item__rect-sale_yellow hidden-xs hidden-sm hidden-md">Sale</span>*}
                {*{/if}*}

                {*{if rand(1,2) > 1}*}
                    {*<span class="item__circle-new_red hidden-xs hidden-sm hidden-md">New</span>*}
                {*{/if}*}

                {if rand(1,2) > 1}
                    <img src="/static/frontend/demo_images/category/1280/029-alv-esp12-1.png" alt="Wicked Color Airbrush Paint: 6-Color Set" />
                {else}
                    <img src="/static/frontend/demo_images/category/1280/alv-1334d-1.png" alt="Wicked Color Airbrush Paint: 6-Color Set" />
                {/if}
            </a>
            <a href="#" class="button yellow-white button-quick-view hide">quick view</a>
        </div>
        <div class="info_container container">
            <h4 class="title">
                <a href="#">
                    Wicked Color Airbrush Paint: 6-Color Set, Primary
                </a>
            </h4>
            <div class="sku show-for-large">
                <span class="value">
                    SKU: MFW-1275
                </span>
                <a data-tooltip class="has-tip right " title="What is SKU">?</a>
            </div>
            <div class="description">
                Princeton Neptune Series 4750
                Synthetic Squirrel Brushes is
                Princeton's thirstiest brush
                ever Princeton Neptune Series 4750.

                <a href="#" class="item__description_see-details hidden-xs">See details</a>

            </div>

            <div class="parameters show-for-medium">
                <ul class="no-bullet">
                    <li>Pain type: Watercolor</li>
                    <li>Hair: Synthetic</li>
                    <li>Form: Angular</li>

                    {if rand(1,2) > 1}
                        <li>Sizes: 4, 8, 12, 16, 20, 32</li>
                    {else}
                        <li>Colors:
                            <i class="color-box color-box_light-green"></i>
                            <i class="color-box color-box_blue"></i>
                            <i class="color-box color-box_purple"></i>
                            <i class="color-box color-box_orange"></i>
                            <i class="color-box color-box_red"></i>
                            <i class="color-box color-box_white"></i>
                            <i class="color-box color-box_grey"></i>
                            <a href="#" class="show-all-link">Show all</a>
                        </li>
                    {/if}
                </ul>
            </div>


            <div class="price hide-for-large">
                <span class="old">US$ 25.50</span>
                <span class="current">US$ 15.48</span>
            </div>
        </div>


        <div class="cart_price_container container">
            <div class="price_container">
                <span class="old">List Price: <span class="price">US$ 19.00</span></span>
                <span class="current">Price: <span class="price">US$ 234.01</span></span>
            </div>
            <div class="cart_quantity">
                <label for="quantity-{$productid}" class="show-for-large">
                    <span class="show-for-xlarge">Quantity:</span>
                    <span class="show-for-large-only">Qty:</span>
                </label>

                <div class="quantity-group">
                    <span class="btn dec">-</span>
                    <input type="number" name="quantity" min="1" max="9999" value="1" id="quantity-{$productid}" />
                    <span class="btn inc active">+</span>
                </div>
            </div>

            <div class="cart_add">
                <a href="#" class="add button yellow">Add to cart</a>
            </div>
            {*<div class="subtotal_container">*}
                {*<div class="subtotal">*}
                    {*Subtotal: US$ 400.01*}
                {*</div>*}
                {*<div class="safe">*}
                    {*Save 41% (US$ 5.27 per unit)*}
                {*</div>*}
            {*</div>*}


        </div>
</div>