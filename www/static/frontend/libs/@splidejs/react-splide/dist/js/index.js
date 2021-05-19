"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
Object.defineProperty(exports, "Splide", {
  enumerable: true,
  get: function get() {
    console.log('import splide');
    return _Splide["default"];
  }
});
Object.defineProperty(exports, "SplideSlide", {
  enumerable: true,
  get: function get() {
    return _SplideSlide["default"];
  }
});

console.log('import splide');

var _Splide = _interopRequireDefault(require("./components/Splide"));

var _SplideSlide = _interopRequireDefault(require("./components/SplideSlide"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }