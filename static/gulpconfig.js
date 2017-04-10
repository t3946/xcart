var fs = require('fs');

var modulesDir = 'node_modules';

var modules = fs.readdirSync(modulesDir).map(function (module) {
    return modulesDir + '/' + module;
});

module.exports.name = "main";

module.exports.compress = true;


module.exports.backend = {
    dst: {
        js: 'backend/dist/js',
        scss: 'backend/temp/css',
        jsx: 'backend/temp/js',
        css: 'backend/dist/css',
        images: 'backend/dist/images',
        fonts: 'backend/dist/fonts',
        raw: 'backend/dist/raw'
    },
    config: {
        babel: {
            presets: ['es2015']
        },
        inline_image: {
            baseDir: './backend/css'
        },
        imagemin: {
            interlaced: true,
            progressive: true,
            optimizationLevel: 5,
            svgoPlugins: [{removeViewBox: true}]
        }
    },
    src: {
        jsx: [
            // 'backend/jsx/**/*'
            'backend/jsx/main.jsx'
        ],
        js: [
            'backend/js/**/*',
            'backend/temp/js/**/*',
        ],
        scss: [
            'backend/scss/**/*.scss'
        ],
        scss_include: [
            'bower_components/compass-mixins/lib/',
            'bower_components/mindy-sass/'
        ],
        css: [
            'backend/css/*',
            'backend/temp/css/**/*'
        ],
        images: [
            'backend/images/**/*.*'
        ],
        fonts: [],
        raw: []
    },
    vendors: {
        jquery: {
            js: [
                'bower_components/jquery/dist/jquery.min.js'
            ]
        },

        'jquery.cookie': {
            js: [
                'bower_components/jquery.cookie/jquery.cookie.js'
            ]
        },

        modal: {
            js: [
                'bower_components/mmodal/js/jquery.mindy.modal.js'
            ]
            // scss: [
            //     'bower_components/mmodal/scss/jquery.mmodal.scss'
            // ]
        },

        select2: {
            js: [
                'bower_components/select2/dist/js/select2.js'
            ],
            css: [
                'bower_components/select2/dist/css/select2.css'
            ]
        },

        'air-datepicker': {
            js: [
                'bower_components/air-datepicker/dist/js/datepicker.js',
                'bower_components/air-datepicker/dist/js/i18n/datepicker.en.js'
            ],
            css: [
                'bower_components/air-datepicker/dist/css/datepicker.css'
            ]
        },

        'font-awesome': {
            fonts: [
                'bower_components/font-awesome/fonts/*'
            ],
            // css: [
            //     'bower_components/font-awesome/css/font-awesome.css'
            // ]
            scss: [
                'bower_components/font-awesome/scss/*'
            ]
        },

        cds: {
            scss_include: [
                'components/cds'
            ]
        }
    }
};

module.exports.frontend = {
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
        babel: {
            presets: ['es2015']
        },
        inline_image: {
            baseDir: './frontend/css'
        }
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
            'bower_components/mindy-sass/'
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
        bootstrap: {
            js: [
                'bower_components/bootstrap-sass/assets/javascripts/bootstrap.js'
            ],
            fonts: [
                'bower_components/bootstrap-sass/assets/fonts/bootstrap/*'
            ],
            scss_include: [
                'bower_components/bootstrap-sass/assets/stylesheets/'
            ]
        },
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
        }
    }
};