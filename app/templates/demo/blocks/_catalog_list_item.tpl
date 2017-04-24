<div class="item">
        <div class="image_container container">
            <a href="#">
                {*{if rand(1,2) > 1}*}
                    {*<span class="item__rect-sale_yellow hidden-xs hidden-sm hidden-md">Sale</span>*}
                {*{/if}*}

                {*{if rand(1,2) > 1}*}
                    {*<span class="item__circle-new_red hidden-xs hidden-sm hidden-md">New</span>*}
                {*{/if}*}

                {if rand(1,2) > 1}
                    <img src="/static/frontend/demo_images/category/1280/029-alv-esp12-1.png" class="item__pic" alt="Wicked Color Airbrush Paint: 6-Color Set" width="190" height="204" />
                {else}
                    <img src="/static/frontend/demo_images/category/1280/alv-1334d-1.png" class="item__pic" alt="Wicked Color Airbrush Paint: 6-Color Set" width="134" height="218" />
                {/if}
            </a>
            <a href="#" class="button button-quick-view hide">quick view</a>
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
                <a data-tooltip class="has-tip top " title="What is SKU">?</a>
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
                <span class="old">List Price:  <span class="price">US$ 19.00</span></span>
                <span class="current">Price:  <span class="price">US$ 234.01</span></span>
            </div>
            <div class="cart_quantity">
                <label for="quantity" class="hidden-sm hidden-md"><span class="full-q">Quantity:</span><span class="short-q">Qty:</span></label>
                <div class="btn-group">
                    <a href="" class="btn quantity_modify quantity_dec">-</a>
                    <input type="number" min="1" max="9999" class="btn quantity_input" name="quantity" id="quantity" value="1" />
                    <a href="" class="btn quantity_modify quantity_inc active">+</a>
                </div>
            </div>
            <div class="cart_add">
                <a href="#" class="item__info-buy_add-button with-text">Add to cart</a>
                <a href="#" class="item__info-buy_add-button no-text hidden-sm hidden-md hidden-lg"></a>
            </div>
            <div class="item__info-buy_subtotal">
                Subtotal: US$ 400.01
            </div>
            <div class="item__info-buy_save">
                Save 41% (US$ 5.27 per unit)
            </div>
        </div>
</div>