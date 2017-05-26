const webpack = require('webpack');
const path = require('path');
const paths = require('./gulp.frontend.patchs');


module.exports = {
    // devtool: 'source-map',
    entry: paths.src.jsx_bundles,
    output: {
        path: path.resolve(__dirname, './' + paths.dst.jsx),
        filename: '[name]-bundle.js'
    },
    target: "web",
    resolve: {
        alias: {
            modernizr$: path.resolve(__dirname, "./support/modernizrrc.js")
        },
        modules: [
            path.resolve(__dirname, './' + paths.modules.jsx),
            'node_modules'
        ],
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
                            [ "es2016" ]
                        ],
                    }
                }
            },
            {
                test: /modernizrrc(\.js)?$/,
                use: [
                    {
                        loader: 'modernizr-loader',
                        options: require('./support/modernizrrc.js'),
                    },
                ]
            },
        ]
    },
    plugins: [
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: true,
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
            }
        }),
        new webpack.ProvidePlugin({
            'Promise': 'bluebird'
        }),
        new webpack.LoaderOptionsPlugin({
            minimize: true,
            debug: false,
            options: {
                context: __dirname
            }
        })
        // new webpack.DefinePlugin({
        //     'process.env': {
        //         NODE_ENV: JSON.stringify(process.env.NODE_ENV || 'development')
        //     }
        // }),
    ]
};