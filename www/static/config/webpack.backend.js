const baseConfig = require("./webpack.frontend");
const _ = require("lodash");
const path = require("path");

const config = _.merge(baseConfig, {
  mode: "development",
  entry: "./backend/jsx/main.jsx",
  output: {
    path: path.resolve("./backend/dist/js"),
    filename: "[name].js",
  },
  resolve: {
    alias: {
      "@": path.resolve("./backend"),
    },
  },
});

module.exports = config;
