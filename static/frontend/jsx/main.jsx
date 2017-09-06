import 'head';
import foundationRegisterCustomEvents from "./_binds/foundation_events";

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
import documentReady from "./utils/documentReady";

// require('preact/devtools');

(function(){

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

    $(document).on('swipe', function(e, Dx, Dy, angle) {
        if (isMedia('medium') && isTouch()) {
            if (angle < 10) {
                if (Dx === 1 && Dy === 0) { //right
                    $('#offCanvasLeft').foundation('open', e);
                }
                else if (Dx === -1 && Dy === 0) {
                    $('#offCanvasLeft').foundation('close');
                }
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

    documentReady(()=>{
        $(document).foundation();

        setTimeout(()=>{
            WebFont.load({
                google: {
                    families: ['Lato:300,300i,400,400i,700,700i,900']
                }
            });

            foundationRegisterCustomEvents();
            loader.detach();
        }, 100);
    })
})();
