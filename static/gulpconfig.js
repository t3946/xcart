var fs = require('fs');

var modulesDir = 'node_modules';

var modules = fs.readdirSync(modulesDir).map(function (module) {
    return modulesDir + '/' + module;
});

module.exports.name = "main";

module.exports.compress = true;


module.exports.frontend = {
    dst: {},
    src: {},
    vendors: {},
};

module.exports.backend = {
    dst: {
        js: 'backend/dist/js',
        scss: 'backend/temp/css',
        css: 'backend/dist/css',
        images: 'backend/dist/images',
        fonts: 'backend/dist/fonts',
        raw: 'backend/dist/raw'
    },
    config: {
        babel: {
            presets: ['es2015']
        }
    },
    src: {
        js: [
            'backend/js/**/*.js'
        ].concat(modules.map(function (dir) {
            return dir + '/static/backend/js/**/*.*'
        })),
        scss: [
            'backend/scss/**/*.scss'
        ].concat(modules.map(function (dir) {
            return dir + '/static/backend/scss/**/*.*'
        })),
        scss_include: [
            'bower_components/compass-mixins/lib/',
            'bower_components/mindy-sass/'
        ],
        css: [
            'backend/css/*',
            'backend/temp/css/**/*'
        ].concat(modules.map(function (dir) {
            return dir + '/static/backend/css/**/*.*'
        })),
        images: [
            'backend/images/**/*.*'
        ],
        fonts: [],
        raw: [].concat(modules.map(function (dir) {
            return dir + '/static/backend/raw/*/**'
        }))
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
        scss: 'frontend/css',
        css: 'frontend/dist/css',
        images: 'frontend/dist/images',
        fonts: 'frontend/dist/fonts',
        raw: 'frontend/dist/raw'
    },
    src: {
        js: [],
        scss: [
            // 'assets/sass/**/*.scss'
        ],
        css: [
            // 'assets/css/*'
        ],
        images: [
            // 'assets/images/**/*.*'
        ],
        fonts: [
            // 'assets/fonts/**/*'
        ],
        html: [
            // 'templates/**/*'
        ],
        raw: []
    },
    vendors: {}
};