<div class="item">
    <div class="row">
        <div class="col col-xs-24 col col-sm-14 col col-md-11 col col-lg-12 item__pic-container">
            <a href="#">
                {if rand(1,2) > 1}
                    <span class="item__rect-sale_yellow hidden-xs hidden-sm hidden-md">Sale</span>
                {/if}

                {if rand(1,2) > 1}
                    <span class="item__circle-new_red hidden-xs hidden-sm hidden-md">New</span>
                {/if}

                {if rand(1,2) > 1}
                    <img src="/static/frontend/demo_images/category/1280/029-alv-esp12-1.png" class="item__pic" alt="Wicked Color Airbrush Paint: 6-Color Set" width="190" height="204" />
                {else}
                    <img src="/static/frontend/demo_images/category/1280/alv-1334d-1.png" class="item__pic" alt="Wicked Color Airbrush Paint: 6-Color Set" width="134" height="218" />
                {/if}
            </a>
            <a href="#" type="button" data-toggle="modal" data-target="#featured_product1_view" class="item__quick-view hidden-xs hidden-sm hidden-md hidden-lg">quick view</a>
        </div>
        <div class="col col-xs-36 col col-sm-28 col col-md-34 col col-lg-34 item__info-container">
            <h4 class="item__caption"><a href="#">Wicked Color <span class="highlighted__yellow">Airbrush Paint</span>: 6-Color Set<span class="item__caption_type hidden-xs">, Primary</span></a></h4>
            <div class="item__sku hidden-xs hidden-sm hidden-md">
                                                        <span class="item__sku_value">
                                                            SKU: MFW-1275
                                                        </span>
                <a tabindex="0" class="item__sku_what-is" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="What is SKU">?</a>
            </div>
            <div class="item__description">
                Princeton Neptune Series 4750
                Synthetic Squirrel Brushes is
                Princeton's thirstiest brush
                <div class="item__description_extend hidden-xs">
                    ever Princeton Neptune Series 4750.
                </div>
                <span class="item__description_dots hidden-sm hidden-md hidden-lg">...</span>
                <a href="#" class="item__description_see-details hidden-xs">See details</a>
                <ul class="item__description_parameters hidden-xs hidden-sm">
                    <li>Pain type: Watercolor</li>
                    <li>Hair: Synthetic</li>
                    <li>Form: Angular</li>

                    {if rand(1,2) > 1}
                        <li>Sizes: 4,&emsp;8,&emsp;12,&emsp;16,&emsp;20,&emsp;32</li>
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
            <div class="item__price hidden-sm hidden-md hidden-lg">
                <span class="item__price_old">US$ 25.50</span>
                <span class="item__price_new">US$ 15.48</span>
            </div>
        </div>
        <div class="hidden-xs col col-sm-18 col col-md-15 col col-lg-14 item__info-buy">
            <div class="item__price">
                <span class="item__price_old">List Price:  <span class="l-through">US$ 19.00</span></span>
                <span class="item__price_new">Price:  <span class="bold">US$ 234.01</span></span>
            </div>
            <div class="item__info-buy_quantity">
                <label for="quantity" class="hidden-sm hidden-md"><span class="full-q">Quantity:</span><span class="short-q">Qty:</span></label>
                <div class="btn-group">
                    <a href="" class="btn quantity_modify quantity_dec">-</a>
                    <input type="number" min="1" max="9999" class="btn quantity_input" name="quantity" id="quantity" value="1" />
                    <a href="" class="btn quantity_modify quantity_inc active">+</a>
                </div>
            </div>
            <div class="item__info-buy_add">
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
</div>