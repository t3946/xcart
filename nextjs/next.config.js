const _merge = require("lodash/merge");
const path = require("path");
const withBundleAnalyzer = require("@next/bundle-analyzer")({
  enabled: process.env.ANALYZE === "true",
});
const colors = require("colors");
const withImages = require("next-images");

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

module.exports = withImages(
  withBundleAnalyzer({
    webpack: (config, { buildId, dev, isServer, defaultLoaders, webpack }) => {
      const extendConfig = {
        resolve: {
          alias: {
            "@modules": path.resolve("modules/"),
            "@pages": path.resolve("pages/"),
            "@redux": path.resolve("redux/"),
            "@utils": path.resolve("utils/"),
            "@services": path.resolve("services/"),
            "@styles": path.resolve("styles/"),
            "@submodules": path.resolve("submodules/"),
          },
        },
      };

      return _merge(config, extendConfig);
    },
    basePath: "/account",
    typescript: {
      // !! WARN !!
      // Dangerously allow production builds to successfully complete even if
      // your project has type errors.
      // !! WARN !!
      ignoreBuildErrors: true,
    },
  })
);
