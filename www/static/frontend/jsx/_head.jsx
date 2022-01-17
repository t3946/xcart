"use strict";

// import 'pace';
// import 'modernizr';
import $ from "jquery";
import "jquery-form";
//import 'mmodal';

// import 'swiper';
//import WebFont from 'webfontloader';
import FontFaceObserver from "fontfaceobserver";
import Waves from "node-waves";
import whatInput from "what-input";
import formValidate from "validate.js";
import noUiSlider from "nouislider";
import Loader from "./components/Loader";
import FilterPriceSlider from "./components/FilterPriceSlider";
import "./utils/mmodal/jquery.mindy.modal";

import "jQuery.dotdotdot";

import "./_binds/forms";
import "./_binds/cart";
import "./_binds/up_down_buttons";
import "./_binds/sticky_header";
import "./_binds/response_status_278";
import "./_binds/endless_pagination";
import "./_binds/click_mmodal";
// import  "./_binds/search";
import "./_binds/minicart";
import "./_binds/shadow";
import "./_binds/productSlider";
import "./_binds/pages/category";
import "./_binds/sliders";
import "./_binds/checkout";

import "./_binds/pages/product";
import "./_binds/pages/categories";
import "./_binds/pages/cart";

import "./ext/jq-swipe";
import "./components/Flash";

import "./ext/foundation-init";
import "../../vendors/wNumb.js";

import sendAnalytics from "./utils/sendAnalytics";
import LazyLoad from "vanilla-lazyload";

(function () {
  window["$"] = $;
  window["jQuery"] = $;
  window["FilterPriceSlider"] = FilterPriceSlider;
  window["loader"] = new Loader();
  window["whatInput"] = whatInput;
  window["formValidate"] = formValidate;
  window["Waves"] = Waves;
  window["FontFaceObserver"] = FontFaceObserver;
  window["sendAnalytics"] = new sendAnalytics();
  window["noUiSlider"] = noUiSlider;
  window["LazyLoad"] = new LazyLoad({
    elements_selector: ".lazy-img, .lazy-bg",
    callback_set: function (el) {
      el.classList.remove("lazy-img");
      el.classList.add("lazy-bg-loaded");
    },
  });

  window.d = (...arg) => {};

  window.surfMetaRegister = () => {
    $.post("/api/analytics?_=" + new Date().getTime(), {
      url: window.location.href,
      referrer: document.referrer || "",
    });
  };
})();
