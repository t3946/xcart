// "use strict";
//
// var productView = $("#product__view-block"),
//     customersWhoBought = $("#customers-who-bought-also-bought-block"),
//     productZoom = $("#product__zoom-block"),
//     itemNavSly = 'basic',
//     speedSly = 300,
//     moveBySly = 600,
//     cycleIntervalSly = 6000,
//     displayControlsSlyBreakPoint = 600,
//     slyProductViewOptions = {
//         horizontal: false,
//         itemNav: itemNavSly,
//         speed: speedSly,
//         mouseDragging: 1,
//         touchDragging: 1,
//         releaseSwing: 1,
//         scrollBar: productView.find('.product__view__scrollbar'),
//         scrollBy: 1,
//         activatePageOn: 'click',
//         moveBy: moveBySly,
//         elasticBounds: 1,
//         dragHandle: 1,
//         dynamicHandle: 1,
//         clickBar: 1,
//         // Buttons
//         prevPage: productView.find('.product__view__controls_prev-page'),
//         nextPage: productView.find('.product__view__controls_next-page')
//     },
//     slyProductZoomOptions = {
//         horizontal: 1,
//         itemNav: itemNavSly,
//         speed: speedSly,
//         mouseDragging: 1,
//         touchDragging: 1,
//         releaseSwing: 1,
//         activatePageOn: 'click',
//         moveBy: moveBySly,
//         elasticBounds: 1,
//         dragHandle: 1,
//         dynamicHandle: 1,
//         clickBar: 1,
//         pagesBar: productZoom.find('.product__zoom_pages'),
//         cycleBy: 'pages',
//         cycleInterval: cycleIntervalSly,
//         pauseOnHover:  false,
//         startPaused:   false
//     },
//
//     slyCustomersWhoBoughtOptions = {
//         horizontal: 1,
//         itemNav: itemNavSly,
//         speed: speedSly,
//         mouseDragging: 1,
//         touchDragging: 1,
//         releaseSwing: 1,
//         scrollBar: customersWhoBought.find('.customers-who-bought-also-bought__scrollbar'),
//         scrollBy: 1,
//         activatePageOn: 'click',
//         moveBy: moveBySly,
//         elasticBounds: 1,
//         dragHandle: 1,
//         dynamicHandle: 1,
//         clickBar: 1,
//         // Buttons
//         prevPage: customersWhoBought.find('.customers-who-bought-also-bought__controls_prev-page'),
//         nextPage: customersWhoBought.find('.customers-who-bought-also-bought__controls_next-page')
//     },
//     productViewFrame = null,
//     productZoomFrame = null,
//     customersWhoBoughtFrame = null;
//
// function reloadProductFrames(){
//     if ($('#customers-who-bought-also-bought_frame').length) {
//         customersWhoBoughtFrame = reloadSly(customersWhoBoughtFrame, '#customers-who-bought-also-bought_frame', slyCustomersWhoBoughtOptions);
//     }
//
//     if ($('#product__view_frame').length){
//         productViewFrame = reloadSly(productViewFrame, '#product__view_frame', slyProductViewOptions);
//     }
// }
//
// $(window).resize(function(){
//     //reloadProductFrames();
// });
//
// $(document).ready(function() {
//     reloadProductFrames();
//
//     $(".product__full-view-container").find(".full-view").click(function(){
//         $('#product_zoom').modal('show');
//     });
//
//     $('#product_zoom').on('shown.bs.modal', function (e) {
//         productZoomFrame = reloadSly(productZoomFrame, '#product__zoom_frame', slyProductZoomOptions);
//     });
//
//     var framesObj = [
//         {
//             block: $('#customers-who-bought-also-bought__frame'),
//             frame: customersWhoBoughtFrame
//         }
//     ];
//
//     framesObj.forEach(function(item, i, arr){
//
//         var controls = item.block.siblings(".slider-horizontal__controls"),
//             scrollbar = item.block.siblings(".slider-horizontal__scrollbar").find(".slider-horizontal__scrollbar_handle");
//
//         if (item.frame && item.block){
//             item.frame.on('moveStart', function(event){
//                 if (!controls.hasClass("active") && $(window).innerWidth() >= displayControlsSlyBreakPoint){
//                     controls.addClass("active");
//                 }
//
//                 if (!scrollbar.hasClass("active")){
//                     scrollbar.addClass("active");
//                 }
//
//             });
//
//             item.frame.on('moveEnd', function(event){
//                 if (scrollbar.hasClass("active")){
//                     scrollbar.removeClass("active");
//                 }
//
//             });
//
//             item.block.hover(function(){},
//                 function(){
//                     if (controls.hasClass("active")){
//                         controls.removeClass("active");
//                     }
//                 }
//             );
//         }
//
//
//     });
//
//     productView.find(".slider-vertical__item").click(function (e) {
//         e.preventDefault();
//
//         var src = $(this).find("img").attr("src"),
//             bgPath = "";
//
//         if (src.length > 0) {
//             bgPath = "url(" + src + ")";
//         }
//
//         if (!$(this).hasClass("active")) {
//             productView.find(".slider-vertical__item.active").removeClass("active");
//             $(this).addClass("active");
//
//             if (bgPath.length > 0) {
//                 productView.parents(".product__view-container").find(".full-view").css("background-image", bgPath);
//             }
//         }
//     });
// });