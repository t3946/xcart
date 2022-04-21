const BearerStrategy = require("passport-http-bearer").Strategy;

module.exports = function (passport) {
  passport.use(
    new BearerStrategy(function (token, done) {
      if (token === "mF_9.B5f-4.1JqM2") {
        done(null, {});
      } else {
        done(null, false);
      }
    })
  );
};
