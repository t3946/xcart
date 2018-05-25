'use strict';

import '../../temp/frontend/js/vendors';
import '_head';

import foundationRegisterCustomEvents from "./_binds/foundation_events";

import DepartmentMenu from "./components/DepartmentMenu";
import DottedText from "./components/DottedText";
import CategoryViewType from "./components/CategoryViewType";
import LazyImageLoad from "./components/LazyImageLoad";
import CatalogFilter from "./components/CatalogFilter";
import SearchSuggestion from "./components/SearchSuggestion";
import isTouch from "./utils/isTouch";
import isMedia from "./utils/isMedia";
import documentReady from "./utils/documentReady";

// require('preact/devtools');
// require('preact/debug');

(function(){
    documentReady(()=>{
        new SearchSuggestion();
        new LazyImageLoad();
        new CategoryViewType();
        new DepartmentMenu();
        // new DottedText('.must-show-less');
        new CatalogFilter();

        isMedia('medium', '(max-width: 1023px)');
        isMedia('large', '(min-width: 1024px)');

        Waves.attach('.waves');
        Waves.init();

        let $offCanvasLeft  = $('#offCanvasLeft');
        let $offCanvasRight = $('#offCanvasRight');

        $(document).on('swipe', function(e, Dx, Dy, angle) {
            if (e.target.closest('#main_wrapper') && !e.target.closest('.disable-global-swipe, .slider-data, .disable-global-swipe-horizontal')) {
                if (isMedia('medium') && isTouch()) {
                    if (angle < 10) {
                        if (Dx === 1 && Dy === 0) { //right
                            if ($offCanvasRight.hasClass('is-open')) {
                                $offCanvasRight.foundation('close');
                            }
                            else {
                                $offCanvasLeft.foundation('open', e);
                            }
                        }
                        else if (Dx === -1 && Dy === 0) {
                            if ($offCanvasLeft.hasClass('is-open')) {
                                $offCanvasLeft.foundation('close');
                            }
                            else {
                                $offCanvasRight.foundation('open', e);
                            }
                        }
                    }
                }
            }
        });

        $(document)
            .on('click', '.show_more', function(e){
                let $this = $(this);
                let $target = $($this.data('target'));

                if (!$target.hasClass('full')) {
                    $target.addClass('full');

                    $this.html($this.data('text-less'));
                }
                else {
                    $target.removeClass('full');

                    $this.html($this.data('text-more'));
                }
            });

        $(document).on('click', 'form button', function(event){
            $(event.target).parents('form').addClass('tried_to_submit');
        });


        loader.detach(()=>{
            $('.off-canvas').removeClass('hide');

            $(document).foundation();

            while(window.app.afterReady.length) {
                (window.app.afterReady.pop())();
            }

            foundationRegisterCustomEvents();
        });

        setTimeout(()=>{
            WebFont.load({
                google: {
                    families: ['Lato:300i,400,700']
                }
            });

            $(document).trigger('component.cart.check');
            $(document).trigger('app.start');

            window.surfMetaRegister();

            setTimeout(()=>{
                WebFont.load({
                    google: {
                        families: ['Lato:300,300i,400,400i,700,700i,800,900']
                    }
                });
            }, 2000);

        }, 100);

    })
})();
