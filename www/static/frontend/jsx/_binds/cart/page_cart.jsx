import storeCart from 'stores/StoreCart';

(()=>{
    let cart_container = document.querySelector('.cart-page');
    if (cart_container) {
        storeCart.dispatch({type:'FETCH'});
    }
})();