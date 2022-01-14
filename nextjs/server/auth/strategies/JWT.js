const JwtStrategy = require("passport-jwt").Strategy;
const ExtractJwt = require("passport-jwt").ExtractJwt;
const authConfig = require("../../config/auth");

function jwtCookieExtractor(req) {
  return req.cookies.session;
}

const strategyOptions = {
  jwtFromRequest: ExtractJwt.fromExtractors([jwtCookieExtractor]),
  secretOrKey: authConfig.jwtSecret,
};

module.exports = function (passport) {
  passport.use(
    new JwtStrategy(strategyOptions, function (jwtPayload, done) {
      const now = new Date().getTime();

      if (now > jwtPayload.timeout) {
        done({ error: { message: "session time out" } }, null);
        return;
      }

      done(null, jwtPayload);
    })
  );
};
