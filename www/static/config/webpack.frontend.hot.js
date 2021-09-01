const webpack = require('webpack');
const config = require("./webpack.frontend");
const paths = require('./gulp.frontend.patchs');

config.devServer = {
        contentBase: path.resolve('./' + paths.dst.jsx),
        hot: true
};


config.plugins.push(
    new webpack.NamedModulesPlugin(),
    new webpack.HotModuleReplacementPlugin()
);