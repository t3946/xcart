const gulp = require("gulp");
const sass = require('gulp-sass')(require('sass'));
const frontend = require("../../../config/gulp.frontend");
const inlineImage = require("gulp-inline-image");
const { default: GulpAssets } = require("../../../GulpAssets");
const concat = require("gulp-concat");
const hashSum = require("gulp-hashsum");
const cssnano = require("gulp-cssnano");

/**
 * build bem.scss bundle and save it
 */
gulp.task("client:bem:scss", async function (done) {
  const bemLevelsOrder = ["common", "form", "checkout"];
  const bemOrderedPaths = await GulpAssets.BemOrderBuilder(
    "frontend/bem/blocks",
    bemLevelsOrder,
    "scss"
  );

  gulp
    .src(bemOrderedPaths)
    .pipe(concat("bem.scss"))
    .pipe(gulp.dest("frontend/bem/dist/"));

  done();
});

/**
 * Компилирует bem.scss отдельно от остального кода,
 */
gulp.task("client:bem:css", async function (done) {
  gulp
    .src("./frontend/bem/dev/includes.scss")
    .pipe(
      sass({
        includePaths: frontend.src.scss_include || [],
      }).on("error", sass.logError)
    )
    .pipe(inlineImage())
    .pipe(concat("bem.css"))
    .pipe(hashSum({ filename: "frontend/versions/bem.yml", hash: "md5" }))
    .pipe(cssnano(frontend.config.cssnano))
    .pipe(gulp.dest("frontend/dist/css"));

  done();
});

gulp.task(
  "watch:client:bem:css",
  gulp.series("client:bem:css", () => {
    gulp.watch(
      "frontend/bem/blocks/**/*.scss",
      gulp.parallel("client:bem:scss")
    );

    gulp.watch("frontend/bem/dist/bem.scss", gulp.parallel("client:bem:css"));
  })
);
