const fs = require('fs');
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
const browserify = require('gulp-browserify');
const spawn = require('child_process').spawn;
const inlineimage = require('gulp-inline-image');
const pump = require('pump');

let watch = false;

let frontend = require('./config/gulp.frontend');
let backend = require('./config/gulp.backend');

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

gulp.task('frontend:scss', function() {
    return gulp.src(frontend.src.scss)
        .pipe(sass({
            includePaths: frontend.src.scss_include ? frontend.src.scss_include : []
        }).on('error', sass.logError))
        .pipe(inlineimage())
        .pipe(gulp.dest(frontend.dst.scss));
});

gulp.task('frontend:css:raw', function() {
    let pipe = gulp.src(frontend.src.css_raw);

    return pipe.pipe(concat(frontend.config.name + '.css'))
        .pipe(gulp.dest(frontend.dst.scss));
});

gulp.task('backend:scss', function() {
    return gulp.src(backend.src.scss)
        .pipe(sass({
            includePaths: backend.src.scss_include ? backend.src.scss_include : []
        }).on('error', sass.logError))
        // .pipe(inlineimage())
        .pipe(gulp.dest(backend.dst.scss));
});

gulp.task('frontend:css', ['frontend:scss', 'frontend:css:raw'], function () {
    let pipe = gulp.src(frontend.src.css)
        .pipe(autoprefixer({
            browsers: ["> 5%", "last 2 versions", "last 4 iOS versions"],
            cascade: false
        }));

    if (frontend.config.compress) {
        pipe = pipe.pipe(cssnano())
    }

    return pipe.pipe(gulp.dest(frontend.dst.css))
        .pipe(hashsum({filename: 'frontend/versions/css.yml', hash: 'md5'}))
        .pipe(livereload());
});

gulp.task('backend:css', ['backend:scss'], function () {
    let pipe = gulp.src(backend.src.css)
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }));

    if (backend.config.compress) {
        pipe = pipe.pipe(cssnano())
    }

    return pipe.pipe(concat(backend.config.name + '.css'))
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

// gulp.task('frontend:jsx', function(done) {
//     pump([
//             gulp.src(frontend.src.jsx),
//             webpackStream(frontend.config.webpack, webpack2),
//             gulp.dest(frontend.dst.jsx)
//         ],
//         done
//     );
// });

gulp.task('frontend:jsx', function(done){

    let args = ['./node_modules/webpack/bin/webpack.js', '--config', './config/webpack.frontend.js'];
    if (watch) {
        args.push('--progress');
        args.push('-w');
    }

    let cmd = spawn('node', args, {stdio: 'inherit'});
    cmd.on('close', function (code) {
        console.log('frontend:jsx exited with code ' + code);
        done(code);
    });
});

let fjsinc_builded = false;
gulp.task('frontend:js:includes', function(done){
    if (!fjsinc_builded) {
        let pipe = gulp.src(frontend.src.js_include);

        if (frontend.config.compress) {
            pipe = pipe.pipe(uglify(frontend.config.uglify));
            fjsinc_builded = true;
        }

        return pipe
            .pipe(concat('vendors.js'))
            .pipe(hashsum({filename: 'frontend/versions/vendor_js.yml', hash: 'md5'}))
            .pipe(gulp.dest(frontend.dst.js));
    }

    done();
});

gulp.task('frontend:js', ['frontend:js:includes'], function() {
    let pipe = gulp.src(frontend.src.js);

    return pipe
        .pipe(concat(frontend.config.name + '.js'))
        .pipe(gulp.dest(frontend.dst.js))
        .pipe(hashsum({filename: 'frontend/versions/js.yml', hash: 'md5'}))
        .pipe(livereload());
});


