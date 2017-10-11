<div class="prices__container">

    <div class="price__quantity">
        <div class="row">
            <div class="column small-12">

               <div class="table table__prices table__prices--top">
                    <div class="column price">
                        <div class="title">Price</div>
                        <div class="value">
                            US$ <span var-price>{$model->getFrontendPrice()}</span>
                        </div>
                    </div>

                    <div class="column quantity">
                        <div class="title">Quantity</div>
                        <div class="value">

                        {if !$model->isOutOfStock()}
                            {include "product/parts/_quantity_group.tpl"}
                        {else}
                            Out of stock
                        {/if}
                        </div>
                    </div>

                   {if !$model->isOutOfStock()}
                        <div class="column extended">
                            <div class="title">Subtotal</div>
                            <div class="value">
                                US$ <span var-price-extended>{$model->getFrontendPrice()}</span>
                            </div>
                        </div>

                           <div class="column auto">
                            <div class="title"></div>
                            <div class="value">

                                <div class="cart_add">
                                    <a class="add button waves waves-orange yellow">
                                        <span class="text">
                                            Add to cart
                                        </span>
                                    </a>
                                </div>

                            </div>
                        </div>
                   {else}

                        <div class="column notify auto">
                            <div class="title"></div>
                            <div class="value">

                            </div>
                        </div>

                   {/if}
               </div>


            </div>
        </div>

        <div class="row">
            <div class="column small-12 large-6">

                {if !$model->isOutOfStock()}
                <div class="table table__prices table__prices--down">
                    {foreach $model->getPrices() as $quantity => $price last=$last}
                        {if $quantity == 1}{continue}{/if}

                        {if $last_quantity!}
                            {set $ql = ($quantity-1 == $last_quantity) ? $last_quantity : "{$last_quantity} - {$quantity - 1}"}
                            {include "product/price/_price_table_row.tpl" quantity=$last_quantity price=$last_price quantity_line = $ql}
                        {/if}

                        {if $last}
                            {include "product/price/_price_table_row.tpl" quantity=$quantity price=$price quantity_line = "{$quantity}+"}
                        {/if}

                        {set $last_quantity = $quantity}
                        {set $last_price = $price}
                    {/foreach}
                </div>
                {/if}

            </div>
        </div>
    </div>

</div>

