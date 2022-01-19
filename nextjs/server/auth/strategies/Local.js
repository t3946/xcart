const passwordUtils = require("../../utils/password");
const axios = require("axios");
const LocalStrategy = require("passport-local").Strategy;
const strategyOptions = {
  usernameField: "login",
  passwordField: "password",
  passReqToCallback: true,
};
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

module.exports = function (passport) {
  passport.use(
    new LocalStrategy(strategyOptions, async function (
      req,
      login,
      password,
      done
    ) {
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
        return done({ message: { password: "Wrong password" } }, null);
      }

      delete user.password;

      if (user.tsv_count === 0) {
        done(null, {
          user,
        });

        return;
      }

      //check tsv code
      if (req.body.fingerprint) {
        const fp = await prisma.xcart_fingerprints.findFirst({
          where: {
            user_id: user.user_id,
            fingerprint: req.body.fingerprint,
          },
        });

        //remembered device
        if (fp) {
          done(null, {
            user,
          });

          return;
        }
      }

      if (req.body.code === undefined) {
        return done({ message: "Need OTP" }, null);
      }

      await axios
        .post(process.env.BASE_URL_NGINX + "/api/account/tsv/confirm-code", {
          code: req.body.code,
          userId: user.user_id,
        })
        .then(async (apiRes) => {
          if (!apiRes.data.user) {
            done({ message: { code: "Code is invalid" } }, null);
            return;
          }

          const { rememberBrowser, fingerprint } = req.body;
          const userId = user.user_id;

          if (rememberBrowser && fingerprint) {
            await prisma.xcart_fingerprints.create({
              data: {
                user_id: userId,
                fingerprint: fingerprint,
              },
            });
          }

          done(null, {
            user,
          });
        });
    })
  );
};
