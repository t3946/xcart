const webpack = require('webpack');
const _ = require('lodash');
const path = require('path');
const BowerResolvePlugin = require("bower-resolve-webpack-plugin");
const paths = require('./gulp.frontend.patchs');
// const conf_dev = require('./webpack/webpack.develop');
// const conf_base = require('./webpack/webpack.base');


module.exports = {
    // devtool: 'source-map',
    entry: paths.src.jsx_bundles,
    output: {
        path: path.resolve('./' + paths.dst.jsx),
        filename: '[name]-bundle.js'
    },
    target: "web",
    resolve: {
        alias: {
            modernizr$: path.resolve(__dirname, "./support/modernizrrc.js"),
            'react': 'preact-compat',
            'react-dom': 'preact-compat',
            // Not necessary unless you consume a module using `createClass`
            'create-react-class': 'preact-compat/lib/create-react-class'
        },
        modules: [
            'frontend/jsx',
            paths.modules.jsx,
            path.resolve('./' + paths.modules.jsx),
            'node_modules',
            'bower_components'
        ],
        plugins: [new BowerResolvePlugin()],
        descriptionFiles: ['bower.json', 'package.json'],
        mainFields: ['browser', 'main'],
        extensions: ['.js', '.jsx', '.json']
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)?$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            [ "es2015", { "modules": false }],
                            [ "es2016" ],
                            // [ "babili" ],
                            [ "react" ]
                        ],
                        plugins: [
                            ["transform-react-jsx", {
                                "pragma":"h" // default pragma is React.createElement
                            }],
                            ["module-resolver", {
                                "root": ["."],
                                "alias": {
                                    "react": "preact-compat",
                                    "react-dom": "preact-compat",
                                    // Not necessary unless you consume a module using `createClass`
                                    "create-react-class": "preact-compat/lib/create-react-class"
                                }
                            }]

                        ]
                    }
                }
            },
            {
                test: /modernizrrc(\.js)?$/,
                use: [
                    {
                        loader: 'modernizr-loader',
                        options: require(__dirname + '/support/modernizrrc.js'),
                    },
                ]
            },
        ]
    },
    plugins: [
        new webpack.optimize.UglifyJsPlugin({
            ie8: false,
            ecma: 5,
            sourceMap: false,
            output: {
                comments: false,
                beautify: false,
            },
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
                // drop_console: true,
                warnings: true
            },
            parallel: {
                cache: true,
                workers: 2 // for e.g
            },
        }),
        new webpack.ProvidePlugin({
        //     'Promise': 'bluebird'
            $: "jquery",
            jQuery: "jquery",
            "window.jQuery": "jquery"
        }),
        new webpack.LoaderOptionsPlugin({
            minimize: true,
            debug: false,
            options: {
                context: __dirname
            }
        }),
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: JSON.stringify(process.env.NODE_ENV || 'development')
            }
        }),
    ]
};