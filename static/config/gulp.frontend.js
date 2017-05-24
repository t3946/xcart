const fs = require('fs');
const imagemin = require('gulp-imagemin');

var modulesDir = 'node_modules';

var modules = fs.readdirSync(modulesDir).map(function (module) {
    return modulesDir + '/' + module;
});

module.exports = {
    dst: {
        js: 'frontend/dist/js',
        jsx: 'frontend/temp/js',
        scss: 'frontend/temp/css',
        css: 'frontend/dist/css',
        images: 'frontend/dist/images',
        fonts: 'frontend/dist/fonts',
        raw: 'frontend/dist/raw'
    },
    config: {
        name: 'main',
        compress: true,
        babel: {
            presets: ['es2015'],
            // plugins: ['babel-plugin-transform-es2015-modules-commonjs']
        },
        inline_image: {
            baseDir: './frontend/css'
        },
        imagemin: [
            imagemin.gifsicle({interlaced: true}),
            imagemin.jpegtran({progressive: true}),
            imagemin.optipng({optimizationLevel: 5}),
            imagemin.svgo({plugins: [{removeViewBox: true, removeComments: true, removeMetadata: true}]})
        ],

        modernizr: {
            "classPrefix": "",
            "options": [
                // "addTest",
                // "atRule",
                // "domPrefixes",
                // "hasEvent",
                // "html5shiv",
                // "html5printshiv",
                // "load",
                // "mq",
                // "prefixed",
                // "prefixes",
                // "prefixedCSS",
                "setClasses",
                // "testAllProps",
                // "testProp",
                // "testStyles"
            ],
            "feature-detects": [
                "a/download",
                // "ambientlight",
                "applicationcache",
                "audio",
                "audio/loop",
                "audio/preload",
                "audio/webaudio",
                "battery",
                "battery/lowbattery",
                "blob",
                "canvas",
                "canvas/blending",
                "canvas/todataurl",
                "canvas/winding",
                "canvastext",
                "contenteditable",
                "contextmenu",
                "cookies",
                "cors",
                "custom-elements",
                "crypto",
                "crypto/getrandomvalues",
                "css/all",
                "css/animations",
                "css/appearance",
                "css/backdropfilter",
                "css/backgroundblendmode",
                "css/backgroundcliptext",
                "css/backgroundposition-shorthand",
                "css/backgroundposition-xy",
                "css/backgroundrepeat",
                "css/backgroundsize",
                "css/backgroundsizecover",
                "css/borderimage",
                "css/borderradius",
                "css/boxshadow",
                "css/boxsizing",
                "css/calc",
                "css/checked",
                "css/chunit",
                "css/columns",
                "css/cssgrid",
                "css/cubicbezierrange",
                "css/displayrunin",
                "css/displaytable",
                "css/ellipsis",
                "css/escape",
                "css/exunit",
                "css/filters",
                "css/flexbox",
                "css/flexboxlegacy",
                "css/flexboxtweener",
                "css/flexwrap",
                "css/fontface",
                "css/generatedcontent",
                "css/gradients",
                "css/hairline",
                "css/hsla",
                "css/hyphens",
                "css/invalid",
                "css/lastchild",
                "css/mask",
                "css/mediaqueries",
                "css/multiplebgs",
                "css/nthchild",
                "css/objectfit",
                "css/opacity",
                "css/overflow-scrolling",
                "css/pointerevents",
                "css/positionsticky",
                "css/pseudoanimations",
                "css/pseudotransitions",
                "css/reflections",
                "css/regions",
                "css/remunit",
                "css/resize",
                "css/rgba",
                "css/scrollbars",
                "css/scrollsnappoints",
                "css/shapes",
                "css/siblinggeneral",
                "css/subpixelfont",
                "css/supports",
                "css/target",
                "css/textalignlast",
                "css/textshadow",
                "css/transforms",
                "css/transformslevel2",
                "css/transforms3d",
                "css/transformstylepreserve3d",
                "css/transitions",
                "css/userselect",
                "css/valid",
                "css/vhunit",
                "css/vmaxunit",
                "css/vminunit",
                "css/vwunit",
                "css/will-change",
                "css/wrapflow",
                // "custom-protocol-handler",
                "customevent",
                // "dart",
                // "dataview-api",
                "dom/classlist",
                "dom/createElement-attrs",
                "dom/dataset",
                "dom/documentfragment",
                "dom/hidden",
                // "dom/microdata",
                "dom/mutationObserver",
                "dom/passiveeventlisteners",
                "elem/bdi",
                "elem/datalist",
                "elem/details",
                "elem/output",
                "elem/picture",
                "elem/progress-meter",
                "elem/ruby",
                "elem/template",
                "elem/time",
                "elem/track",
                "elem/unknown",
                // "emoji",
                // "es5/array",
                // "es5/date",
                // "es5/function",
                // "es5/object",
                // "es5/specification",
                // "es5/strictmode",
                // "es5/string",
                // "es5/syntax",
                // "es5/undefined",
                // "es6/array",
                // "es6/collections",
                // "es6/contains",
                // "es6/generators",
                // "es6/math",
                // "es6/number",
                // "es6/object",
                // "es6/promises",
                // "es6/string",
                "event/deviceorientation-motion",
                "event/oninput",
                "eventlistener",
                // "exif-orientation",
                // "file/api",
                // "file/filesystem",
                "flash",
                // "forms/capture",
                // "forms/fileinput",
                // "forms/fileinputdirectory",
                // "forms/formattribute",
                // "forms/inputnumber-l10n",
                // "forms/placeholder",
                // "forms/requestautocomplete",
                "forms/validation",
                "fullscreen-api",
                "gamepad",
                "geolocation",
                "hashchange",
                "hiddenscroll",
                "history",
                "htmlimports",
                // "ie8compat",
                // "iframe/sandbox",
                // "iframe/seamless",
                // "iframe/srcdoc",
                // "img/apng",
                // "img/crossorigin",
                // "img/jpeg2000",
                // "img/jpegxr",
                // "img/sizes",
                // "img/srcset",
                // "img/webp",
                // "img/webp-alpha",
                // "img/webp-animation",
                // "img/webp-lossless",
                "indexeddb",
                "indexeddbblob",
                "input",
                "input/formaction",
                "input/formenctype",
                "input/formmethod",
                "input/formtarget",
                "inputsearchevent",
                "inputtypes",
                "intl",
                "json",
                "ligatures",
                "lists-reversed",
                // "mathml",
                // "mediaquery/hovermq",
                // "mediaquery/pointermq",
                // "messagechannel",
                // "network/beacon",
                // "network/connection",
                // "network/eventsource",
                // "network/fetch",
                // "network/xhr-responsetype",
                // "network/xhr-responsetype-arraybuffer",
                // "network/xhr-responsetype-blob",
                // "network/xhr-responsetype-document",
                // "network/xhr-responsetype-json",
                // "network/xhr-responsetype-text",
                // "network/xhr2",
                "notification",
                "pagevisibility-api",
                "performance",
                "pointerevents",
                "pointerlock-api",
                "postmessage",
                "proximity",
                "queryselector",
                "quota-management-api",
                "requestanimationframe",
                "script/async",
                "script/defer",
                "serviceworker",
                "speech/speech-recognition",
                "speech/speech-synthesis",
                "storage/localstorage",
                "storage/sessionstorage",
                "storage/websqldatabase",
                "style/scoped",
                "svg",
                "svg/asimg",
                "svg/clippaths",
                "svg/filters",
                "svg/foreignobject",
                "svg/inline",
                "svg/smil",
                "templatestrings",
                "textarea/maxlength",
                "touchevents",
                "typed-arrays",
                "unicode",
                "unicode-range",
                // "url/bloburls",
                // "url/data-uri",
                // "url/parser",
                // "url/urlsearchparams",
                "userdata",
                "vibration",
                "video",
                "video/autoplay",
                "video/crossorigin",
                "video/loop",
                "video/preload",
                // "vml",
                // "web-intents",
                // "webanimations",
                // "webgl",
                // "webgl/extensions",
                // "webrtc/datachannel",
                // "webrtc/getusermedia",
                // "webrtc/peerconnection",
                // "websockets",
                // "websockets/binary",
                "window/framed",
                // "workers/blobworkers",
                // "workers/dataworkers",
                // "workers/sharedworkers",
                // "workers/transferables",
                // "workers/webworkers",
                // "xdomainrequest"
            ]
        }
        ,
    },
    src: {
        jsx: [
            'frontend/jsx/**/*'
            // 'frontend/jsx/main.jsx'
        ],
        js: [
            'frontend/js/**/*',
            'frontend/temp/js/**/*'
        ],
        scss: [
            'frontend/sass/**/*.scss'
        ],
        scss_include: [
            'bower_components/compass-mixins/lib/',
        ],
        css: [
            'frontend/css/*',
            'frontend/temp/css/**/*'
        ],
        images: [
            'frontend/images/**/*.*'
        ],
        fonts: [
            'frontend/fonts/**/*'
        ],
        raw: []
    },
    vendors: {
        jquery: {
            js: [
                'bower_components/jquery/dist/jquery.min.js'
            ]
        },
        // bootstrap: {
        //     js: [
        //         'bower_components/bootstrap-sass/assets/javascripts/bootstrap.js'
        //     ],
        //     fonts: [
        //         'bower_components/bootstrap-sass/assets/fonts/bootstrap/*'
        //     ],
        //     scss_include: [
        //         'bower_components/bootstrap-sass/assets/stylesheets/'
        //     ]
        // },
        jquery_jscrollpane: {
            js: [
                'bower_components/jScrollPane/script/jquery.jscrollpane.min.js'
            ]
        },
        jquery_mousewheel: {
            js: [
                'bower_components/jquery-mousewheel/jquery.mousewheel.min.js'
            ]
        },
        nouislider: {
            js: [
                'bower_components/nouislider/distribute/nouislider.min.js'
            ]
        },
        sly: {
            js: [
                'bower_components/sly/dist/sly.min.js'
            ]
        },
        lato: {
            fonts: [
                'bower_components/lato-webfont/fonts/*'
            ],
            scss: [
                'bower_components/lato-webfont/scss/lato-webfont.scss'
            ]
        },
        dotdotdot: {
            js: [
                'bower_components/jQuery.dotdotdot/src/jquery.dotdotdot.js'
            ]
        },
        waves: {
            js: [
                'bower_components/Waves/src/js/waves.js'
            ],
            scss: [
                // 'bower_components/Waves/src/scss/waves.scss'
            ]
        },
        jqlazy: {
            js: [
                'bower_components/jquery_lazyload/jquery.lazyload.js'
            ]
        },

        "what-input": {
            js: [
                'bower_components/what-input/dist/what-input.js'
            ]
        },
        foundation: {
            js: [
                // 'bower_components/foundation-sites/dist/js/foundation.js', //all
                'bower_components/foundation-sites/dist/js/plugins/foundation.core.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.offcanvas.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.accordion.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.sticky.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.toggler.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.dropdown.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.dropdownMenu.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.tooltip.js',

                'bower_components/foundation-sites/dist/js/plugins/foundation.util.keyboard.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.util.box.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.util.nest.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.util.motion.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.util.triggers.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.util.mediaQuery.js',
            ],
            scss_include: [
                'bower_components/foundation-sites/scss/'
            ]
        },
    }
};