const app = require("express")();
const passport = require("../auth/Passport");
const generateToken = require("../utils/generateToken");
const isAuthMiddleware = require("../middleware/isAuth");
const setSessionCookie = require("../utils/session").setCookie;
const passwordUtils = require("../utils/password");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const axios = require("axios");

app.post("/login", function (req, res) {
  passport.authenticate("local", { session: false }, async (err, result) => {
    if (!result) {
      return res.send({ error: err.message });
    }

    req.login(result.user, { session: false }, async (err) => {
      if (err) {
        return res.send(err);
      }

      await setSessionCookie(res, result.user.user_id);

      res.json({ user: result.user });
    });
  })(req, res);
});

app.post("/register");

app.post("/change-password", isAuthMiddleware, async (req, res) => {
  const hashed = await passwordUtils.encryptPassword(req.body.password);

  await prisma.xcart_users.update({
    where: {
      user_id: req.user.userId,
    },
    data: {
      password: hashed,
      access_token: generateToken(),
    },
  });

  res.sendStatus(200);
});

app.get("/info", isAuthMiddleware, async (req, res) => {
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json(user);
});

app.get("/logout", isAuthMiddleware, async function (req, res) {
  await prisma.xcart_users.update({
    where: {
      user_id: req.user.userId,
    },
    data: {
      access_token: generateToken(),
    },
  });

  res.clearCookie("session");
  res.sendStatus(200);
});

app.post("/check-login", async function (req, res) {
  const user = await prisma.xcart_users.findFirst({
    where: {
      OR: [
        {
          email: req.body.login,
        },
        {
          phone: req.body.login,
        },
      ],
    },
  });

  if (user) {
    res.send();
  } else {
    res.json({ error: "User not found", user: req.body });
  }
});

app.post("/create", async function (req, res) {
  const { email, name, password } = req.body;
  let user = await prisma.xcart_users.findUnique({
    where: {
      email,
    },
  });

  if (user) {
    res.json({ error: { email: "This email already registered" } });
  } else {
    await prisma.xcart_users.create({
      data: {
        email,
        name,
        password: await passwordUtils.encryptPassword(password),
      },
    });

    user = await prisma.xcart_users.findUnique({
      where: {
        email,
      },
    });

    await setSessionCookie(res, result.user.user_id);

    res.json(user);
  }
});

app.post("/send-otp", async function (req, res) {
  await axios
    .post("http://nginx/api/account/reset-password/send-one-time-password", {
      login: req.body.login,
    })
    .then((phpRes) => {
      res.json(phpRes.data);
    });
});

app.post("/verify-otp", async function (req, res) {
  axios
    .post("http://nginx/api/account/reset-password/verify-one-time-password", {
      login: req.body.login,
      otp: req.body.otp,
    })
    .then((apiRes) => {
      res.json(apiRes.data);
    });
});

app.post("/reset-password", async function (req, res) {
  axios
    .post("http://nginx/api/account/reset-password/reset-password", {
      resetPasswordToken: req.body.resetPasswordToken,
      login: req.body.login,
      password: await passwordUtils.encryptPassword(req.body.password),
    })
    .then(() => {
      res.sendStatus(200);
    });
});

app.post("/change-name", isAuthMiddleware, async function (req, res) {
  await prisma.xcart_users.update({
    where: {
      user_id: req.user.userId,
    },
    data: {
      name: req.body.name,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json({ user });

  res.clearCookie("session");
  res.sendStatus(200);
});

app.post("/change-email", isAuthMiddleware, async function (req, res) {
  await prisma.xcart_users.update({
    where: {
      user_id: req.user.userId,
    },
    data: {
      email: req.body.email,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json({ user });

  res.clearCookie("session");
  res.sendStatus(200);
});

app.post("/change-phone", isAuthMiddleware, async function (req, res) {
  //check phone
  const phone = req.body.phone;
  const countryCode = parseInt(phone.slice(1, -10));

  const country = await prisma.xcart_countries.findUnique({
    where: {
      phone_code: countryCode,
    },
  });

  await prisma.xcart_users.update({
    where: {
      user_id: req.user.userId,
    },
    data: {
      phone,
      phone_country_code: country.code,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json({user});
  res.clearCookie("session");
  res.sendStatus(200);
});

/**
 * /verify-one-time-password
 * /send-one-time-password
 * /reset-password
 * */
module.exports = app;
