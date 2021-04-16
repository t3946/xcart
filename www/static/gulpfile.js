/**
 * A LITTLE ABOUT
 *
 * Recommended to write in this files only imports and tasks definitions in global name space
 * All task divided on thematic sections and chapters
 *
 * sections as: [FRONTEND ...] [BACKEND ...]
 * chapters as: [STYLES] [SCRIPTS] ...
 * [STYLES] / [SCRIPTS] / [FONTS] / [IMAGES] this chapters correspond one-name tasks
 * [UTILS] this chapter for tasks that improves your work experience
 * [BUILD] must have only one task that only bundle all assets
 * [WATCH] all watch tasks must be in this chapter
 */

/**
 * gulp modules
 */
const gulp = require( 'gulp' );
const autoprefixer = require( 'gulp-autoprefixer' );
const concat = require( 'gulp-concat' );
const cssnano = require( 'gulp-cssnano' );
const inlineImage = require( 'gulp-inline-image' );
const sass = require( 'gulp-sass' );
const hashSum = require( 'gulp-hashsum' );
const babel = require( 'gulp-babel' );
const uglify = require( 'gulp-uglify' );
const imagemin = require( 'gulp-imagemin' );
const rimraf = require( 'gulp-rimraf' );

const spawn = require( 'child_process' ).spawn;

// user code
const GulpAssets = require( './GulpAssets' ).default;

// configurations
const frontend = require( './config/gulp.frontend' );
const backend = require( './config/gulp.backend' );

/**
 *
 *          [FRONTEND START]
 *
 */

/**
 *
 *          [STYLES]
 *
 */

/**
 * build bem styles for frontend
 */
gulp.task( 'frontend:bem', async function() {
    const bemLevelsOrder = [
        'common',
        'form',
        'checkout',
    ];
    const bemOrderedPaths = await GulpAssets.BemOrderBuilder( 'frontend/bem/blocks', bemLevelsOrder, 'scss' );

    return gulp
        .src( bemOrderedPaths )
        .pipe( concat( 'bem.scss' ) )
        .pipe( gulp.dest( 'frontend/bem/' ) );
} );

gulp.task( 'frontend:scss', function() {
    return gulp
        .src( frontend.src.scss )
        .pipe( sass( {
            includePaths: frontend.src.scss_include ? frontend.src.scss_include : [],
        } ).on( 'error', sass.logError ) )
        .pipe( inlineImage() )
        .pipe( gulp.dest( frontend.dst.scss ) );
} );

gulp.task( 'frontend:css:raw', function() {
    let pipe = gulp.src( frontend.src.css_raw );

    return pipe
        .pipe( concat( frontend.config.name + '.css' ) )
        .pipe( gulp.dest( frontend.dst.scss ) );
} );

gulp.task( 'frontend:css', gulp.series( 'frontend:scss', 'frontend:css:raw', () => {
    let pipe = gulp
        .src( frontend.src.css )
        .pipe( autoprefixer( {
            browsers: [ '> 5%', 'last 2 versions', 'last 4 iOS versions' ],
            cascade: false,
        } ) );

    if ( GulpAssets.isProduction && frontend.config.compress ) {
        pipe = pipe.pipe( cssnano( frontend.config.cssnano ) );
    }

    return pipe
        .pipe( hashSum( { filename: 'frontend/versions/css.yml', hash: 'md5' } ) )
        .pipe( gulp.dest( frontend.dst.css ) );
} ) );

/**
 *
 *          [SCRIPTS]
 *
 */

gulp.task( 'frontend:jsx', function( done ) {
    let args = [ './node_modules/webpack/bin/webpack.js', '--config', './config/webpack.frontend.js' ];

    GulpAssets.isProduction() && args.push( '-p' );

    const cmd = spawn( 'node', args, { stdio: 'inherit' } );
    const src = frontend.src.js_include;
    const dst = frontend.dst.js;

    GulpAssets.buildJsx( src, dst, cmd, done );
} );

gulp.task( 'watch:frontend:jsx', function( done ) {
    let args = [ './node_modules/webpack/bin/webpack.js', '--config', './config/webpack.frontend.js' ];

    GulpAssets.isProduction() && args.push( '-p' );

    args.push( '--progress' );
    args.push( '-w' );

    const cmd = spawn( 'node', args, { stdio: 'inherit' } );
    const src = frontend.src.js_include;
    const dst = frontend.dst.js;

    GulpAssets.buildJsx( src, dst, cmd, done );
} );

/**
 *
 *          [FONTS]
 *
 */

gulp.task( 'frontend:fonts', function() {
    return gulp
        .src( frontend.src.fonts )
        .pipe( gulp.dest( frontend.dst.fonts ) );
} );

/**
 *
 *          [IMAGES]
 *
 */

gulp.task( 'frontend:images', function() {
    let pipe = gulp.src( frontend.src.images );

    if ( GulpAssets.isProduction() && frontend.config.compress ) {
        pipe = pipe.pipe( imagemin( frontend.config.imagemin || {} ) );
    }
    return pipe
        .pipe( gulp.dest( frontend.dst.images ) );
} );

/**
 *
 *          [UTILS]
 *
 */

/**
 * remove frontend bundles and assets in destinations
 */
