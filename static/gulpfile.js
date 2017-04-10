const gulp = require('gulp');
const concat = require('gulp-concat');
const cssnano = require('gulp-cssnano');
const imagemin = require('gulp-imagemin');
const livereload = require('gulp-livereload');
const rimraf = require('gulp-rimraf');
const sass = require('gulp-sass');
const hashsum = require('gulp-hashsum');
const uglify = require('gulp-uglify');
const autoprefixer = require('gulp-autoprefixer');
const babel = require('gulp-babel');
const inlineimage = require('gulp-inline-image');
const svgo = require('gulp-svgo');

let config = require('./gulpconfig');
let frontend = config.frontend;
let backend = config.backend;

function buildVendorsData(vendors) {
    let vendorsData = {};
    for (let vendor in vendors) {
        if (vendors.hasOwnProperty(vendor)) {
            let vendorConfig = vendors[vendor];
            for (let type in vendorConfig) {
                if (vendorConfig.hasOwnProperty(type)) {
                    let matches = vendorConfig[type];
                    if (typeof vendorsData[type] == 'undefined') {
                        vendorsData[type] = [];
                    }
                    if (typeof matches == 'string') {
                        vendorsData[type].unshift(matches);
                    } else {
                        vendorsData[type] = [].concat(vendorsData[type], matches)
                    }
                }
            }
        }
    }
    return vendorsData;
}

let frontendVendorsData = buildVendorsData(frontend.vendors);
for (let vendorType in frontendVendorsData) {
    if (frontendVendorsData.hasOwnProperty(vendorType)) {
        if (!frontend.src.hasOwnProperty(vendorType)) {
            frontend.src[vendorType] = [];
        }
        frontend.src[vendorType] = [].concat(frontendVendorsData[vendorType], frontend.src[vendorType]);
    }
}

let backendVendorsData = buildVendorsData(backend.vendors);
for (let vendorType in backendVendorsData) {
    if (backendVendorsData.hasOwnProperty(vendorType)) {
        if (!backend.src.hasOwnProperty(vendorType)) {
            backend.src[vendorType] = [];
        }
        backend.src[vendorType] = [].concat(backendVendorsData[vendorType], backend.src[vendorType]);
    }
}

gulp.task('frontend_scss', function() {
    return gulp.src(frontend.src.scss)
        .pipe(sass({
            includePaths: frontend.src.scss_include ? frontend.src.scss_include : []
        }).on('error', sass.logError))
        // .pipe(inlineimage())
        .pipe(gulp.dest(frontend.dst.scss));
});

gulp.task('backend_scss', function() {
    return gulp.src(backend.src.scss)
        .pipe(sass({
            includePaths: backend.src.scss_include ? backend.src.scss_include : []
        }).on('error', sass.logError))
        // .pipe(inlineimage())
        .pipe(gulp.dest(backend.dst.scss));
});

gulp.task('frontend_css', ['frontend_scss'], function () {
    let pipe = gulp.src(frontend.src.css)
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }));

    if (config.compress) {
        pipe = pipe.pipe(cssnano())
    }

    return pipe.pipe(concat(config.name + '.css'))
        // .pipe(inlineimage(frontend.config.inline_image || {}))
        // .on('error',  function(err) {
        //     console.log('[Compilation Error]');
        //     console.log(err.fileName + ( err.loc ? `( ${err.loc.line}, ${err.loc.column} ): ` : ': '));
        //     console.log('error Babel: ' + err.message + '\n');
        //     console.log(err.codeFrame);
        //
        //     this.emit('end');
        // })
        .pipe(gulp.dest(frontend.dst.css))
        .pipe(hashsum({filename: 'frontend/versions/css.yml', hash: 'md5'}))
        .pipe(livereload());
});

gulp.task('backend_css', ['backend_scss'], function () {
    let pipe = gulp.src(backend.src.css)
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }));

    if (config.compress) {
        pipe = pipe.pipe(cssnano())
    }

    return pipe.pipe(concat(config.name + '.css'))
        // .pipe(inlineimage(backend.config.inline_image || {}))
        // .on('error',  function(err) {
        //     console.log('[Compilation Error]');
        //     console.log(err.fileName + ( err.loc ? `( ${err.loc.line}, ${err.loc.column} ): ` : ': '));
        //     console.log('error Babel: ' + err.message + '\n');
        //     console.log(err.codeFrame);
        //
        //     this.emit('end');
        // })
        .pipe(gulp.dest(backend.dst.css))
        .pipe(hashsum({filename: 'backend/versions/css.yml', hash: 'md5'}))
        .pipe(livereload());
});

gulp.task('frontend_jsx', function() {
    let pipe = gulp.src(frontend.src.jsx);

    if (frontend.config && frontend.config.babel) {
        pipe = pipe.pipe(babel(frontend.config.babel))
            .on('error',  function(err) {
                // For gulp-util users u can use a more colorfull variation
                // util.log(util.colors.red('[Compilation Error]'));
                // util.log(err.fileName + ( err.loc ? `( ${err.loc.line}, ${err.loc.column} ): ` : ': '));
                // util.log(util.colors.red('error Babel: ' + err.message + '\n'));
                // util.log(err.codeFrame);

                console.log('[Compilation Error]');
                console.log(err.fileName + ( err.loc ? `( ${err.loc.line}, ${err.loc.column} ): ` : ': '));
                console.log('error Babel: ' + err.message + '\n');
                console.log(err.codeFrame);

                this.emit('end');
            });
    }

    return pipe.pipe(gulp.dest(frontend.dst.jsx));
});

