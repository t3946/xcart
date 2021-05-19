/* eslint no-console: 0 */

const gulp = require("gulp");
const hashSum = require("gulp-hashsum");
const concat = require("gulp-concat");
const fs = require("fs");

exports["default"] = {
  /**
   * Redefinition Level(RL) is directory that contain bem-blocks and named as
   * Main.blocks or Common.blocks e.t.c farther will as RL for concise
   *
   * @param order array - RL names f.e. Basic.blocks or Common.blocks should to pass as ['basic', 'common']
   * @param rlDir string - path to redefinition level dirs
   * @param ext - string style files extension
   * @return array paths to RL
   */
  BemOrderBuilder: async function (rlDir, order = [], ext = "css") {
    //trim slash in the end
    rlDir.replace(/[\\/]$/, "");

    let orderedLevels = [];

    return new Promise((resolve, reject) => {
      fs.readdir(rlDir, (err, files) => {
        const otherLevels = [];

        files.forEach((file) => {
          if (file.search(/^.*?\.blocks$/) !== 0) {
            return;
          }

          const levelName = file.split(".")[0];
          let index = order.indexOf(levelName);

          if (index > -1) {
            orderedLevels[index] = `${rlDir}/${file}/**/*.${ext}`;
          } else {
            otherLevels.push(`${rlDir}/${file}/**/*.${ext}`);
          }
        });

        //remove empty values
        orderedLevels = orderedLevels.filter((elem) => elem);
        orderedLevels.push(...otherLevels);

        resolve(orderedLevels);
      });
    });
  },

  isProduction: function () {
    return process.env.NODE_ENV === "production";
  },

  buildJsx: function (src, dst, cmd, done) {
    gulp
      .src(src)
      .pipe(concat("vendors.js"))
      .pipe(
        hashSum({ filename: "frontend/versions/vendor_js.yml", hash: "md5" })
      )
      .pipe(gulp.dest(dst));

    cmd.on("close", function (code) {
      console.log("frontend:jsx exited with code " + code);
      done(code);
    });
  },
};
