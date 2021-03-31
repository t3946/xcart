import { SwitcherButton } from "@/js/Classes/SwitcherButton";
import { Switcher } from "@/js/Classes/Switcher";

export const DistributorCart = ( function () {
    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    /**
     * add show/hide buttons for cart table
     */
    $( '.distributor-cart' ).each( function ( i, e ) {
        const $cart = $( e );
        const $table = $cart.find( '.table' );
        const $textSwitcher = $cart.find( '.cart-show-switcher_text' );
        const $buttonSwitcher = $cart.find( '.switcher-button' );
        const $images = $( '.cart-item-image' );
        const $cartCaption = $cart.find( '.cart-table-caption' );

        function getAnimationDuration() {
            return $table.find( '.cart-table-row' ).length <= 7 ? 500 : 750;
        }

        const showTable = function () {
            $images.each( function ( i, e ) {
                LazyLoad.load( e );
            } );
            $table.stop( true, false ).slideDown( getAnimationDuration() );
        };

        const hideTable = function () {
            $table.stop( true, false ).slideUp( getAnimationDuration() );
        };

        const switcherButton = new SwitcherButton( $buttonSwitcher, showTable, hideTable, function ( e ) {
            e.stopPropagation();
            switcherCaption.isOn = switcherText.isOn = switcherButton.isOn;
        } );

        const switcherText = new Switcher( $textSwitcher, showTable, hideTable, function ( e ) {
            e.stopPropagation();
            switcherCaption.isOn = switcherButton.isOn = switcherText.isOn;
        } );

        const switcherCaption = new Switcher( $cartCaption, showTable, hideTable, function ( e ) {
            e.stopPropagation();
            switcherButton.isOn = switcherText.isOn = switcherCaption.isOn;
        } );

        switcherCaption.isOn = switcherButton.isOn = switcherText.isOn = true;
    } );

    /**
     * recalculate carts totals
     */
    function recalc() {
        const page_cart = document.querySelector( '.cart-page, .checkout-page' );

        let products = page_cart.querySelectorAll( '[data-product]' );
        if ( products ) {
            let subtotals = Object.create( null ), fullquantity = 0;
            subtotals.wh = Object.create( null );
            subtotals.cart = 0;

            for ( let i = 0; products.length > i; ++i ) {
                let product = products[ i ];
                let quantity = parseInt( product.dataset.quantity );
                let subtotal = product.dataset.price * quantity;

                fullquantity += quantity;

                subtotals.cart += subtotal;
                subtotals.wh[ product.dataset.wh ] = subtotal + ( subtotals.wh[ product.dataset.wh ] || 0 );
            }

            let whs = page_cart.querySelectorAll( '.warehouse_subtotal' );

            for ( let i = 0; whs.length > i; ++i ) {
                let wh = whs[ i ];
                wh.querySelector( '.subtotal' ).innerHTML = toLocaleCurrency( subtotals.wh[ wh.dataset.wh ] );
            }

            page_cart.querySelector( '.cart_subtotal' ).innerHTML = toLocaleCurrency( subtotals.cart );
            page_cart.dataset.total = subtotals.cart;
            page_cart.dataset.quantity = fullquantity;
        }
    }

    /**
     * remove item from cart handler
     */
    $( 'a.cart-remove-item-button' ).click( function ( e ) {
        e.preventDefault();

        Pace.ignore( function () {
            const $target = $( e.currentTarget );

            $.ajax( {
                url: '/api/checkout/update',
                data: {
                    uid: $target.attr( 'href' ).split('/').pop(),
                    quantity: 0,
                },
                method: 'POST',
                success: function ( res ) {
                    let $row = $target;

                    while ( $row.length && !$row.hasClass( 'cart-table-row' ) ) {
                        $row = $row.parent();
                    }

                    const productRemovedMessage = $( '.checkout-page' ).data( 'product-removed' );

                    window.addFlashMessage( productRemovedMessage, 'success', true );

                    // removed last product in some cart
                    if ( $row.parent().children().length === 1 ) {
                        const $warehouse = $row.parent().parent().parent().parent();
                        const $warehouseList = $warehouse.parent();

                        $warehouse.animate( {
                            height: 0,
                            opacity: 0,
                            paddingTop: 0,
                            paddingBottom: 0,
                        }, 250, function () {
                            $warehouse.remove();

                            // removed last product in last cart
                            if ( $warehouseList.find( '.warehouse_products' ).length === 0 ) {
                                window.location.href = '/';
                            }
                        } );

                        return;
                    }

                    $row.animate( {
                        height: 0,
                        opacity: 0,
                        paddingTop: 0,
                        paddingBottom: 0,
                    }, 250, function () {
                        $row.remove();
                    } );
                },
            } );
        } );
    } );
} )();
