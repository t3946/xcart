<div class="prices__container">

    {if !$model->isOutOfStock()}
        <div class="row">
            <div class="columns small-12 hide-for-medium">
                <div class="cart_add">
                    <a class="add button waves waves-orange yellow">
                    <span class="text">
                        Add to cart
                    </span>
                    </a>
                </div>
            </div>
        </div>
    {/if}

    <div class="price__quantity">
        <div class="row">
            <div class="column small-12">

               <div class="table table__prices table__prices--top">
                    <div class="column price">
                        <div class="title">Price</div>
                        <div class="value price-value">
                            US$ <span class="price" var-price>{$model->getFrontendPrice()|number_format:2}</span>
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
                            <div class="value extended-value">
                                US$ <span class="price" var-price-extended>{$model->getFrontendPrice()|number_format:2}</span>
                            </div>
                        </div>

                        <div class="column auto hide-for-small show-for-medium">
                            <div class="title"></div>
                            <div class="value ">
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
                            <div class="value"></div>
                        </div>
                   {/if}
               </div>
            </div>
        </div>

        {if !$model->isOutOfStock()}
        <div class="row">
            <div class="column small-12 large-7 price-row-width xl-6">
                <div class="table table__prices table__prices--down price-row-width">
                    {foreach $model->getPrices() as $quantity => $price last=$last}
                        {if $quantity == 1}{continue}{/if}

                        {if $last_quantity!}
                            {set $max_q = ($quantity > $model->avail) ? $model->avail : $quantity -1}
                            {set $ql = ($max_q == $last_quantity) ? $last_quantity : "{$last_quantity} - {$max_q}"}

                            {include "product/price/_price_table_row.tpl" quantity=$last_quantity price=$last_price quantity_line = $ql}
                        {/if}

                        {if $quantity > $model->avail}{break}{/if}

                        {if $last}
                            {include "product/price/_price_table_row.tpl" quantity=$quantity price=$price quantity_line = "{$quantity}+"}
                        {/if}

                        {set $last_quantity = $quantity}
                        {set $last_price = $price}
                    {/foreach}
                </div>

            </div>



            {set $subtotal_hide = ($model->list_price > $model->getFrontendPrice())}
            {set $price_safe = ($model->list_price - $model->getFrontendPrice())}

            <div class="column large-5 xl-6 hide-for-small show-for-medium auto">
                <div class="subtotal_container {if !$subtotal_hide}hide{/if}" cont-subtotal>
                    {if $subtotal_hide}
                        <div class="safe-prices list-price">
                            <div class="title">
                                List Price:
                            </div>
                            <div class="value">
                                US$ <span class="price" var-price-list>{$model->list_price|number_format:2}</span>
                            </div>
                        </div>
                    {/if}

                    <div class="safe-prices safe safe-per-item">
                        <div class="title">
                            Per item saving:
                        </div>
                        <div class="value">
                            US$ <span class="price" var-price-perunit-safe>{$price_safe|number_format:2}</span>
                        </div>
                    </div>

                    <div class="safe-prices safe total-safe">
                        <div class="title">
                            Total saving:
                        </div>
                        <div class="value">
                            US$ <span class="price" var-price-safe>{$price_safe|number_format:2}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {/if}
    </div>



</div>