gulp.task('backend:jsx', function() {
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

gulp.task('backend:js', ['backend:jsx'], function() {
    let pipe = gulp.src(backend.src.js);

    if (backend.config.compress) {
        pipe = pipe.pipe(uglify())
    }

    return pipe
        .pipe(concat(backend.config.name + '.js'))
        .pipe(gulp.dest(backend.dst.js))
        .pipe(hashsum({filename: 'backend/versions/js.yml', hash: 'md5'}))
        .pipe(livereload());
});

gulp.task('frontend:images', function() {
    let pipe = gulp.src(frontend.src.images);

    if (frontend.config.compress) {
        pipe = pipe.pipe(imagemin(frontend.config.imagemin || {}));
    }
    return pipe
        .pipe(gulp.dest(frontend.dst.images))
        .pipe(livereload());
});

gulp.task('backend:images', function() {
    let pipe = gulp.src(backend.src.images);

    if (backend.config.compress) {
        pipe = pipe.pipe(imagemin(backend.config.imagemin || {}));
    }
    return pipe
        .pipe(gulp.dest(backend.dst.images))
        .pipe(livereload());
});

gulp.task('frontend:fonts', function() {
    return gulp.src(frontend.src.fonts)
        .pipe(gulp.dest(frontend.dst.fonts)).pipe(livereload());
});

gulp.task('backend:fonts', function() {
    return gulp.src(backend.src.fonts)
        .pipe(gulp.dest(backend.dst.fonts)).pipe(livereload());
});

gulp.task('frontend:raw', function() {
    return gulp.src(frontend.src.raw)
        .pipe(gulp.dest(frontend.dst.raw)).pipe(livereload());
});

gulp.task('backend:raw', function() {
    return gulp.src(backend.src.raw)
        .pipe(gulp.dest(backend.dst.raw)).pipe(livereload());
});

gulp.task('watch:frontend', ['build:frontend'], function() {
    watch = true;
    livereload({ start: true });
    // const js_watch = frontend.src.js.concat(frontend.src.jsx);

    gulp.watch(frontend.src.raw, ['frontend:raw']);
    gulp.watch(frontend.src.scss, ['frontend:css']);
    gulp.watch(frontend.src.css, ['frontend:css']);
    gulp.watch(frontend.src.js, ['frontend:js']);
    gulp.watch(frontend.src.images, ['frontend:images']);
    gulp.watch(frontend.src.fonts, ['frontend:fonts']);

    gulp.start('frontend:jsx');
});

gulp.task('watch:backend', ['build:backend'], function() {
    livereload({ start: true });

    gulp.watch(backend.src.raw, ['backend:raw']);
    gulp.watch(backend.src.jsx, ['backend:js']);
    gulp.watch(backend.src.js, ['backend:js']);
    gulp.watch(backend.src.scss, ['backend:css']);
    gulp.watch(backend.src.css, ['backend:css']);
    gulp.watch(backend.src.images, ['backend:images']);
    gulp.watch(backend.src.fonts, ['backend:fonts']);
});

gulp.task('prepare:frontend', ['clear:frontend' , 'frontend:jsx'], function(done){

    if (!fs.existsSync(frontend.dst.scss)){
        fs.mkdirSync(frontend.dst.scss);
    }

    if (!fs.existsSync(frontend.dst.jsx)){
        fs.mkdirSync(frontend.dst.jsx);
    }

    done();
});

gulp.task('watch', ['build'], function() {
    gulp.start(
        'watch:backend' , 'watch:frontend'
    );
});


gulp.task('clear:frontend', function() {
    return gulp.src(['frontend/dist/*', 'frontend/temp/*', frontend.dst.jsx, frontend.dst.scss]).pipe(rimraf());
});

gulp.task('clear:backend', function() {
    return gulp.src(['backend/dist/*', 'backend/temp/*', backend.dst.jsx, backend.dst.scss]).pipe(rimraf());
});

gulp.task('clear', function() {
    gulp.start(
        'clear:frontend', 'clear:backend'
    );
});

gulp.task('build:frontend', ['clear:frontend', 'prepare:frontend'], function(){
    gulp.start(
        'frontend:raw', 'frontend:css', 'frontend:js', 'frontend:images', 'frontend:fonts'
    );
});

gulp.task('build:backend', ['clear:backend'], function(){
    gulp.start(
        'backend:raw', 'backend:css', 'backend:js', 'backend:images', 'backend:fonts'
    );
});

gulp.task('build', function(){
    gulp.start(
        'build:backend' , 'build:frontend'
    );
});

gulp.task('default', function(){
    gulp.start('watch');
});