gulp.task( 'clear:frontend', function() {
    return gulp.src( [ 'frontend/dist/*', 'frontend/temp/*', frontend.dst.jsx, frontend.dst.scss ] ).pipe( rimraf() );
} );

/**
 *
 *          [WATCH]
 *
 */

/**
 * build scripts for frontend when changed
 */
gulp.task( 'watch:frontend:scripts', gulp.series( 'watch:frontend:jsx' ) );

/**
 * build styles for frontend when changed
 */
gulp.task( 'watch:frontend:styles', gulp.series(
    'frontend:bem',
    function watchStyles() {
        gulp.watch( 'frontend/bem/blocks/**/*.scss', gulp.parallel( 'frontend:bem' ) );
        gulp.watch( [ 'frontend/bem/bem.scss', 'frontend/sass/**/*' ], gulp.parallel( 'frontend:css' ) );
    },
) );

/**
 *
 *          [BUILD]
 *
 */

/**
 * build all frontend assets
 */
gulp.task( 'build:frontend', gulp.series( 'clear:frontend', 'frontend:bem', 'frontend:css', 'frontend:jsx', 'frontend:fonts', 'frontend:images' ) );

/**
 *
 *          [FRONTEND END]
 *
 */

//------------------------------------------------//

/**
 *
 *          [BACKEND START]
 *
 */

/**
 *
 *          [STYLES]
 *
 */

gulp.task( 'backend:scss', function() {
    return gulp
        .src( backend.src.scss )
        .pipe( sass( {
            includePaths: backend.src.scss_include ? backend.src.scss_include : [],
        } ).on( 'error', sass.logError ) )
        .pipe( gulp.dest( backend.dst.scss ) );
} );

gulp.task( 'backend:css', gulp.series( 'backend:scss', () => {
    let pipe = gulp.src( backend.src.css );

    return pipe
        .pipe( concat( backend.config.name + '.css' ) )
        .pipe( gulp.dest( backend.dst.css ) )
        .pipe( hashSum( { filename: 'backend/versions/css.yml', hash: 'md5' } ) );
} ) );

/**
 *
 *          [SCRIPTS]
 *
 */

gulp.task( 'backend:jsx', function() {
    let pipe = gulp.src( backend.src.jsx );

    if ( backend.config && backend.config.babel ) {
        pipe = pipe
            .pipe( babel( backend.config.babel ) )
            .on( 'error', function( err ) {
                console.log( '[Compilation Error]' );
                console.log( err.fileName + ( err.loc ? `( ${ err.loc.line }, ${ err.loc.column } ): ` : ': ' ) );
                console.log( 'error Babel: ' + err.message + '\n' );
                console.log( err.codeFrame );
                this.emit( 'end' );
            } );
    }

    return pipe.pipe( gulp.dest( backend.dst.jsx ) );
} );

gulp.task( 'backend:js', gulp.series( 'backend:jsx', function() {
    let pipe = gulp.src( backend.src.js, { allowEmpty: true } );

    if ( backend.config.compress ) {
        pipe = pipe.pipe( uglify( backend.config.uglify ) );
    }

    return pipe
        .pipe( concat( backend.config.name + '.js' ) )
        .pipe( gulp.dest( backend.dst.js ) )
        .pipe( hashSum( { filename: 'backend/versions/js.yml', hash: 'md5' } ) );
} ) );

/**
 *
 *          [IMAGES]
 *
 */

gulp.task( 'backend:images', function() {
    let pipe = gulp.src( backend.src.images );

    if ( GulpAssets.isProduction() && backend.config.compress ) {
        pipe = pipe.pipe( imagemin( backend.config.imagemin || {} ) );
    }

    return pipe.pipe( gulp.dest( backend.dst.images ) );
} );

/**
 *
 *          [FONTS]
 *
 */

gulp.task( 'backend:fonts', function() {
    return gulp
        .src( backend.src.fonts )
        .pipe( gulp.dest( backend.dst.fonts ) );
} );

gulp.task( 'backend:raw', function() {
    return gulp
        .src( backend.src.raw )
        .pipe( gulp.dest( backend.dst.raw ) );
} );

/**
 *
 *          [UTILS]
 *
 */

/**
 * remove backend bundles and assets in destinations
 */
gulp.task( 'clear:backend', function() {
    return gulp
        .src( [ 'backend/dist/*', 'backend/temp/*', backend.dst.jsx, backend.dst.scss ], { allowEmpty: true } )
        .pipe( rimraf() );
} );

/**
 *
 *          [WATCH]
 *
 */

gulp.task( 'watch:backend', gulp.series(
    function watchALL() {
        gulp.watch( backend.src.raw, [ 'backend:raw' ] );
        gulp.watch( backend.src.jsx, [ 'backend:js' ] );
        gulp.watch( backend.src.js, [ 'backend:js' ] );
        gulp.watch( backend.src.scss, [ 'backend:css' ] );
        gulp.watch( backend.src.css, [ 'backend:css' ] );
        gulp.watch( backend.src.fonts, [ 'backend:fonts' ] );
    },
) );

/**
 *
 *          [BUILD]
 *
 */

gulp.task( 'build:backend', gulp.series( 'clear:backend', 'backend:raw', 'backend:css', 'backend:js', 'backend:images', 'backend:fonts' ) );

/**
 *
 *          [BACKEND END]
 *
 */
