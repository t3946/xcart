var sendItems = [];
var sendItemsValues = [];
var sentItems = [];

(function() {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame']
            || window[vendors[x]+'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function(callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function(id) {
            clearTimeout(id);
        };
}());

function collectVisibleElements(obj) {

    var t = obj.visible(false, false);
    if (t) {
        var po = obj.parent(),
        wraper_width = po.parent().width(),
        ul_left = Math.abs(po.position().left),
        el_left = obj.position().left;
        if ((el_left >= ul_left) && ((ul_left + wraper_width) > el_left)) {
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
}
function sendGoogleAnalitics()
{
    var counter = 0;
    var listtype = '';
    while (sendItems.length > 0) {
        counter++;
        var productid = sendItems.pop();
        var valtosend = sendItemsValues.pop();
        listtype = valtosend.list;
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
        ga('send', 'event', listtype, 'scroll', listtype + ' item');
    }

}

function checkCarouselsVisibility() {
    $('.jcarousel, .product_list_row, .mobile_products_list')
        .find('.google_impression_object').each(function () {
            collectVisibleElements($(this))
    });
    sendGoogleAnalitics();
}

$(window).scroll(function(){
    requestAnimationFrame(checkCarouselsVisibility);
});
$( document ).ready(function() {
    requestAnimationFrame(checkCarouselsVisibility);
});