gulp.task('frontend_js', ['frontend_jsx'], function() {
    let pipe = gulp.src(frontend.src.js);

    if (frontend.config && frontend.config.babel) {
        pipe = pipe.pipe(babel(frontend.config.babel));
    }

    if (config.compress) {
        pipe = pipe.pipe(uglify())
    }
    return pipe
        .pipe(concat(config.name + '.js'))
        .pipe(gulp.dest(frontend.dst.js))
        .pipe(hashsum({filename: 'frontend/versions/js.yml', hash: 'md5'}))
        .pipe(livereload());
});


gulp.task('backend_jsx', function() {
    let pipe = gulp.src(backend.src.jsx);

    if (backend.config && backend.config.babel) {
        pipe = pipe.pipe(babel(backend.config.babel))
            .on('error',  function(err) {
                // For gulp-util users u can use a more colorfull variation
                // util.log(util.colors.red('[Compilation Error]'));
                // util.log(err.fileName + ( err.loc ? `( ${err.loc.line}, ${err.loc.column} ): ` : ': '));
                // util.log(util.colors.red('error Babel: ' + err.message + '\n'));
                // util.log(err.codeFrame);

                console.log('[Compilation Error]');
                console.log(err.fileName + ( err.loc ? `( ${err.loc.line}, ${err.loc.column} ): ` : ': '));
                console.log('error Babel: ' + err.message + '\n');
                console.log(err.codeFrame);

                this.emit('end');
            });
    }

    return pipe.pipe(gulp.dest(backend.dst.jsx));
});

gulp.task('backend_js', ['backend_jsx'], function() {
    let pipe = gulp.src(backend.src.js);

    if (config.compress) {
        pipe = pipe.pipe(uglify())
    }

    return pipe
        .pipe(concat(config.name + '.js'))
        .pipe(gulp.dest(backend.dst.js))
        .pipe(hashsum({filename: 'backend/versions/js.yml', hash: 'md5'}))
        .pipe(livereload());
});

gulp.task('frontend_images', function() {
    let pipe = gulp.src(frontend.src.images);

    if (config.compress) {
        pipe = pipe.pipe(imagemin(frontend.config.imagemin || {}));
    }
    return pipe
        .pipe(gulp.dest(frontend.dst.images))
        .pipe(livereload());
});

gulp.task('backend_images', function() {
    let pipe = gulp.src(backend.src.images);

    if (config.compress) {
        pipe = pipe.pipe(imagemin(backend.config.imagemin || {}));
    }
    return pipe
        .pipe(gulp.dest(backend.dst.images))
        .pipe(livereload());
});

gulp.task('frontend_fonts', function() {
    return gulp.src(frontend.src.fonts)
        .pipe(gulp.dest(frontend.dst.fonts)).pipe(livereload());
});

gulp.task('backend_fonts', function() {
    return gulp.src(backend.src.fonts)
        .pipe(gulp.dest(backend.dst.fonts)).pipe(livereload());
});

gulp.task('frontend_raw', function() {
    return gulp.src(frontend.src.raw)
        .pipe(gulp.dest(frontend.dst.raw)).pipe(livereload());
});

gulp.task('backend_raw', function() {
    return gulp.src(backend.src.raw)
        .pipe(gulp.dest(backend.dst.raw)).pipe(livereload());
});

gulp.task('watch_frontend', ['build_frontend'], function() {
    livereload({ start: true });

    gulp.watch(frontend.src.raw, ['frontend_raw']);
    gulp.watch(frontend.src.scss, ['frontend_css']);
    gulp.watch(frontend.src.css, ['frontend_css']);
    gulp.watch(frontend.src.jsx, ['frontend_js']);
    gulp.watch(frontend.src.js, ['frontend_js']);
    gulp.watch(frontend.src.images, ['frontend_images']);
    gulp.watch(frontend.src.fonts, ['frontend_fonts']);
});

gulp.task('watch_backend', ['build_backend'], function() {
    livereload({ start: true });

    gulp.watch(backend.src.raw, ['backend_raw']);
    gulp.watch(backend.src.jsx, ['backend_js']);
    gulp.watch(backend.src.js, ['backend_js']);
    gulp.watch(backend.src.scss, ['backend_css']);
    gulp.watch(backend.src.css, ['backend_css']);
    gulp.watch(backend.src.images, ['backend_images']);
    gulp.watch(backend.src.fonts, ['backend_fonts']);
});

gulp.task('watch', ['build'], function() {
    gulp.start(
        'watch_backend' , 'watch_frontend'
    );
});


gulp.task('clear_frontend', function() {
    return gulp.src(['frontend/dist/*', 'frontend/temp/*', frontend.dst.jsx, frontend.dst.scss]).pipe(rimraf());
});

gulp.task('clear_backend', function() {
    return gulp.src(['backend/dist/*', 'backend/temp/*', backend.dst.jsx, backend.dst.scss]).pipe(rimraf());
});

gulp.task('clear', function() {
    gulp.start(
        'clear_frontend', 'clear_backend'
    );
});

gulp.task('build_frontend', ['clear_frontend'], function(){
    gulp.start(
        'frontend_raw', 'frontend_css', 'frontend_js', 'frontend_images', 'frontend_fonts'
    );
});

gulp.task('build_backend', ['clear_backend'], function(){
    gulp.start(
        'backend_raw', 'backend_css', 'backend_js', 'backend_images', 'backend_fonts'
    );
});

gulp.task('build', function(){
    gulp.start(
        'build_backend' , 'build_frontend'
    );
});

gulp.task('default', function(){
    gulp.start('watch');
});
