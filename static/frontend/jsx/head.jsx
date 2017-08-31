import 'modernizr';

// import 'jScrollPane';
import 'jquery-form';
import 'mmodal';

import WebFont from 'webfontloader';
import Waves from 'Waves';
import whatInput from 'what-input';
import noUiSlider from 'noUiSlider';

// import 'foundation-sites';
import 'bower_components/foundation-sites/dist/js/plugins/foundation.core.js';
import 'bower_components/foundation-sites/dist/js/plugins/foundation.offcanvas.js';
import 'bower_components/foundation-sites/dist/js/plugins/foundation.accordion.js';
// import 'bower_components/foundation-sites/dist/js/plugins/foundation.sticky.js';
// import 'bower_components/foundation-sites/dist/js/plugins/foundation.toggler.js';

import 'bower_components/foundation-sites/dist/js/plugins/foundation.util.keyboard.js';
import 'bower_components/foundation-sites/dist/js/plugins/foundation.util.box.js';
// // import 'bower_components/foundation-sites/dist/js/plugins/foundation.util.nest.js';
// // import 'bower_components/foundation-sites/dist/js/plugins/foundation.util.motion.js';
import 'bower_components/foundation-sites/dist/js/plugins/foundation.util.triggers.js';
import 'bower_components/foundation-sites/dist/js/plugins/foundation.util.mediaQuery.js';
import '../../vendors/wNumb.js'
// import 'sly';

import  "./_binds/response_status_278";
import  "./_binds/product_quantity_group";
import  "./_binds/endless_pagination";
import  "./_binds/click_mmodal";
import  "./_binds/search";
import  "./_binds/minicart";
import  "./_binds/shadow";
import  "./_binds/catalog_actionblock_sort";

import  "./ext/jq-swipe";


(function(){
    window['whatInput'] = whatInput;
    window['Waves'] = Waves;
    window['WebFont'] = WebFont;
    window['noUiSlider'] = noUiSlider;
})();