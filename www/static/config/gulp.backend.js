const fs = require('fs');
const imagemin = require('gulp-imagemin');

// var modulesDir = 'node_modules';
var modulesDir = '../../app/Modules';

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
        scss_include: [
            'node_modules/compass-mixins/lib/',
            'node_modules/foundation-sites/scss',
            'node_modules/mindy-sass',
            'components/cds',
        ],
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
                'node_modules/jquery/dist/jquery.js'
            ]
        },

        'jquery-ui': {
            js: [
                'node_modules/jquery-ui/jquery-ui.js'
            ],
            css: [
                'node_modules/jquery-ui/themes/base/jquery-ui.css',
                // 'node_modules/jquery-ui/themes/base/theme.css',
                // 'node_modules/jquery-ui/themes/base/tabs.css',
                // 'node_modules/jquery-ui/themes/base/datepicker.css',
            ],
        },

        'jquery.shapeshift': {
            js: [
                'node_modules/jquery.shapeshift/core/jquery.shapeshift.js'
            ]
        },

        'jquery.cookie': {
            js: [
                'node_modules/jquery.cookie/jquery.cookie.js'
            ]
        },
        'jquery-form': {
            js: [
                'node_modules/jquery-form/dist/jquery.form.min.js'
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
                'node_modules/mmodal/js/jquery.mindy.modal.js'
            ]
            // scss: [
            //     'node_modules/mmodal/scss/jquery.mmodal.scss'
            // ]
        },
        modal: {
            js: [
                'components/modal/modal.js'
            ]
        },
        mouse_wheel: {
            js: [
                'node_modules/jquery-mousewheel/jquery.mousewheel.js'
            ]
            // scss: [
            //     'node_modules/mmodal/scss/jquery.mmodal.scss'
            // ]
        },
        underscore: {
            js: [
                'node_modules/underscore/underscore.js'
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
                'node_modules/flow-js/dist/flow.js'
            ]
        },
        files_field: {
            js: [
                'components/fields/js/filesfield.js'
            ]
        },
        select2: {
            js: [
                // 'node_modules/select2/dist/js/select2.full.js'
                'node_modules/select2/dist/js/select2.js'
            ],
            css: [
                'node_modules/select2/dist/css/select2.css'
            ]
        },

        'air-datepicker': {
            js: [
                'components/air-datepicker/dist/js/datepicker.js',
                'components/air-datepicker/dist/js/i18n/datepicker.en.js'
            ],
            css: [
                'components/air-datepicker/dist/css/datepicker.css'
            ]
        },

        'font-awesome': {
            fonts: [
                'node_modules/font-awesome/fonts/*'
            ],
            // css: [
            //     'node_modules/font-awesome/css/font-awesome.css'
            // ]
            scss: [
                'node_modules/font-awesome/scss/*'
            ]
        },

        'font-gotham_pro': {
            fonts: [
                'backend/fonts/GothamPro/fonts/**/*'
            ],
            css: [
                'backend/fonts/GothamPro/css/*'
            ]
        },

        cds: {
            scss_include: [
                'components/cds'
            ]
        },

        'compass-mixins': {
            scss_include: [
                'node_modules/compass-mixins/lib/'
            ]
        },
        'mindy-sass': {
            scss_include: [
                'node_modules/mindy-sass/'
            ]
        }
    }
};
