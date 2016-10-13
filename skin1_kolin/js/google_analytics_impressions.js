sendItems = [];
sendItemsValues = [];
sentItems = [];

function collectVisibleElements(obj) {

    var t = obj.visible(false, false),
        wraper_width = obj.parent().parent().width(),
        ul_left = Math.abs(obj.parent().position().left),
        el_left = obj.position().left;
    if (t && ((el_left >= ul_left) && ((ul_left + wraper_width) > el_left))) {
        var productid = obj.data('productid');
        if (sendItems.indexOf(productid) === -1 && sentItems.indexOf(productid) === -1) {
            sendItems.push(productid);
            sendItemsValues.push({
                productid: productid,
                name: obj.data('name'),
                category: obj.data('category'),
                brand: obj.data('brand'),
                list: obj.data('list'),
                price: obj.data('price'),
                position: obj.data('position')
            });
        }
    }
}
function sendGoogleAnalitics()
{
    var counter = 0;
    while (sendItems.length > 0) {
        counter++;
        var productid = sendItems.pop();
        var valtosend = sendItemsValues.pop();
        ga('ec:addImpression', {
            'id': valtosend.productid,
            'name': valtosend.name,
            'category': valtosend.category,
            'brand': valtosend.brand,
            'list': valtosend.list,
            'price': valtosend.price,
            'position': valtosend.position
        });

        sentItems.push(productid);
    }

    if (counter > 0) {
        console.log(ga);
        ga('send', 'pageview');
    }

}

function checkCarouselsVisibility() {
    $('#similar_products .jcarousel, #related_products .jcarousel, #products_also_bought_with_this_product .jcarousel, #recently_viewed_products .jcarousel, .product_list_row').each(function () {
        $('.google_impression_object::visible', $(this)).each (function () {
            collectVisibleElements($(this))
        });
    });

    sendGoogleAnalitics();
}

$(window).scroll(function(){
    checkCarouselsVisibility()
});
