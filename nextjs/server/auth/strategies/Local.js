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
      let user = await prisma.xcart_users.findFirst({
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
            .post("https://www.google.com/recaptcha/api/siteverify", null, {
              params: {
                secret: "6LenP30eAAAAAOo7P0vvQSGN-6aosCRuhUg2w5YR",
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

      // no tsv methods
      if (
        user.tsv_preferred_method === "na" ||
        (authenticators.length === 0 && !user.phone)
      ) {
        done(null, {
          user,
        });

        return;
      }

      //tsv

      //auth by fingerprint
      if (req.body.fingerprint) {
        console.log("fp");
        const fp = await prisma.xcart_fingerprints.findFirst({
          where: {
            user_id: user.user_id,
            fingerprint: req.body.fingerprint,
          },
        });

        //known device
        if (fp) {
          console.log("known device");
          done(null, {
            user,
          });

          return;
        }
      }

      //auth by secret code
      if (req.body.code === undefined) {
        return done({ message: "Need OTP" }, null);
      }

      console.log("user.tsv_preferred_method", user.tsv_preferred_method);

      //check code from app
      switch (user.tsv_preferred_method) {
        case "authenticator_app":
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

              if (rememberBrowser && fingerprint) {
                await prisma.xcart_fingerprints.create({
                  data: {
                    user_id: user.user_id,
                    fingerprint: fingerprint,
                  },
                });
              }

              //update user data
              user = await prisma.xcart_users.findFirst({
                where: {
                  user_id: user.user_id,
                },
              });

              delete user.password;

              done(null, {
                user,
              });
            });
          break;

        case "phone_number":
          const otp = await prisma.xcart_one_time_passwords.findFirst({
            where: {
              user_id: user.user_id,
              label: "login-by-sms",
            },
          });

          if (new Date().getTime() > parseInt(otp.expired)) {
            done({ message: { code: "Code was deprecated" } }, null);
            return;
          }

          //wrong code
          if (req.body.code !== otp.one_time_password) {
            done({ message: { code: "Code is invalid" } }, null);
            return;
          }

          //correct code

          //set otp confirmed
          await prisma.xcart_one_time_passwords.update({
            where: {
              one_time_password_id: otp.one_time_password_id,
            },
            data: {
              confirmed: true,
            },
          });

          //remember browser
          const { rememberBrowser, fingerprint } = req.body;

          if (rememberBrowser && fingerprint) {
            await prisma.xcart_fingerprints.create({
              data: {
                user_id: user.user_id,
                fingerprint: fingerprint,
              },
            });
          }

          //update user data
          user = await prisma.xcart_users.findFirst({
            where: {
              user_id: user.user_id,
            },
          });

          delete user.password;

          done(null, {
            user,
          });

          break;
      }
    })
  );
};
