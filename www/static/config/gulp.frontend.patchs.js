const fs = require('fs');
const imagemin = require('gulp-imagemin');



var modulesDir = 'node_modules';

var modules = fs.readdirSync(modulesDir).map(function (module) {
    return modulesDir + '/' + module;
});

module.exports = {
    dst: {
        js: 'frontend/dist/js',
        jsx: 'temp/frontend/js',
        scss: 'temp/frontend/css',
        css: 'frontend/dist/css',
        images: 'frontend/dist/images',
        fonts: 'frontend/dist/fonts',
        raw: 'frontend/dist/raw'
    },
    modules: {
        jsx: 'frontend/jsx/**/*',
    },
    src: {
        jsx_bundles: {
            app: './frontend/jsx/main.jsx'
        },
        jsx: [ // for watching
            'frontend/jsx/**/*'
        ],
        js_include: [
            'frontend/js_include/**/*',
        ],
        js: [
            'frontend/js/**/*',
            'temp/frontend/js/**/*.js'
        ],
        scss: [
            'frontend/sass/**/*.scss'
        ],
        scss_include: [
            'bower_components/compass-mixins/lib/',
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
        raw: []
    },
    vendors: {
        bower: {
            scss_include: [
                'bower_components/'
            ]
        },

        jquery: {
            js: [
                // 'bower_components/jquery/dist/jquery.min.js'
            ]
        },
        // jquery_cookie: {
        //     js_include: [
        //         'bower_components/jquery.cookie/jquery.cookie.js'
        //     ]
        // },
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
            js_include: [
                'bower_components/jScrollPane/script/jquery.jscrollpane.min.js'
            ]
        },
        jquery_mousewheel: {
            js_include: [
                'bower_components/jquery-mousewheel/jquery.mousewheel.min.js'
            ]
        },
        nouislider: {
            js_include: [
                'bower_components/nouislider/distribute/nouislider.js',
                // 'bower_components/nouislider/distribute/nouislider.js',
            ],
            // css: [
            //     'bower_components/nouislider/distribute/nouislider.css'
            // ]
        },
        sly: {
            js_include: [
                'bower_components/sly/dist/sly.min.js'
            ]
        },
        // swiper: {
        //     // css: [
        //     //     'bower_components/swiper/dist/css/swiper.min.css'
        //     // ],
        //     scss_include: [
        //         'bower_components/swiper/src/less/'
        //     ],
        // },
        // lato: {
        //     fonts: [
        //         'bower_components/lato-webfont/fonts/*'
        //     ],
        //     scss_include: [
        //         'bower_components/lato-webfont/scss/'
        //     ]
        // },
        dotdotdot: {
            js_include: [
                'bower_components/jQuery.dotdotdot/src/jquery.dotdotdot.js'
            ]
        },
        waves: {
            js_include: [
                'bower_components/Waves/src/js/waves.js'
            ],
            scss: [
                // 'bower_components/Waves/src/scss/waves.scss'
            ]
        },
        // jqlazy: {
        //     js_include: [
        //         'bower_components/jquery_lazyload/jquery.lazyload.js'
        //     ]
        // },

        "what-input": {
            js_include: [
                'bower_components/what-input/dist/what-input.js'
            ]
        },
        cds: {
            scss_include: [
                'components/cds'
            ]
        },

        jquery_form: {
            js_include: [
                'bower_components/jquery-form/dist/jquery.form.min.js'
            ]
        },
        webfontloader: {
            js_include: [
                'bower_components/webfontloader/webfontloader.js'
            ]
        },
        modal: {
            js_include: [
                'bower_components/mmodal/js/jquery.mindy.modal.js'
            ],
            scss_include: [
                'bower_components/mmodal/scss/'
            ]
        },
        bourbon: {
            scss_include: [
                'bower_components/bourbon/app/assets/stylesheets/'
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
                'bower_components/PACE/pace.js'
            ],
            css: [
                // 'bower_components/PACE/themes/black/pace-theme-minimal.css'
                // 'bower_components/PACE/themes/red/pace-theme-minimal.css'
            ]
        },
        simplebar: {
            css_raw: [
                'node_modules/simplebar/dist/simplebar.css'
            ]
        },
        foundation: {
            js_include: [
                // 'bower_components/foundation-sites/dist/js/foundation.js', //all
                'bower_components/foundation-sites/dist/js/plugins/foundation.core.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.offcanvas.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.accordion.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.sticky.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.toggler.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.smoothScroll.js',
                'bower_components/foundation-sites/dist/js/plugins/foundation.abide.js',
                // 'bower_components/foundation-sites/dist/js/plugins/foundation.dropdown.js',
                // 'bower_components/foundation-sites/dist/js/plugins/foundation.dropdownMenu.js',
                // 'bower_components/foundation-sites/dist/js/plugins/foundation.tooltip.js',

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