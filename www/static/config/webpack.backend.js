const baseConfig = require("./webpack.frontend");
const _ = require("lodash");
const path = require("path");

const config = _.merge(baseConfig, {
  entry: "./backend/jsx/main.jsx",
  output: {
    path: path.resolve("./backend/dist/js"),
    filename: "[name].[hash].js",
    clean: true,
  },
  resolve: {
    alias: {
      "@": path.resolve("./backend"),
      "@redux": path.resolve("./backend/jsx/redux"),
      "@s3stores-mail": path.resolve("./backend/jsx/modules/s3stores-mail"),
      "@admin/icons": path.resolve("./backend/jsx/modules/common/components/icons"),
      "@admin/modules": path.resolve("./backend/jsx/modules"),
      "@admin": path.resolve("./backend/jsx"),
    },
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        enforce: "pre",
        use: ["source-map-loader"],
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
                "@babel/transform-runtime",
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
                "@babel/transform-runtime",
              ],
            },
          },
        ],
      },
    ],
  },
});

module.exports = config;
