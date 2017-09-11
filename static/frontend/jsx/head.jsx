import 'modernizr';
import $ from 'jquery';
// import 'jScrollPane';
import 'jquery-form';
import 'mmodal';

import WebFont from 'webfontloader';
import Waves from 'Waves';
import whatInput from 'what-input';
import noUiSlider from 'noUiSlider';

// import 'bower_components/PACE/pace.js';
import 'bower_components/jQuery.dotdotdot/src/jquery.dotdotdot.js';

// import 'sly';

import  "./_binds/cart"
import  "./_binds/response_status_278";
import  "./_binds/endless_pagination";
import  "./_binds/click_mmodal";
import  "./_binds/search";
import  "./_binds/minicart";
import  "./_binds/shadow";
import  "./_binds/catalog_actionblock_sort";

import  "./ext/jq-swipe";
import  "./components/Flash";

import './ext/foundation-init';
import '../../vendors/wNumb.js'

(function(){
    window['$'] = $;
    window['whatInput'] = whatInput;
    window['Waves'] = Waves;
    window['WebFont'] = WebFont;
    window['noUiSlider'] = noUiSlider;
})();