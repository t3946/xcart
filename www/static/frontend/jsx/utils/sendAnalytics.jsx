export default class sendAnalytics {

    constructor(options = {}) {

    }

    addToCart(product) {
        if (window['ga']) {
            window.ga('require', 'ec');
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

    sendLoadMore(lc) {
        if (window['ga']) {
            window.ga('send', {hitType: 'pageview', location: lc});
        }
    }

    productDetail(product) {
        if (window['ga']) {
            window.ga('require', 'ec');
            window.ga('ec:addProduct', {
                'id': product.dataset.product,
                'name': product.dataset.name || '',
                'category': product.dataset.category || '',
                'brand': product.dataset.brand || '',
                'price': product.dataset.price,
                'position': 1
            });
            window.ga('ec:setAction', 'detail');
        }
    }

    pageview () {
        if (window['ga']) {
            window.ga('send', 'pageview');
        }
    }

}