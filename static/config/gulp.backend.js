const fs = require('fs');
const imagemin = require('gulp-imagemin');

var modulesDir = 'node_modules';

var modules = fs.readdirSync(modulesDir).map(function (module) {
    return modulesDir + '/' + module;
});


module.exports = {
    dst: {
        js: 'backend/dist/js',
        scss: 'temp/backend/css',
        jsx: 'temp/backend/js',
        css: 'backend/dist/css',
        images: 'backend/dist/images',
        fonts: 'backend/dist/fonts',
        raw: 'backend/dist/raw'
    },
    config: {
        name: 'main',
        compress: true,
        babel: {
            presets: ['es2015']
        },
        inline_image: {
            baseDir: './backend/css'
        },
        imagemin: [
            imagemin.gifsicle({interlaced: true}),
            imagemin.jpegtran({progressive: true}),
            imagemin.optipng({optimizationLevel: 5}),
            imagemin.svgo({plugins: [{removeViewBox: true, removeComments: true, removeMetadata: true}]})
        ],
    },
    src: {
        jsx: [
            // 'backend/jsx/**/*'
            'backend/jsx/main.jsx'
        ],
        js: [
            'backend/js/**/*',
            'temp/backend/js/**/*',
        ],
        scss: [
            'backend/scss/**/*.scss'
        ],
        scss_include: [
            'bower_components/compass-mixins/lib/',
        ],
        css: [
            'backend/css/*',
            'temp/backend/css/**/*'
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

        'jquery-ui': {
            js: [
                'bower_components/jquery-ui/jquery-ui.js'
            ]
        },

        'jquery.shapeshift': {
            js: [
                'bower_components/jquery.shapeshift/core/jquery.shapeshift.js'
            ]
        },

        'jquery.cookie': {
            js: [
                'bower_components/jquery.cookie/jquery.cookie.js'
            ]
        },
        'jquery-form': {
            js: [
                'bower_components/jquery-form/dist/jquery.form.min.js'
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
        mouse_wheel: {
            js: [
                'bower_components/jquery-mousewheel/jquery.mousewheel.js'
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
        },

        'mindy-sass': {
            scss_include: [
                'bower_components/mindy-sass/'
            ]
        }
    }
};
