"use strict";

var featuredProd = $("#featured-products-block"),
    whatsNew = $("#whats-new-block"),
    topCateg = $("#top-categories-block"),
    brands = $("#brands-block"),
    promotion = $("#promotion-block"),
    itemNavSly = 'basic',
    speedSly = 300,
    moveBySly = 600,
    cycleIntervalSly = 3000,
    displayControlsSlyBreakPoint = 600,
    slyPromotionOptions = {
        horizontal: 1,
        itemNav: itemNavSly,
        speed: speedSly,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        activatePageOn: 'click',
        moveBy: moveBySly,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,
        pagesBar: promotion.find('.promotion__pages'),
        cycleBy: 'pages',
        cycleInterval: cycleIntervalSly,
        pauseOnHover:  false,
        startPaused:   false
    },
    slyFeatProdOptions = {
        horizontal: 1,
        itemNav: itemNavSly,
        speed: speedSly,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        scrollBar: featuredProd.find('.featured-products__scrollbar'),
        scrollBy: 1,
        activatePageOn: 'click',
        moveBy: moveBySly,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,
        // Buttons
        prevPage: featuredProd.find('.featured-products__controls_prev-page'),
        nextPage: featuredProd.find('.featured-products__controls_next-page')
    },
    slyWhatsNewOptions = {
        horizontal: 1,
        itemNav: itemNavSly,
        speed: speedSly,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        scrollBar: whatsNew.find('.whats-new__scrollbar'),
        scrollBy: 1,
        activatePageOn: 'click',
        moveBy: moveBySly,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,
        // Buttons
        prevPage: whatsNew.find('.whats-new__controls_prev-page'),
        nextPage: whatsNew.find('.whats-new__controls_next-page')

    },
    slyTopCategOptions = {
        horizontal: 1,
        itemNav: itemNavSly,
        speed: speedSly,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        scrollBar: topCateg.find('.top-categories__scrollbar'),
        scrollBy: 1,
        activatePageOn: 'click',
        moveBy: moveBySly,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,
        // Buttons
        prevPage: topCateg.find('.top-categories__controls_prev-page'),
        nextPage: topCateg.find('.top-categories__controls_next-page')

    },
    slyBrandsOptions = {
        horizontal: 1,
        itemNav: itemNavSly,
        speed: speedSly,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        scrollBar: brands.find('.brands__scrollbar'),
        scrollBy: 1,
        activatePageOn: 'click',
        moveBy: moveBySly,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,
        // Buttons
        prevPage: brands.find('.brands__controls_prev-page'),
        nextPage: brands.find('.brands__controls_next-page')

    },
    bannersSection = $(".banners-section"),
    promotionFrame = null,
    featuredProdFrame = null,
    whatsNewFrame = null,
    topCategFrame = null,
    brandsFrame = null,
    promotionBlockWidth;

function setPromotionItemWidth(){
    if (bannersSection) {
        promotionBlockWidth = bannersSection.find("#promotion_frame").innerWidth();
        bannersSection.find(".promotion__item").innerWidth(Math.floor(promotionBlockWidth));
    }
}

function reloadFrames(){
    if ($("#promotion_frame").length){
        promotionFrame = reloadSly(promotionFrame, '#promotion_frame', slyPromotionOptions);
    }

    if ($("#featured-prod_frame").length){
        featuredProdFrame = reloadSly(featuredProdFrame, '#featured-prod_frame', slyFeatProdOptions);
    }

    if ($("#whats-new_frame").length){
        whatsNewFrame = reloadSly(whatsNewFrame, '#whats-new_frame', slyWhatsNewOptions);
    }

    if ($("#top-categories_frame").length){
        topCategFrame = reloadSly(topCategFrame, '#top-categories_frame', slyTopCategOptions);
    }

    if ($("#brands_frame").length){
        brandsFrame = reloadSly(brandsFrame, '#brands_frame', slyBrandsOptions);
    }

}

$(window).resize(function(){
    setPromotionItemWidth();
    reloadFrames();
});

$(document).ready(function() {
    setPromotionItemWidth();
    reloadFrames();

    var framesObj = [
            {
                block: $('#featured-prod_frame'),
                frame: featuredProdFrame
            },
            {
                block: $('#whats-new_frame'),
                frame: whatsNewFrame
            },
            {
                block: $('#top-categories_frame'),
                frame: topCategFrame
            },
            {
                block: $('#brands_frame'),
                frame: brandsFrame
            }
        ];

    framesObj.forEach(function(item, i, arr){
        var controls = item.block.siblings(".slider-horizontal__controls"),
            scrollbar = item.block.siblings(".slider-horizontal__scrollbar").find(".slider-horizontal__scrollbar_handle");

        if (item.frame && item.block){
            item.frame.on('moveStart', function(event){
                if (!controls.hasClass("active") && $(window).innerWidth() >= displayControlsSlyBreakPoint){
                    controls.addClass("active");
                }

                if (!scrollbar.hasClass("active")){
                    scrollbar.addClass("active");
                }

            });

            item.frame.on('moveEnd', function(event){
                if (scrollbar.hasClass("active")){
                    scrollbar.removeClass("active");
                }

            });

            item.block.hover(function(){},
                function(){
                    if (controls.hasClass("active")){
                        controls.removeClass("active");
                    }
                }
            );
        }

    });
});