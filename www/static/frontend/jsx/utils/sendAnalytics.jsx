export default class sendAnalytics {

    constructor(options = {}) {

    }

    addToCart(product) {
        if (window['ga']) {
            window.ga('ec:addProduct', {
                'id': product.dataset.product,
                'name': product.dataset.name || '',
                'category': product.dataset.category || '',
                'brand': product.dataset.brand || '',
                'price': product.dataset.price,
                'quantity': product.dataset.quantity  || 1
            });
            window.ga('ec:setAction', 'add', {list: product.dataset.source});
            window.ga('send', 'event', 'UX', 'click', 'Add to cart');
        }
    }

}