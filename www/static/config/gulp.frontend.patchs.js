const fs = require('fs');
const imagemin = require('gulp-imagemin');




var modulesDir = 'node_modules';

var modules = fs.readdirSync(modulesDir).map(function (module) {
    return modulesDir + '/' + module;
});

module.exports = {
    dst: {
        // js: 'frontend/dist/js',
        js: 'temp/frontend/js',
        jsx: 'frontend/dist/js',
        scss: 'temp/frontend/css',
        css: 'frontend/dist/css',
        images: 'frontend/dist/images',
        fonts: 'frontend/dist/css/fonts',
        raw: 'frontend/dist/raw'
    },
    modules: {
        jsx: 'frontend/jsx/**/*',
    },
    src: {
        jsx_bundles: {
            main: './frontend/jsx/main.jsx'
        },
        jsx: [ // for watching
            'frontend/jsx/**/*'
        ],
        js_include: [
            'frontend/js_include/**/*',
        ],
        js: [
            'frontend/js/**/*',
        ],
        scss: [
            'frontend/sass/**/*.scss'
        ],
        scss_include: [
            'node_modules/compass-mixins/lib/',
            'node_modules/foundation-sites/scss',
            'components/cds',
        ],
        css: [
            'temp/frontend/css/**/*'
        ],
        css_raw: [
            'frontend/css/*',
        ],
        images: [
            'frontend/images/**/*.*'
        ],
        fonts: [
            'frontend/fonts/**/*'
        ],
        raw: ['frontend/row/*']
    },
    vendors: {

        jquery: {
            js: [
                // 'node_modules/jquery/dist/jquery.min.js'
            ]
        },
        // jquery_cookie: {
        //     js_include: [
        //         'node_modules/jquery.cookie/jquery.cookie.js'
        //     ]
        // },
        // bootstrap: {
        //     js: [
        //         'node_modules/bootstrap-sass/assets/javascripts/bootstrap.js'
        //     ],
        //     fonts: [
        //         'node_modules/bootstrap-sass/assets/fonts/bootstrap/*'
        //     ],
        //     scss_include: [
        //         'node_modules/bootstrap-sass/assets/stylesheets/'
        //     ]
        // },
        jquery_jscrollpane: {
            js_include: [
                'node_modules/jScrollPane/script/jquery.jscrollpane.min.js'
            ]
        },
        jquery_mousewheel: {
            js_include: [
                'node_modules/jquery-mousewheel/jquery.mousewheel.min.js'
            ]
        },
        nouislider: {
            js_include: [
                'node_modules/nouislider/distribute/nouislider.js',
                // 'node_modules/nouislider/distribute/nouislider.js',
            ],
            // css: [
            //     'node_modules/nouislider/distribute/nouislider.css'
            // ]
        },
        sly: {
            js_include: [
                'node_modules/sly/dist/sly.min.js'
            ]
        },
        // swiper: {
        //     // css: [
        //     //     'node_modules/swiper/dist/css/swiper.min.css'
        //     // ],
        //     scss_include: [
        //         'node_modules/swiper/src/less/'
        //     ],
        // },
        // lato: {
        //     fonts: [
        //         'node_modules/lato-webfont/fonts/*'
        //     ],
        //     scss_include: [
        //         'node_modules/lato-webfont/scss/'
        //     ]
        // },
        dotdotdot: {
            js_include: [
                'node_modules/jQuery.dotdotdot/src/jquery.dotdotdot.js'
            ]
        },
        waves: {
            js_include: [
                'node_modules/node-waves/src/js/waves.js'
            ],
            scss: [
                // 'node_modules/Waves/src/scss/waves.scss'
            ]
        },
        // jqlazy: {
        //     js_include: [
        //         'node_modules/jquery_lazyload/jquery.lazyload.js'
        //     ]
        // },

        "what-input": {
            js_include: [
                'node_modules/what-input/dist/what-input.js'
            ]
        },
        cds: {
            scss_include: [
                'components/cds'
            ]
        },

        jquery_form: {
            js_include: [
                'node_modules/jquery-form/dist/jquery.form.min.js'
            ]
        },
        webfontloader: {
            js_include: [
                'node_modules/webfontloader/webfontloader.js'
            ]
        },
        // modal: {
        //     js_include: [
        //         'node_modules/mmodal/js/jquery.mindy.modal.js'
        //     ],
        //     scss_include: [
        //         'node_modules/mmodal/scss/'
        //     ]
        // },
        bourbon: {
            scss_include: [
                'node_modules/bourbon/app/assets/stylesheets/'
            ]
        },
        wNumb: {
            js_include: [
                'vendors/wNumb.js'
            ]
        },
        Sly: {
            js: [
                // 'node_modules/sly/dist/sly.js'
            ]
        },
        pace: {
            js_include: [
                'node_modules/PACE/pace.js'
            ],
            css: [
                // 'node_modules/PACE/themes/black/pace-theme-minimal.css'
                // 'node_modules/PACE/themes/red/pace-theme-minimal.css'
            ]
        },
        simplebar: {
            css_raw: [
                'node_modules/simplebar/dist/simplebar.css'
            ]
        },
        foundation: {
            js_include: [
                // 'node_modules/foundation-sites/dist/js/foundation.js', //all
                'node_modules/foundation-sites/dist/js/plugins/foundation.core.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.offcanvas.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.accordion.js',
                // 'node_modules/foundation-sites/dist/js/plugins/foundation.sticky.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.toggler.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.smoothScroll.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.abide.js',
                // 'node_modules/foundation-sites/dist/js/plugins/foundation.dropdown.js',
                // 'node_modules/foundation-sites/dist/js/plugins/foundation.dropdownMenu.js',
                // 'node_modules/foundation-sites/dist/js/plugins/foundation.tooltip.js',

                'node_modules/foundation-sites/dist/js/plugins/foundation.util.keyboard.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.util.box.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.util.nest.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.util.motion.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.util.triggers.js',
                'node_modules/foundation-sites/dist/js/plugins/foundation.util.mediaQuery.js',
            ],
            scss_include: [
                'node_modules/foundation-sites/scss/'
            ]
        },
    }
};