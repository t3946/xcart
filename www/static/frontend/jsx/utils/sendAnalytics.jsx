export default class sendAnalytics {
    addToCart(product) {
        window.dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
        window.dataLayer.push({
            'event': 'addToCart',
            'ecommerce': {
                'currencyCode': product.dataset.currncy || 'USD',
                'add': {                                // 'add' actionFieldObject measures.
                    'products': [{                        //  adding a product to a shopping cart.
                        'name': product.dataset.name || '',
                        'id':  product.dataset.product,
                        'price': product.dataset.price,
                        'brand': product.dataset.brand || '',
                        'category': product.dataset.category || '',
                        'quantity': product.dataset.quantity  || 1
                    }]
                }
            }
        });
    }
}