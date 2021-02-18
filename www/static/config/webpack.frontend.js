const webpack = require( 'webpack' );
const path = require( 'path' );
const BowerResolvePlugin = require( "bower-resolve-webpack-plugin" );
const paths = require( './gulp.frontend.patchs' );

config = {
    entry: paths.src.jsx_bundles,
    output: {
        path: path.resolve( './' + paths.dst.jsx ),
        filename: '[name].js'
    },
    target: "web",
    resolve: {
        alias: {
            modernizr$: path.resolve( __dirname, "./support/modernizrrc.js" ),
            'jQuery': 'jquery',
            'react': 'preact-compat',
            'react-dom': 'preact-compat',
            // Not necessary unless you consume a module using `createClass`
            'create-react-class': 'preact-compat/lib/create-react-class',
            'Classes': path.resolve('./frontend/js/Classes'),
        },
        modules: [
            'frontend/jsx',
            paths.modules.jsx,
            path.resolve( './' + paths.modules.jsx ),
            'node_modules',
            'bower_components',
        ],
        plugins: [ new BowerResolvePlugin( {
            modulesDirectories: [ "bower_components" ],
            includes: /.*/,
            excludes: [],
            searchResolveModulesDirectories: true
        } ) ],
        descriptionFiles: [ 'bower.json', 'package.json' ],
        mainFields: [ 'browser', 'main' ],
        extensions: [ '.js', '.jsx', '.json' ],
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)?$/,
                exclude: /(node_modules)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        comments: false,
                        presets: [
                            [ "react" ],
                            [ "env", {
                                "targets": {
                                    "browsers": [ "last 10 versions", "safari >= 8" ],
                                    "uglify": true,
                                },
                                "production": {
                                    "presets": [ "minify" ]
                                },
                                // "modules": false,
                                "loose": true,
                            } ],
                        ],
                        plugins: [
                            [ "transform-object-rest-spread", { "useBuiltIns": true } ],
                            [ "transform-react-jsx", {
                                "pragma": "h" // default pragma is React.createElement
                            } ],
                            [ "module-resolver", {
                                "root": [ "." ],
                                "alias": {
                                    "react": "preact-compat",
                                    "react-dom": "preact-compat",
                                    "create-react-class": "preact-compat/lib/create-react-class"
                                }
                            } ],
                        ]
                    }
                }
            },
            {
                test: /modernizrrc(\.js)?$/,
                use: [
                    {
                        loader: 'modernizr-loader',
                        options: require( __dirname + '/support/modernizrrc.js' ),
                    },
                ]
            },
        ]
    },
    plugins: [
        new webpack.ProvidePlugin( {
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery'
        } ),
        new webpack.LoaderOptionsPlugin( {
            minimize: true,
            debug: false,
            options: {
                context: __dirname
            }
        } ),
        new webpack.DefinePlugin( {
            'process.env': {
                NODE_ENV: JSON.stringify( process.env.NODE_ENV || 'development' )
            }
        } ),
    ],
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000
    }
};

if ( process.env.NODE_ENV === 'production' ) {
    config.plugins.push(
        new webpack.optimize.UglifyJsPlugin( {
            ie8: false,
            ecma: 6,
            sourceMap: false,
            output: {
                comments: false,
                beautify: false,
            },
            compress: {
                passes: 2,
                unsafe_math: true,
                unsafe_proto: true,

                reduce_vars: true,
                cascade: true,

                loops: true,
                comparisons: true,
                sequences: true,
                properties: true,
                drop_debugger: true,
                dead_code: true,
                conditionals: true,
                booleans: true,
                unused: true,
                if_return: true,
                join_vars: true,
                warnings: true
            },
            parallel: {
                cache: true,
            },

            warningsFilter: ( src ) => true
        } )
    );
}

module.exports = config;
