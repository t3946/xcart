const fs = require('fs');
const imagemin = require('gulp-imagemin');

// var modulesDir = 'node_modules';
var modulesDir = '../app/Modules';

var modules = fs.readdirSync(modulesDir).map(function (module) {
    return modulesDir + '/' + module;
});


module.exports = {
    config: require('./gulp.backend.configs'),
    dst: {
        js: 'backend/dist/js',
        scss: 'temp/backend/css',
        jsx: 'temp/backend/js',
        css: 'backend/dist/css',
        images: 'backend/dist/images',
        fonts: 'backend/dist/fonts',
        raw: 'backend/dist/raw'
    },
    src: {
        jsx: [
            // 'backend/jsx/**/*'
            'backend/jsx/main.jsx'
        ],
        js: [
            'backend/js/**/*',
            'temp/backend/js/**/*',
        ].concat(modules.map(function(dir) {
            return dir + '/static/js/**/*.*'
        })),
        scss: [
            'backend/scss/**/*.scss'
        ].concat(modules.map(function(dir) {
            return dir + '/static/scss/**/*.*'
        })),
        scss_include: [],
        css: [
            'backend/css/*',
            'temp/backend/css/**/*',
        ].concat(modules.map(function(dir) {
            return dir + '/static/css/**/*.*'
        })),
        images: [
            'backend/images/**/*.*'
        ],
        fonts: [
            'backend/fonts/GothamPro/fonts/**/*',
            'backend/fonts/icons/fonts/*'
        ],
        raw: [].concat(modules.map(function(dir) {
            return dir + '/static/raw/*/**'
        }))
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
        font_icons: {
            css: [
                'backend/fonts/icons/css/style.css'
            ],
            fonts: [
                'backend/fonts/icons/fonts/*'
            ],
        },

        mmodal: {
            js: [
                'bower_components/mmodal/js/jquery.mindy.modal.js'
            ]
            // scss: [
            //     'bower_components/mmodal/scss/jquery.mmodal.scss'
            // ]
        },
        modal: {
            js: [
                'components/modal/modal.js'
            ]
        },
        mouse_wheel: {
            js: [
                'bower_components/jquery-mousewheel/jquery.mousewheel.js'
            ]
            // scss: [
            //     'bower_components/mmodal/scss/jquery.mmodal.scss'
            // ]
        },
        underscore: {
            js: [
                'bower_components/underscore/underscore.js'
            ]
        },
        confirm: {
            js: [
                'components/confirm/jquery.confirm.js'
            ]
        },
        ui_custom: {
            js: [
                'components/ui-custom/jquery-ui.min.js'
            ],
            css: [
                'components/ui-custom/jquery-ui.min.css'
            ]
        },
        deparam: {
            js: [
                'components/deparam/jquery.deparam.js'
            ]
        },
        flow: {
            js: [
                'bower_components/flow-js/dist/flow.js'
            ]
        },
        files_field: {
            js: [
                'components/fields/js/filesfield.js'
            ]
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

        'font-gotham_pro': {
            fonts: [
                'backend/fonts/GothamPro/fonts/**/*'
            ],
            css: [
                'backend/fonts/GothamPro/css/*',
                'backend/fonts/gothampro/css/*',
            ]
        },

        cds: {
            scss_include: [
                'components/cds'
            ]
        },

        'compass-mixins': {
            scss_include: [
                'bower_components/compass-mixins/lib/'
            ]
        },
        'mindy-sass': {
            scss_include: [
                'bower_components/mindy-sass/'
            ]
        }
    }
};
