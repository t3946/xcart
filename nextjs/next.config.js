const _merge = require("lodash/merge");
const path = require("path");
const withBundleAnalyzer = require("@next/bundle-analyzer")({
  enabled: process.env.ANALYZE === "true",
});
const colors = require("colors");

//print essentials info
console.log();
console.log("ENV VARS:");
console.log("NODE_ENV=" + colors.cyan(colors.bold(process.env.NODE_ENV)));
console.log(
  "ANALYZE=" +
    (process.env.ANALYZE === "true"
      ? colors.green("enabled")
      : colors.red("disabled"))
);
console.log();

module.exports = withBundleAnalyzer({
  webpack: (config, { buildId, dev, isServer, defaultLoaders, webpack }) => {
    const extendConfig = {
      resolve: {
        alias: {
          "@modules": path.resolve("modules/"),
          "@pages": path.resolve("pages/"),
          "@redux": path.resolve("redux/"),
          "@utils": path.resolve("utils/"),
          "@services": path.resolve("services/"),
          styles: path.resolve("styles/"),
        },
      },
    };

    return _merge(config, extendConfig);
  },
  basePath: "/account",
});
