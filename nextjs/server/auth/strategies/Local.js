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

      const maxWrongPasswordAttempts = 3;

      if (user.wrong_password_attempts >= maxWrongPasswordAttempts) {
        if (req.body.captcha) {
          let recaptchaPassed;

          await axios
            .post("https://www.google.com/recaptcha/api/siteverify", null,{
              params: {
                secret: "6Le71nweAAAAADfhvwQeioTAIaHxI5Y9v2q8pWlz",
                response: req.body.captcha,
              },
            })
            .then((resApi) => {
              recaptchaPassed = resApi.data.success;
            });

          if (!recaptchaPassed) {
            return done({ message: { password: "Captcha is invalid" } }, null);
          }
        } else {
          return done({ message: { password: "Captcha required" } }, null);
        }
      }

      if (!isPasswordsMatch) {
        await prisma.xcart_users.update({
          where: {
            user_id: user.user_id,
          },
          data: {
            wrong_password_attempts: user.wrong_password_attempts + 1,
          },
        });

        return done({ message: { password: "Wrong password" } }, null);
      }

      //reset wrong_password_attempts
      await prisma.xcart_users.update({
        where: {
          user_id: user.user_id,
        },
        data: {
          wrong_password_attempts: 0,
        },
      });

      delete user.password;

      const authenticators = await prisma.xcart_authenticators.findMany({
        where: {
          user_id: user.user_id,
        },
      });

      if (authenticators.length === 0) {
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
        .post(process.env.BASE_URL_NGINX + "/api/account/tsv/check-code", {
          code: req.body.code,
          userId: user.user_id,
        })
        .then(async (apiRes) => {
          if (apiRes.data.checkResult === false) {
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
