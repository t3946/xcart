'use strict';

// import 'pace';
import 'modernizr';
import $ from 'jquery';
import 'jquery-form';
//import 'mmodal';

import 'swiper';
import WebFont from 'webfontloader';
import Waves from 'Waves';
import whatInput from 'what-input';
import noUiSlider from 'noUiSlider';
import Loader from "./components/Loader";
import FilterPriceSlider from "./components/FilterPriceSlider";
import 'sly/dist/sly';
import './utils/mmodal/jquery.mindy.modal';

import 'bower_components/jQuery.dotdotdot/src/jquery.dotdotdot.js';


import  "./_binds/cart"
import  "./_binds/breadcrumbs"
import  "./_binds/sticky_menu"
import  "./_binds/sticky_header"
import  "./_binds/response_status_278";
import  "./_binds/endless_pagination";
import  "./_binds/click_mmodal";
// import  "./_binds/search";
import  "./_binds/minicart";
import  "./_binds/shadow";
import  "./_binds/catalog_actionblock_sort";
import  "./_binds/productSlider";
import  "./_binds/sliders";

import  "./_binds/pages/product";
import  "./_binds/pages/categories";
import  "./_binds/pages/cart";

import  "./ext/jq-swipe";
import  "./components/Flash";

import './ext/foundation-init';
import '../../vendors/wNumb.js'

import sendAnalytics from './utils/sendAnalytics'

(function(){
    window['$'] = $;
    window['jQuery'] = $;
    window['FilterPriceSlider'] = FilterPriceSlider;
    window['loader'] = new Loader;
    window['whatInput'] = whatInput;
    window['Waves'] = Waves;
    window['WebFont'] = WebFont;
    window['noUiSlider'] = noUiSlider;
    window['sendAnalytics'] = new sendAnalytics;
    window.d = (...arg) => {
        console.log(...arg);
    };

    window.surfMetaRegister = () => {
        $.post('/api/analytics?_='+(new Date()).getTime(),{
            'url':window.location.href,
            'referrer': document.referrer || ''
        });
    };
})();