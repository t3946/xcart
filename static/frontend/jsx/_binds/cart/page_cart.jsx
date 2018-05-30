import storeCart from 'stores/StoreCart';
(()=>{
    let cart_container = $('.cart-page');
    if (cart_container.length) {
        storeCart.dispatch({type:'FETCH'});

    }
})();