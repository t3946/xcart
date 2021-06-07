const baseConfig = require("./webpack.frontend");
const _ = require("lodash");
const path = require("path");

const config = _.merge(baseConfig, {
  entry: "./backend/jsx/main.jsx",
  output: {
    path: path.resolve("./backend/dist/js"),
    filename: "[name].js",
  },
  resolve: {
    alias: {
      "@": path.resolve("./backend"),
      "@modules": path.resolve("./backend/jsx/modules"),
    },
  },
});

module.exports = config;
