{extends "checkout/base_one_page.tpl"}
{block 'content'}
    {raw $shippingForm->renderBegin([
    'action' => $.app->router->url('checkout:shipping'),
    'method' => 'POST',
    'class' => 'checkout-shipping-form'
    ])}
    <section class="checkout-shipping">
        <div class="row">
            <div class="columns small-12 large-4">
                <div class="row">
                    <div class="columns small-12">
                        {* shipping address form *}
                        {* shipping address form -- header *}
                        <div class="options">
                            <h2 class="title">{t 'Shipping Address' }</h2>
                        </div>
                        <div class="checkout-mandatory checkout__mandatory">
                            {t 'The fields marked with' }
                            <span class="common-label_required checkout-mandatory__required"></span> {t 'are mandatory.' }
                        </div>

                        {* shipping address form -- fields *}
                        {set $fieldsets = $shippingForm->createFieldsets()}
                        {foreach array_slice($fieldsets['shipping'], 0, 3) as $field}
                            {if $field->getName() === 's_address' }
                                <div class="tumbler-field-wrapper">
                                    {raw $field->render()}
                                    <span class="toggle-other-fields tumbler-field-wrapper_button">+</span>
                                </div>
                            {else}
                                {raw $field->render()}
                            {/if}
                        {/foreach}
                        <div class="checkout-shipping-other-fields">
                            {foreach array_slice($fieldsets['shipping'], 3) as $field}
                                {if $field->getName() === 's_address_2' }{/if}
                                {raw $field->render()}
                            {/foreach}
                        </div>

                        {* contact information form *}
                        {* contact information form -- header *}
                        <div class="contact-options">
                            <h2 class="title">{t 'Contact Information' }</h2>
                        </div>
                        {* contact information form -- fields *}
                        {foreach $fieldsets['contact'] as $field}
                            {raw $field->render()}
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="columns small-12 large-8">
                <style>
                    .cart-table-caption {
                        padding: 1.375rem 0;
                        text-align: center;
                        font-size: .9375rem;
                        font-weight: 700;
                    }


                    @media print, screen and (min-width: 23.125em) {
                        .cart-table-caption {
                            font-size: 1.125rem;
                        }
                    }

                    @media print, screen and (min-width: 45em) {
                        .cart-table-caption {
                            font-size: 1.25rem;
                            padding: 2.125rem 0 1.5rem;
                        }
                    }

                    @media print, screen and (min-width: 64em) {
                        .cart-table-caption {
                            font-size: 1.125rem;
                            padding: 1.875rem 0 1.375rem;
                        }
                    }
                </style>
                {* distributor carts *}
                {foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
                    {set $items = $group.items}
                    {set $warehouse = $.get_warehouse($gi) }
                    <div class="warehouse_products">
                        <div class="distributor-cart">
                            <div class="cart-table-caption">
                                <span class="cart-show-switcher cart-show-switcher_text">{t 'The items'}</span> {t 'below will be shipped from warehouse in'} {$warehouse->m_city},
                                {if $config.show_full_state_country === 'Y'}{$warehouse->state_model}{else}{$warehouse->m_state}{/if},
                                {if $config.show_full_state_country === 'Y'}{$warehouse->country_model}{else}{$warehouse->m_country}{/if}
                                <span class="cart__button-switcher cart-show-switcher cart-show-switcher_button">+</span>
                            </div>
                            <div class="table">
                                <div class="table-row table-row__head table-head show-for-large">
                                    <div class="table-column cart-column-image"></div>
                                    <div class="table-column cart-column-name cart-column-name__header">
                                        {t 'Item name / SKU' }
                                    </div>

                                    <div class="table-column cart-column-remove"></div>

                                    <div class="table-column cart-column-quantity">
                                        {t 'Quantity' }
                                    </div>

                                    <div class="table-column cart-column-multiply-sign"></div>

                                    <div class="table-column cart-column-price">
                                        {t 'Unit price' }
                                    </div>
                                </div>

                                <div class="table-body">
                                    {foreach $items as $key=>$position}
                                        <div
                                                class="table-row table-row_checkout"
                                                data-key="{$key}"
                                                data-wh="{$gi}"
                                                data-product='{$position->object->productid}'
                                                data-quantity="{$position->quantity}"
                                                data-price="{$position->object->getFrontendPrice($position->quantity)}"
                                                data-prices='{$position->object->getPrices()|json_encode}'
                                                data-cart-action="{url 'cart:quantity:set:post' key=$key}"
                                        >
                                            <div class="table-column cart-column-image image">
                                                {include 'catalog/parts/_item_image.tpl' model=$position->object class='cart-item-image'}
                                            </div>

                                            <div class="table-column cart-column-name">
                                                <a class="cart-item-title-link" href="{$position->object->getAbsoluteUrl()}">
                                                    {$position->object}
                                                </a>

                                                <div class="cart-item-sku show-for-medium">
                                                    {t 'SKU' }:
                                                    {$position->object->productcode}
                                                </div>

                                                {if $position->data}
                                                    <div class="options">
                                                        {include '_parts/_options.tpl' options=$position->data}
                                                    </div>
                                                {/if}

                                            </div>

                                            <div class="cart-item-remove-button cart-column-remove show-for-large">
                                                <a href="{url 'cart:delete' key=$key}" title="{t 'Delete' }" class="icon cart_remove" onclick="loader.load(this)">
                                                    {include 'cart/_close_icon.tpl'}
                                                </a>
                                            </div>

                                            <div class="table-wrapper cart-column-quantity quantity-extended">
                                                <div class="table-column quantity">
                                                    <div class="inline-block">
                                                        <div class="quantity-group">
                                                            <a href="{url 'cart:quantity:dec' key=$key}" class="btn active dec">-</a>
                                                            <input type="number" name="quantity"
                                                                   min="{$position->object->min_amount}"
                                                                   max="{$position->object->avail}"
                                                                   step="{if $position->object->mult_order_quantity == 'Y'}{$position->object->min_amount}{else}1{/if}"
                                                                   value="{$position->quantity}">
                                                            <a href="{url 'cart:quantity:inc' key=$key}" class="btn active inc">+</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-column cart-column-multiply-sign">x</div>

                                            <div class="table-column cart-column-price price show-for-large format_price">
                                                {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}
                                                <span class="price" var-price>{$site_currency->getCurrencyFormat($position->object->getFrontendPrice($position->quantity))}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                                            </div>

                                            <dziv class="table-column remove hide-for-medium">
                                                <a href="{url 'cart:delete' key=$key}" title="{t 'Delete' }" class="icon cart_remove" onclick="loader.load(this)">
                                                    {include 'cart/_close_icon.tpl'}
                                                </a>
                                            </dziv>
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                            {include 'checkout/shipping_methods_one_page.tpl'}
                        </div>
                    </div>
                    <div class="warehouse_subtotal wh_{$gi}"
                         data-wh="{$gi}"
                         data-minamount="{$warehouse->getMinimalAmount()}"
                    >
                        <div class="table">
                            <div class="table-body">
                                <div class="table-row table-row_subtotal">
                                    <div class="table-column extended_remove format_price">
                                        {t 'Subtotal' }: {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}
                                        <span class="wh_{$gi}_subtotal subtotal" var-group-subtotal>{$site_currency->getCurrencyFormat($group.subtotal)}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="errors">
                            {if $warehouse->getMinimalAmount()}
                                {p_label cls="err fill minimal-amount " ~ ($warehouse->checkMinimalAmount($group.subtotal) ? 'hide': '')}
                                {t 'The minimum order amount for this product line is'} {$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} {$site_currency->getCurrencyFormat($warehouse->getMinimalAmount())}{if $site_currency->after}&nbsp;{$site_currency}{/if}
                                {/p_label}
                            {/if}
                            {set $only_one_country = $warehouse->getShippingOnlyOneCountry()}
                            {if $only_one_country}
                                {p_label cls="err fill last-items"}
                                {t 'This product line can only be shipped to a'} {$only_one_country} {t 'address.'}
                                {/p_label}
                            {/if}
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>

        <div class="row show-for-large">
            <div class="small-12 columns">
                <div class="hr"></div>
            </div>
        </div>

        <div class="row">
            <div class="columns small-12">
                <div class="subscription-options">
                    <h5 class="title">{t 'Privacy Policy' }</h5>
                    <div class="private-claim">
                        123
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-center">
            <div class="column small-12">
                <div class="buttons text-center">
                    <button type="submit" class="button submit yellow waves waves-orange waves-effect">
                        {t 'Submit' }
                    </button>
                </div>
            </div>
        </div>

        <div class="row align-center">
            <div class="column small-12">
                <div class="submit-notes text-center hint">
                    {t 'Submit and proceed to shipping & payment options.' }
                </div>
            </div>
        </div>

    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        /*cart item*/
        .cart-item-image {
            max-height: 6.25rem;
            max-width: 100%;
        }

        .cart-item-sku {
            color: #28862f;
            text-transform: uppercase;
            font-weight: 400;
        }

        .cart-item-title-link {
            color: black;
        }

        .cart-item-remove-button {
            display: flex;
        }

        /*car columns*/
        .cart-column-image {
            width: 100px;
        }

        .cart-column-name {
            width: 250px;
        }

        .cart-column-name__header {
            text-align: center;
        }

        .cart-column-remove {
            width: 100px
        }

        .cart-column-multiply-sign {
            text-align: center;
            width: 10px;
        }

        .cart-column-quantity {
            width: 135px;
            text-align: center;
            padding-right: 40px;
        }

        .cart-column-price {
            width: 110px;
            text-align: right;
        }

        /*table row*/
        .table-row {
            padding: .75rem 45px .75rem 0;
            min-height: 6.5625rem;
            border-top: 1px solid #e1e1e1;
            border-bottom: 1px solid #e1e1e1;
            align-items: center;
            justify-content: space-between;
        }

        .table-row_subtotal {
            justify-content: flex-end;
            color: #83132B;
            border: none;
            padding: 28px 45px 38px 0;
            height: initial;
            min-height: initial;
        }

        .table-row_checkout {
            border-bottom: none;
        }

        .table-row__head {
            height: 40px;
            line-height: 40px;
            background: #ebebeb;
            min-height: initial;
            padding: 0 45px 0 0;
        }

        .cart-show-switcher {
            cursor: pointer;
            user-select: none;
        }

        .cart-show-switcher_text {
            color: #055A93;
            border-bottom: 2px dashed #055A93;
        }

        .cart-show-switcher_button {
            width: 10px;
            display: inline-block;
        }

        .cart__button-switcher {
            position: absolute;
            right: 45px;
            font-size: 24px;
            line-height: 24px;
        }

        .cart-table-caption {
            position: relative;
        }

        @media screen and (min-width: 45em) {
            .cart-item-name-quantity {
                justify-content: space-between;
            }
        }
    </style>
    {raw $shippingForm->renderEnd()}
{/block}

{block 'js'}
    <script>
        /* переключатели */
        class Switcher {
            _isOn = false;

            constructor( elem, onAction, offAction, callback ) {
                this.$button = typeof elem === 'string' ? $( elem ) : elem;
                this.onAction = onAction;
                this.offAction = offAction;
                this.callback = callback;

                const self = this;

                this.$button.click( function ( event ) {
                    self.toggle( event );
                } );
            }

            set isOn( value ) {
                if ( typeof value !== 'boolean' ) {
                    throw new Error( 'isOn expected type boolean, passed ' + typeof value );
                }

                this._isOn = value;
            }

            get isOn() {
                return this._isOn;
            }

            toggle( event ) {
                this._isOn = !this._isOn;

                if ( this._isOn === true ) {
                    this.$button.addClass( 'tumbler-button__on' );
                    this.$button.removeClass( 'tumbler-button__off' );
                    this.onAction();
                } else {
                    this.$button.addClass( 'tumbler-button__off' );
                    this.$button.removeClass( 'tumbler-button__on' );
                    this.offAction();
                }

                if ( this.callback ) {
                    this.callback();
                }
            }
        }

        class TumblerButton extends Switcher {
            /**
             * @param elem - string or jquery object
             * @param onAction
             * @param offAction
             * @param callback
             */
            constructor( elem, onAction, offAction, callback ) {
                super( elem, onAction, offAction, callback );
                this.toggleCaption();
            }

            set isOn( value ) {
                this._isOn = value;
                this.toggleCaption();
            }

            get isOn() {
                return this._isOn;
            }

            toggleCaption() {
                this.$button.text( this._isOn === true ? '–' : '+' );
            }

            toggle( event ) {
                super.toggle( event );
                this.toggleCaption();
            }
        }

        /* shipping форма */
        $( function () {
            ( function () {
                class Checkout {
                    constructor() {
                        const self = this;

                        this.$otherFields = $( '.checkout-shipping-other-fields' );

                        new TumblerButton( '.toggle-other-fields', function () {
                            self.$otherFields.stop( true, false ).slideDown();
                        }, function () {
                            self.$otherFields.stop( true, false ).slideUp();
                        } );

                        /* change delivery address */
                        $( '#CheckoutForm_s_state, #CheckoutForm_s_zipcode, #CheckoutForm_s_city, #CheckoutForm_s_country' ).change( function () {
                            Pace.ignore( function () {
                                $.ajax( {
                                    method: 'GET',
                                    url: '/api/shipping-methods',
                                    data: {
                                        state: $( '#CheckoutForm_s_state' ).attr( 'data-code' ),
                                        zipcode: $( '#CheckoutForm_s_zipcode' ).val(),
                                        city: $( '#CheckoutForm_s_city' ).val(),
                                        country: $( '#CheckoutForm_s_country' ).attr( 'data-code' ),
                                    },
                                    success: function ( res ) {
                                        console.log( res );
                                    },
                                    error: function ( err ) {
                                        console.log( err );
                                    }
                                } );
                            } );
                        } );
                    }
                }

                return new Checkout();
            } )();
        } );
    </script>
    <script>
        /* корзины для разных dx */
        $( '.distributor-cart' ).each( function ( i, e ) {
            const $cart = $( e );
            const $table = $cart.find( '.table' ).hide();
            const $textSwitcher = $cart.find( '.cart-show-switcher_text' );
            const $buttonSwitcher = $cart.find( '.cart-show-switcher_button' );
            const $images = $( '.cart-item-image' );

            const showTable = function () {
                $images.each( function ( i, e ) {
                    LazyLoad.load( e );
                } );
                $table.stop( true, false ).slideDown();
            };

            const hideTable = function () {
                $table.stop( true, false ).slideUp();
            };

            const switcherButton = new TumblerButton( $buttonSwitcher, showTable, hideTable, function () {
                switcherText.isOn = switcherButton.isOn;
            } );

            const switcherText = new Switcher( $textSwitcher, showTable, hideTable, function () {
                switcherButton.isOn = switcherText.isOn;
            } );
        } );
    </script>
{/block}
