const passwordUtils = require("../../utils/password");
const LocalStrategy = require("passport-local").Strategy;
const strategyOptions = {
  usernameField: "login",
  passwordField: "password",
};
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

module.exports = function (passport) {
  passport.use(
    new LocalStrategy(strategyOptions, async function (login, password, done) {
      const user = await prisma.xcart_users.findFirst({
        where: {
          OR: [
            {
              email: login,
            },
            {
              phone: login,
            },
          ],
        },
      });

      if (!user) {
        return done({ message: "User not found" }, null);
      }

      const isPasswordsMatch = await passwordUtils.comparePassword(
        password,
        user.password
      );

      if (!isPasswordsMatch) {
        return done({ message: "Wrong password" }, null);
      }

      delete user.password;

      done(null, {
        user,
      });
    })
  );
};
