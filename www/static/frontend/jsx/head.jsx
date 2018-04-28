import 'modernizr';
import $ from 'jquery';
// import 'jScrollPane';
import 'jquery-form';
import 'mmodal';

// import Fotorama from 'fotorama';
import 'swiper';
import WebFont from 'webfontloader';
import Waves from 'Waves';
import whatInput from 'what-input';
import noUiSlider from 'noUiSlider';
import 'sly/dist/sly';

import 'pace';
// import 'bower_components/PACE/pace.js';
import 'bower_components/jQuery.dotdotdot/src/jquery.dotdotdot.js';
// import PhotoSwipe from 'bower_components/photoswipe/dist/photoswipe.js';
// import PhotoSwipeUI_Default from 'bower_components/photoswipe/dist/photoswipe-ui-default';

// import 'sly';

import  "./_binds/cart"
import  "./_binds/response_status_278";
import  "./_binds/endless_pagination";
import  "./_binds/click_mmodal";
import  "./_binds/search";
import  "./_binds/minicart";
import  "./_binds/shadow";
import  "./_binds/catalog_actionblock_sort";
import  "./_binds/productSlider";
import  "./_binds/sliders";

import  "./_binds/pages/product";
import  "./_binds/pages/categories";

import  "./ext/jq-swipe";
import  "./components/Flash";

import './ext/foundation-init';
import '../../vendors/wNumb.js'

(function(){
    window['$'] = $;
    window['jQuery'] = $;
    window['whatInput'] = whatInput;
    window['Waves'] = Waves;
    window['WebFont'] = WebFont;
    window['noUiSlider'] = noUiSlider;
    window['d'] = arg =>{
        console.log(...arguments);
    };
    // window['PhotoSwipe'] = PhotoSwipe;
    // window['PhotoSwipeUI_Default'] = PhotoSwipeUI_Default;
    // window['Fotorama'] = Fotorama;
})();