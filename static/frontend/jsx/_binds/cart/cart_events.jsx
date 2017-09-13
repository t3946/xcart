import CountUp from 'countUp.js';
import storeCart from '../../stores/StoreCart';

(()=>{
    window['addToCart'] = (data, callback) => {
        storeCart.dispatch({type:'PUSH', action: 'ADD', callback:callback, data:{items: data}});
    };

    let productItemResetState = ($products) => {
        let $input = $products.find('.quantity-group input');
        let val = $input.attr('min');

        $input.val(val);
        $products.data('quantity', val);

        for (let i = 0, len = $products.length; i < len; i++) {
            // let $product = $($products[i]);

            // $product.data('quantity', undefined);
            // let $input = $product.find('.quantity-group input');
            // $input.val($input.attr('min'));

            $(document).trigger('component.quantity.change', {
                target: $products[i],
                val: val
            });
        }
    };

    $(document)
        .on('click','.cart_add .button', (e) =>{
            e.preventDefault();

            let $product = $(e.target).closest('[data-product]');
            if ( $product.length ) {
                let data = [{id: $product.data('product'), quantity: $product.data('quantity') || 1}];

                window.addToCart(data, ()=>{ productItemResetState($product); });
            }

        })
        .on('click', '.group_cart_add .button', (e) => {
            e.preventDefault();

            let $products = $(e.target)
                .closest('[data-product-group]')
                .find('[data-product]');

            if ( $products.length ) {

                let data = [];

                for (let i = 0, len = $products.length; i < len; i++) {
                    if ($products[i].data('quantity')) {
                        data.push({
                            id: $products[i].data('product'),
                            quantity: $products[i].data('quantity'),
                        });
                    }
                }


                window.addToCart(data, ()=>{ productItemResetState($products); });
            }

        })
        .on('store.cart.update', (e, data) => {
            let qNew = data.state.cart.quantity;
            let qPrev = data.prevState.cart.quantity;

            if (qNew > 99) {
                $('.mc_count').addClass('small');
            }
            else {
                $('.mc_count').removeClass('small');
            }

            $('.mc_count').html(qNew);

            (new CountUp('desktop-cart-quantity', qPrev, qNew,0, 1, {useEasing: true})).start();
        });
})();