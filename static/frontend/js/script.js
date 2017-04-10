"use strict";
var largeBreakPoint = 1024,
    minPrice = 0,
    maxPrice = 20000,
    initialPrice = 14856,
    speedSly = 300,
    moveBySly = 600,
    displayControlsSlyBreakPoint = 600,
    itemNavSly = 'basic',
    animateDuration = 400,
    animateDurationFast = 10,
    closeTimeOut = 500,
    setPopover = {
        placement: function (context, source) {
            if ($(".button-group-actions_desktop_switch-view .button-tile").hasClass("active-view")) {
                return "top";
            } else {
                return "right";
            }
        }
        , trigger: "focus"
    },
    offCanvas = $('.row-offcanvas'),
    priceRangeBlock = document.getElementById("price-range"),
    dpPriceRangeBlock = document.getElementById("dp_price-range"),
    imageLeftTop = $('#image_left-top'),
    recentlyViewedFrame = $("#recently-viewed_frame"),
    controlsRecentlyViewed = recentlyViewedFrame.siblings(".slider-horizontal__controls"),
    scrollBarRecentlyViewed = recentlyViewedFrame.siblings(".slider-horizontal__scrollbar").find(".slider-horizontal__scrollbar_handle"),
    recentlyViewed = $("#recently-viewed-block"),
    alsoBought = $("#customers-also-bought-block"),
    quickView = $("#quick-view-product1-block"),
    quickViewModal = $("#featured_product1_view"),
    slyRecentViewOptions = {
        horizontal: 1,
        itemNav: itemNavSly,
        speed: speedSly,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        scrollBar: recentlyViewed.find('.recently-viewed__scrollbar'),
        scrollBy: 1,
        activatePageOn: 'click',
        moveBy: moveBySly,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,
        // Buttons
        prevPage: recentlyViewed.find('.recently-viewed__controls_prev-page'),
        nextPage: recentlyViewed.find('.recently-viewed__controls_next-page')

    },
    slyCustomersAlsoBoughtOptions = {
        horizontal: 1,
        itemNav: itemNavSly,
        speed: speedSly,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        scrollBar: alsoBought.find('.customers-also-bought__scrollbar'),
        scrollBy: 1,
        activatePageOn: 'click',
        moveBy: moveBySly,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,
        // Buttons
        prevPage: alsoBought.find('.customers-also-bought__controls_prev-page'),
        nextPage: alsoBought.find('.customers-also-bought__controls_next-page')

    },
    slyQuickViewOptions = {
        horizontal: false,
        itemNav: itemNavSly,
        speed: speedSly,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        scrollBar: quickView.find('.quick-view-product1__scrollbar'),
        scrollBy: 1,
        activatePageOn: 'click',
        moveBy: moveBySly,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,
        // Buttons
        prevPage: quickView.find('.quick-view-product1__controls_prev-page'),
        nextPage: quickView.find('.quick-view-product1__controls_next-page')

    },
    alsoBoughtFrame = null,
    slyQuickViewProduct1Frame = null,
    recentlyViewedSly = null;


function changePlaceholder(){
    var placeholder = $(window).innerWidth() < largeBreakPoint ? "Search" :
        "Search art supply items, brands and categories";
    $("#search_main_input").attr("placeholder", placeholder);
}

function reloadSly(frame, block, options){
    var resFrame;

    if (frame === null){
        resFrame = new Sly(block, options);
        resFrame.init();
    } else {
        resFrame = frame;
        resFrame.reload();
    }

    return resFrame;
}

function addCurrencyClass (curr_val){
    var currAttr = "usa";
    if (typeof curr_val !== 'undefined' && currAttr !== curr_val){
        currAttr = curr_val;
    }
    $(".switch-settings__currency ~ .selectBox-dropdown .selectBox-label").attr("data-currency", currAttr);
}

function setImageLeftTopHeight() {
    var newHeight =  $("#right-desktop-column__top-block").height() + imageLeftTop.innerHeight() -
        imageLeftTop.outerHeight(true);

    imageLeftTop.innerHeight(newHeight);
}

function reloadRecentlyViewed(){
    if (recentlyViewedFrame.length){
        if (recentlyViewedSly === null){
            recentlyViewedSly = new Sly(recentlyViewedFrame, slyRecentViewOptions).init();
        } else {
            recentlyViewedSly.reload();
        }
    }
}

function closeModalQuickViewOnSmall(){
    if ($(window).innerWidth() < largeBreakPoint && quickViewModal.hasClass("in")){
        quickViewModal.modal("hide");
    }
}

