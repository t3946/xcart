const imagemin = require('gulp-imagemin');

module.exports = {
    name: 'main',
    compress: true,
    inline_image: {
        baseDir: './frontend/css'
    },
    webpack: require('./webpack.frontend'),
    imagemin: [
        imagemin.gifsicle({interlaced: true}),
        imagemin.jpegtran({progressive: true}),
        imagemin.optipng({optimizationLevel: 5}),
        imagemin.svgo({plugins: [{removeViewBox: true, removeComments: true, removeMetadata: true}]})
    ],
};