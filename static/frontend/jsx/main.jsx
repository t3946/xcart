import 'modernizr';
import  "./_binds/response_status_278";
import  "./_binds/product_quantity_group";
import  "./_binds/endless_pagination";
import  "./_binds/click_mmodal";
import  "./_binds/search";

import  "./ext/jq-swipe";

import { h, render } from 'preact';
import MiniCart from "./components/MiniCart";
import DepartmentMenu from "./components/DepartmentMenu";
import DottedText from "./components/DottedText";
import CategoryViewType from "./components/CategoryViewType";
import LazyImageLoad from "./components/LazyImageLoad";
import CatalogFilter from "./components/CatalogFilter";
import FilterPriceSlider from "./components/FilterPriceSlider";
import SearchSuggestion from "./components/SearchSuggestion";
import Loader from "./components/Loader";
import isTouch from "./utils/isTouch";
import isMedia from "./utils/isMedia";

require('preact/devtools');

(function(){
    let minicart = document.querySelector('body #search_container .minicart');

    new SearchSuggestion();
    new LazyImageLoad();
    new CategoryViewType();
    new DepartmentMenu();
    new DottedText('.must-show-less');
    new CatalogFilter();

    isMedia('medium', '(max-width: 1023px)');

    window['FilterPriceSlider'] = FilterPriceSlider;
    window['loader'] = new Loader;

    Waves.attach('.waves');
    Waves.init();


    $(document).on('show:dm', ()=> {
        $('.shadow').addClass('active');
    });

    $(document).on('hide:dm', ()=> {
        $('.shadow').removeClass('active');
    });

    $('.shadow').on('click touchstart', ()=> {
        $(document).trigger('click:shadow');
    });

    if (minicart) {
        render(<MiniCart />, minicart);
    }

    $(document).on('swipe', function(e, Dx, Dy) {
        if (isMedia('medium') && isTouch()) {

            if (Dx === 1) { //right
                $('#offCanvasLeft').foundation('open');
            }
            else if (Dx === -1) {
                $('#offCanvasLeft').foundation('close');
            }
        }
    });


    $(document).on('click', '.show_more', function(e){
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


    // $(window).on('beforeunload unload pagehide', (e)=>{
    //     console.log(e);
    //
    // });

    // $(window).on('pageshow', (e)=>{
    //     console.log(e);
    // });
    // $(window).on('pagehide', (e)=>{
    //     loader.load();
    // });

    // $(window).on('popstate', ()=>{
    //     loader.load();
    // });
    //
    // let handlePushState = function() {
    //     loader.load();
    // };
    //
    //
    // if (window.history.pushState != null) {
    //     var _pushState = window.history.pushState;
    //     window.history.pushState = function() {
    //         handlePushState();
    //         return _pushState.apply(window.history, arguments);
    //     };
    // }
    //
    //
    // if (window.history.replaceState != null) {
    //     var _replaceState = window.history.replaceState;
    //     window.history.replaceState = function() {
    //         handlePushState();
    //         return _replaceState.apply(window.history, arguments);
    //     };
    // }

    if (document.readyState !== 'complete') {
        $(document).ready(()=>{
            $(document).foundation();
            loader.detach();
        })
    }
    else {
        $(document).foundation();
        loader.detach();
    }
})();
