const webpack = require("webpack");
const path = require("path");
const paths = require("./gulp.frontend.patchs");

config = {
  mode: process.env.NODE_ENV || "development",
  entry: paths.src.jsx_bundles,
  output: {
    path: path.resolve("./" + paths.dst.jsx),
    filename: "[name].[hash].js",
    clean: true,
  },
  stats: "errors-only",
  target: "web",
  resolve: {
    alias: {
      modernizr$: path.resolve(__dirname, "./support/modernizrrc.js"),
      jQuery: "jquery",
      react: "preact/compat",
      "react-dom": "preact/compat",
      // Not necessary unless you consume a module using `createClass`
      "create-react-class": "preact/compat/lib/create-react-class",
      "@": path.resolve("./frontend/jsx"),
      "@client/modules": path.resolve("./frontend/jsx/modules"),
      "@client/redux": path.resolve("./frontend/jsx/redux"),
      "@client": path.resolve("./frontend"),
      "@local_modules": path.resolve("./local_modules"),
    },

    modules: [
      "frontend/jsx",
      paths.modules.jsx,
      path.resolve("./" + paths.modules.jsx),
      "node_modules",
    ],

    fallback: {
      http: require.resolve("stream-http"),
      https: require.resolve("https-browserify"),
      crypto: require.resolve("crypto-browserify"),
      stream: require.resolve("stream-browserify"),
    },

    descriptionFiles: ["package.json"],
    extensions: ["*", ".json", ".js", ".jsx", ".tsx", ".ts"],
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        enforce: "pre",
        use: ["source-map-loader"],
      },
      {
        test: /\.css$/,
        use: [{ loader: "style-loader" }, { loader: "css-loader" }],
      },
      {
        test: /\.s[ac]ss$/,
        use: [
          { loader: "style-loader" },
          { loader: "css-loader" },
          { loader: "sass-loader" },
        ],
      },
      {
        test: /\.(png|jpe?g|gif)$/i,
        use: [
          {
            loader: "file-loader",
          },
        ],
      },
      {
        test: /\.svg$/,
        loader: "svg-inline-loader",
      },
      {
        test: /\.(js|jsx)?$/,
        exclude: /(node_modules)/,
        use: [
          {
            loader: "source-map-loader",
          },
          {
            loader: "babel-loader",
            options: {
              comments: false,
              presets: [
                [
                  "@babel/env",
                  {
                    modules: false,
                    loose: true,
                  },
                ],
                "@babel/preset-react",
              ],
              plugins: [
                "@babel/plugin-proposal-object-rest-spread",
                [
                  "transform-react-jsx",
                  {
                    pragma: "h", // default pragma is React.createElement
                  },
                ],
                [
                  "module-resolver",
                  {
                    root: ["."],
                    alias: {
                      react: "preact/compat",
                      "react-dom": "preact/compat",
                      "create-react-class":
                        "preact-compat/lib/create-react-class",
                    },
                  },
                ],
              ],
            },
          },
        ],
      },
      {
        test: /\.(ts|tsx)?$/,
        exclude: /(node_modules)/,
        use: [
          {
            loader: "babel-loader",
            options: {
              comments: false,
              presets: [
                "@babel/preset-env",
                "@babel/preset-typescript",
                "@babel/preset-react",
              ],
              plugins: [
                "@babel/proposal-class-properties",
                "@babel/proposal-object-rest-spread",
              ],
            },
          },
        ],
      },
    ],
  },
  plugins: [
    new webpack.ProvidePlugin({
      $: "jquery",
      jQuery: "jquery",
      React: "react",
      "window.jQuery": "jquery",
      h: ["preact", "h"],
      Component: ["preact", "Component"],
    }),
    new webpack.LoaderOptionsPlugin({
      minimize: true,
      debug: false,
      options: {
        context: __dirname,
      },
    }),
    new webpack.DefinePlugin({
      "process.env": {
        NODE_ENV: JSON.stringify(process.env.NODE_ENV || "development"),
      },
    }),
  ],
  watchOptions: {
    aggregateTimeout: 300,
    poll: 1000,
  },
};

module.exports = config;