function hideAddToCart(){
    if ($(window).innerWidth() >= largeBreakPoint) {
        $(".slider-horizontal__item_add-to-cart:visible").hide();
    }
}

function setSliderHandle(i, value) {
    var r = [null,null];
    r[i] = value;
    priceRangeBlock.noUiSlider.set(r);
}

$(window).resize(function(){
    changePlaceholder();
    setImageLeftTopHeight();
    reloadRecentlyViewed();
    closeModalQuickViewOnSmall();
    hideAddToCart();
});

$(window).scroll(function () {
    var winHeight = $(window).height(),
        wrapperHeight = $('#main_wrapper').outerHeight(),
        scrollTop = $(this).scrollTop(),
        offset = 100,
        up = $(".nav-page-fixed__btn_up"),
        down = $(".nav-page-fixed__btn_down");

    if (scrollTop > wrapperHeight - winHeight - offset){
        up.addClass("active");
        down.removeClass("active");
    } else if (scrollTop > offset){
        up.addClass("active");
        down.addClass("active");
    } else {
        up.removeClass("active");
        down.addClass("active");
    }
});

$(document).ready(function(){
    changePlaceholder();
    addCurrencyClass();
    setImageLeftTopHeight();
    reloadRecentlyViewed();

    $(".item__sku_what-is").popover(setPopover);
    $("[data-toggle=popover]").popover({
        html: true
    });

    if (priceRangeBlock) {

        var input0 = document.getElementById('min-price'),
            input1 = document.getElementById('max-price'),
            inputs = [input0, input1];

        noUiSlider.create(priceRangeBlock, {
            start: [initialPrice, maxPrice],
            connect: true,
            range: {
                'min': minPrice,
                'max': maxPrice
            }
        });

        priceRangeBlock.noUiSlider.on('update', function( values, handle ) {
                inputs[handle].value = Math.round(values[handle]);
        });

        input0.value = initialPrice;
        input1.value = maxPrice;



        // Listen to keydown events on the input field.
        inputs.forEach(function(input, handle) {

            input.addEventListener('change', function(){
                setSliderHandle(handle, this.value);
            });

            input.addEventListener('keydown', function( e ) {

                var values = priceRangeBlock.noUiSlider.get();
                var value = Number(values[handle]);

                // [[handle0_down, handle0_up], [handle1_down, handle1_up]]
                var steps = priceRangeBlock.noUiSlider.steps();

                // [down, up]
                var step = steps[handle];

                var position;

                // 13 is enter,
                // 38 is key up,
                // 40 is key down.
                switch ( e.which ) {

                    case 13:
                        setSliderHandle(handle, this.value);
                        break;

                    case 38:

                        // Get step to go increase slider value (up)
                        position = step[1];

                        // false = no step is set
                        if ( position === false ) {
                            position = 1;
                        }

                        // null = edge of slider
                        if ( position !== null ) {
                            setSliderHandle(handle, value + position);
                        }

                        break;

                    case 40:

                        position = step[0];

                        if ( position === false ) {
                            position = 1;
                        }

                        if ( position !== null ) {
                            setSliderHandle(handle, value - position);
                        }

                        break;
                }
            });
        });
    }

    if (dpPriceRangeBlock) {
        var dPinput0 = document.getElementById('dp_min-price'),
            dPinput1 = document.getElementById('dp_max-price'),
            dPinputs = [dPinput0, dPinput1];

        noUiSlider.create(dpPriceRangeBlock, {
            start: [initialPrice, maxPrice],
            connect: true,
            range: {
                'min': minPrice,
                'max': maxPrice
            }
        });

        dpPriceRangeBlock.noUiSlider.on('update', function( values, handle ) {
                dPinputs[handle].value = Math.round(values[handle]);
        });

        dPinput0.value = initialPrice;
        dPinput1.value = maxPrice;
    }
    //
    // recentlyViewedSly.on('moveStart', function(){
    //     if (!controlsRecentlyViewed.hasClass("active") && $(window).innerWidth() >= displayControlsSlyBreakPoint){
    //         controlsRecentlyViewed.addClass("active");
    //     }
    //
    //     if (!scrollBarRecentlyViewed.hasClass("active")){
    //         scrollBarRecentlyViewed.addClass("active");
    //     }
    //
    // });
    //
    // recentlyViewedSly.on("moveEnd", function () {
    //     if (scrollBarRecentlyViewed.hasClass("active")){
    //         scrollBarRecentlyViewed.removeClass("active");
    //     }
    // });
    //
    // recentlyViewedFrame.hover(function(){},
    //     function(){
    //         if (controlsRecentlyViewed.hasClass("active")){
    //             controlsRecentlyViewed.removeClass("active");
    //         }
    //     }
    // );

    $('#featured_product1_view').on('shown.bs.modal', function (e) {
        alsoBoughtFrame = reloadSly(alsoBoughtFrame, '#customers-also-bought_frame', slyCustomersAlsoBoughtOptions);
        slyQuickViewProduct1Frame = reloadSly(slyQuickViewProduct1Frame, '#quick-view-product1_frame', slyQuickViewOptions);
    });

    quickView.find(".slider-vertical__item").click(function(e){
        e.preventDefault();

        var src = $(this).find("img").attr("src"),
            bgPath = "";

        if (src.length > 0){
            bgPath = "url("+src+")";
        }

        if(!$(this).hasClass("active")){
            quickView.find(".slider-vertical__item.active").removeClass("active");
            $(this).addClass("active");

            if (bgPath.length > 0 ){
                quickView.parents(".quick-view-container").find(".full-view").css("background-image", bgPath);
            }
        }
    });

    $(".switch-settings__currency").on("change", function(){
        var curr_val = $(this).find("option:selected").val();
        addCurrencyClass(curr_val);
    });

    $(".filter-by__panel").find(".modal")
        .on('shown.bs.modal', function () {
        $('.shadow').addClass("active");
    })
        .on('hidden.bs.modal', function () {
        $('.shadow').removeClass("active");
    });

    $(".search-for").focus(function(){
        $(".hidden-search").addClass("active").animate({opacity: 1}, animateDuration);
        $('.shadow').addClass("active");
    }).blur(function(){
        $(".hidden-search").removeClass("active").animate({opacity: 0}, animateDuration);
        $(this).find(".remove-text").hide();
        $('.shadow').removeClass("active");
    }).keyup(function() {
        if ($(this).val().length > 0){
            $(this).siblings(".remove-text").show();
        } else {
            $(this).siblings(".remove-text").hide();
        }
    });

    $(".remove-text").click(function(){
        $(this).siblings("input[type='text']").val("").focus();
        $(this).hide();

        if ($(this).hasClass("product-questions__remove-text")){
            $(this).siblings(".valid").find("span").hide();
        }
    });

    $("#product_mob_questions").find("input[type='text']").keyup(function() {
        $(this).siblings(".valid").find("span").hide();

        if ($(this).val().length > 0){
            $(this).siblings(".remove-text").show();
        } else {
            $(this).siblings(".remove-text").hide();
        }
    });

    $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active');
    });

    $(".accordion-mob").find(".panel-title a").click(function(){
        $(this).parents(".panel-heading").toggleClass("active-panel-heading");
    });

    $(".top-block-inner .departments__toggle").hover(function() {
        if (!$(".hidden-search").hasClass("active") && !$(".departments-menu-desktop").hasClass("active")) {
            $(".departments-menu-desktop").addClass("active").animate({opacity: 1}, animateDurationFast);
            $(this).toggleClass("active-white");
            $('.shadow').addClass("active");
        }
    });

    $(".departments-menu-desktop").hover(function(){
        $(".departments__toggle").addClass("active-white");
    }, function(){
    });

    $(".departments-menu-desktop__block").hover(function(){
        $(this).parents(".departments-menu-desktop").addClass("extend-menu");
    }, function(){
        $(this).parents(".departments-menu-desktop").removeClass("extend-menu");
    });

    $(".departments__toggle-container").mouseleave(function(){
        if (!$(".hidden-search").hasClass("active")) {
            var timeoutId = setTimeout(function(){
                $(".departments-menu-desktop").removeClass("active").removeClass("extend-menu").animate({opacity: 0}, animateDuration);
                $(".departments__toggle").removeClass("active-white");
                $('.shadow').removeClass("active");

            }, closeTimeOut);

            $(this).mouseover(function(){
                clearTimeout(timeoutId);
            });

        }
    });

    $(".drop-settings").hover(function(){
        $(this).addClass("open");
    }, function(){
        $(this).removeClass("open");
    });

    recentlyViewedFrame.find(".slider-horizontal__item_image-link").click(function(e){
        e.preventDefault();

        if ($(window).innerWidth() < largeBreakPoint) {
            $(this).siblings(".slider-horizontal__item_add-to-cart").css("display", "block");
        }
    });

    recentlyViewedFrame.find(".slider-horizontal__item_add-to-cart").click(function(e){
        e.preventDefault();
    });

    recentlyViewedFrame.find(".recently-viewed__item").mouseleave(function(){
        if ($(window).innerWidth() < largeBreakPoint){
            $(this).find(".slider-horizontal__item_add-to-cart").hide();
        }
    });


    $('.cart-lg__wrapper').mouseover(function(){
        if (!$(".hidden-search").hasClass("active")){
            $('.shadow,.cart_hide').addClass("active");
            $(this).addClass("active");
        }
    }).hover(function(){
        if (!$(".hidden-search").hasClass("active")) {
            $('.cart__scroll-block').each(function () {
                var api = $(this).data('jsp');
                if (api) {
                    api.reinitialise()
                }
                else {
                    $(this).jScrollPane({showArrows: true, mouseWheelSpeed : 50});
                }
            })
        }
    });

    $(".cart-lg-container").mouseleave(function(){
        if (!$(".hidden-search").hasClass("active")) {
            var timeoutId = setTimeout(function(){
                $('.cart-lg__wrapper,.shadow,.cart_hide').removeClass("active");

            }, closeTimeOut);

            $(this).mouseover(function(){
                clearTimeout(timeoutId);
            });
        }
    });

    $(".filter-by__panel .panel-body .active").click(function () {
        $(this).removeClass("active");
    });


    $(".button-switch-view").click(function () {

        $(".items__list").toggleClass("tile-view");
        $(".button-switch-view").toggleClass("button-tile");

    });

    $(".button-group-actions_desktop_switch-view .button-switch-view-desktop").click(function(e){
        e.preventDefault();
        if (!$(this).hasClass("active-view")){
            if ($(this).hasClass("button-tile")){
                $(".button-group-actions_desktop_switch-view .button-list").removeClass("active-view");
                $(".button-group-actions_desktop_switch-view .button-tile").addClass("active-view");
                $(".item__brand").hide();

            } else {
                $(".button-group-actions_desktop_switch-view .button-list").addClass("active-view");
                $(".button-group-actions_desktop_switch-view .button-tile").removeClass("active-view");
                $(".item__brand").show();
            }

            $(".items__list").toggleClass("tile-view");
        }
    });

    $(".list-info__read-more").click(function (e) {
        e.preventDefault();
        $(this).parents(".list-info").toggleClass("full");
    });

    $("a.read-more").click(function (e) {
        e.preventDefault();
        $(this).parents(".text-more-wrapper").toggleClass("full");
    });

    $("#categories-show-more").click(function (e) {
        e.preventDefault();
        $(this).parents(".list-categories").toggleClass("full");
    });

    $(".applied-filters__list li a").click(function(e){
        e.stopPropagation();
        $(this).parent("li").hide();
    });

   $(".sort-by__list li a").click(function(e){
        e.preventDefault();
        var li = $(this).parent("li"),
            dropdown = $(this).parents(".sort-by__list");

        if (li.hasClass("active")){
            li.removeClass("active");
        } else {
            $(this).parents("ul").find(".active").removeClass("active");
            li.addClass("active");
        }

       if (dropdown.siblings('.button-sort-by').attr("aria-expanded") == "true"){
           dropdown.dropdown('toggle');
       }

       setTimeout(function(){
           dropdown.dropdown('toggle');
       }, closeTimeOut);

    });


    $(".quantity_modify").on("click", function(e){
        e.preventDefault();
        if ($(this).hasClass("active")){
            var quantity = parseInt($(this).siblings(".quantity_input").val());

            if ($(this).hasClass("quantity_inc")){
                if (quantity == 1) {
                    $(this).siblings(".quantity_dec").addClass("active");
                }
                quantity++;
            } else if ($(this).hasClass("quantity_dec") && quantity > 1) {
                quantity--;
                if (quantity <= 1) {
                    $(this).removeClass("active");
                }
            }

            $(this).siblings(".quantity_input").val(quantity);
        }

    });

    $(".item__info-buy_quantity .quantity_input").on("change", function(e){
        var q = parseInt($(this).val()),
            dec = $(this).siblings(".quantity_dec");

        if (q <= 1 && dec.hasClass("active")) {
            dec.removeClass("active");
        } else if (q > 1 && !dec.hasClass("active")) {
            dec.addClass("active");
        }

    });

    $('.sort-by__list').parent().on('hide.bs.dropdown', function (e) {

    });

    $('.main-canvas, .offcanvas-toggle').on("click", function(event) {
        if ($(this).hasClass('offcanvas-toggle')){
            event.stopPropagation();
            return;
        }

        if (offCanvas.hasClass('active')) {
            offCanvas.removeClass('active');
        }
    });

});
