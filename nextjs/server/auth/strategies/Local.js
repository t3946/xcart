const passwordUtils = require("../../utils/password");
const LocalStrategy = require("passport-local").Strategy;
const strategyOptions = {
  usernameField: "email",
  passwordField: "password",
};
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

module.exports = function (passport) {
  passport.use(
    new LocalStrategy(strategyOptions, async function (email, password, done) {
      const user = await prisma.user.findUnique({
        where: {
          email,
        },
      });

      if (!user) {
        return done(null, null);
      }

      const isPasswordsMatch = await passwordUtils.comparePassword(
        password,
        user.password
      );

      if (!isPasswordsMatch) {
        return done(null, null);
      }

      if (user) {
        done(null, {
          userId: user.user_id,
          createdTime: new Date().getTime(),
          accessToken: user.access_token,
        });
      }
    })
  );
};
