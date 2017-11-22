const webpack = require('webpack');
const path = require('path');


module.exports = {
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
            paths.modules.jsx,
            path.resolve('./' + paths.modules.jsx),
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
                            [ "es2016" ],
                            [ "babili" ],
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
        new webpack.ProvidePlugin({
            'Promise': 'bluebird',
            'IntersectionObserver':'intersection-observer',
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