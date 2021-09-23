const imagemin = require('gulp-imagemin');

module.exports ={
    name: 'main',
    compress: false,
    babel: {
        presets: [
            [ "env", {
                "targets": {
                    "browsers": ["last 5 versions", "safari >= 9"],
                    "uglify": true,
                },
                "production": {
                    "presets": ["minify"]
                },
                // "modules": false,
                "loose": true,
            }],
        ]
    },
    inline_image: {
        baseDir: './backend/css'
    },
    imagemin: [
        imagemin.gifsicle({
            interlaced: true
        }),
        imagemin.jpegtran({
            progressive: true,
            optimize: true,
        }),
        imagemin.optipng({
            optimizationLevel: 7,
            bitDepthReduction: true,
            colorTypeReduction: true,
            paletteReduction: true,
            buffer: true
        }),
        imagemin.svgo({plugins: [{
                removeViewBox: false,
                removeComments: true,
                removeMetadata: true,
                removeUselessDefs: true,
                removeDimensions: true,
                removeEditorsNSData: true,
                removeEmptyAttrs: true,
                removeHiddenElems: true,
                removeEmptyContainers: true,
                cleanupEnableBackground: true,
                cleanupIDs: true,
                minifyStyles: true,
                collapseGroups: true,
                convertPathData: true
            }]})
    ],
    cssnano: {
        preset: ['default'],
        discardComments: { removeAll: true, },
        reduceIdents: false,
        zindex: false,
    },
    uglify: {
        compress: {
            sequences: true,
            properties: true,
            drop_debugger: true,
            dead_code: true,
            conditionals: true,
            booleans: true,
            unused: true,
            if_return: true,
            join_vars: true,
            drop_console: false,
            warnings: true
        }
    }
};