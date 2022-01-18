const app = require("express")();
const passport = require("../auth/Passport");
const generateToken = require("../utils/generateToken");
const isAuthMiddleware = require("../middleware/isAuth");
const setSessionCookie = require("../utils/session").setCookie;
const passwordUtils = require("../utils/password");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const axios = require("axios");
const mail = require("../services/mail");
const AxiosInstance = axios.create({
  baseURL: process.env.BASE_URL_NGINX,
});

app.post("/login", function (req, res) {
  passport.authenticate("local", { session: false }, async (err, result) => {
    if (!result) {
      return res.send({ error: err.message });
    }

    req.login(result.user, { session: false }, async (err) => {
      if (err) {
        return res.send(err);
      }

      const params = {
        userId: result.user.user_id,
        rememberMe: req.body.rememberMe,
      };

      await setSessionCookie(res, params);

      res.json({ user: result.user });
    });
  })(req, res);
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
  let users = await prisma.xcart_users.findMany({
    where: {
      email,
    },
  });

  if (users.length) {
    res.json({ error: { email: "This email already registered" } });
  } else {
    const user = await prisma.xcart_users.create({
      data: {
        email,
        name,
        password: await passwordUtils.encryptPassword(password),
      },
    });

    await setSessionCookie(res, { userId: user.user_id });

    delete user.password;
    res.json({ user });
  }
});

app.post("/send-otp", async function (req, res) {
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

  if (!user) {
    res.json({ error: { login: "User not found" } });
    return;
  }

  await AxiosInstance.post(
    "/api/account/reset-password/send-one-time-password",
    {
      login: req.body.login,
    }
  ).then((phpRes) => {
    res.json(phpRes.data);
  });
});

app.post("/verify-otp", async function (req, res) {
  AxiosInstance.post("/api/account/reset-password/verify-one-time-password", {
    login: req.body.login,
    otp: req.body.otp,
  }).then((apiRes) => {
    res.json(apiRes.data);
  });
});

app.post("/reset-password", async function (req, res) {
  AxiosInstance.post("/api/account/reset-password/reset-password", {
    resetPasswordToken: req.body.resetPasswordToken,
    login: req.body.login,
    password: await passwordUtils.encryptPassword(req.body.password),
  }).then(() => {
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

  res.json({ user });
  res.sendStatus(200);
});

app.post("/change-password", isAuthMiddleware, async function (req, res) {
  const { oldPassword, newPassword } = req.body;
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  const isPasswordsMatch = await passwordUtils.comparePassword(
    oldPassword,
    user.password
  );

  if (!isPasswordsMatch) {
    res.json({ errors: { oldPassword: "Wrong password" } });
    return;
  }

  const hashed = await passwordUtils.encryptPassword(newPassword);

  await prisma.xcart_users.update({
    where: {
      user_id: user.user_id,
    },
    data: {
      password: hashed,
    },
  });

  res.sendStatus(200);
});

app.post("/tsv/confirm-code", isAuthMiddleware, async function (req, res) {
  await AxiosInstance.post("/api/account/tsv/confirm-code", {
    code: req.body.code,
    userId: req.user.userId,
  }).then((apiRes) => {
    res.json(apiRes.data);
    res.send();
  });
});

app.get("/tsv/disable", isAuthMiddleware, async function (req, res) {
  await AxiosInstance.post("/api/account/tsv/disable", {
    userId: req.user.userId,
  }).then((apiRes) => {
    res.json(apiRes.data);
    res.send();
  });
});

app.get("/tsv/get", isAuthMiddleware, async function (req, res) {
  await AxiosInstance.post("/api/account/tsv/get", {
    userId: req.user.userId,
  }).then((apiRes) => {
    res.json(apiRes.data);
    res.send();
  });
});

app.get("/tsv/require-for-all", isAuthMiddleware, async function (req, res) {
  await prisma.xcart_fingerprints.deleteMany({
    where: {
      user_id: req.user.userId,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json({ user });
});

app.post("/send-feedback", isAuthMiddleware, async function (req, res) {
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  const data = {
    from: "vl0809081@gmail.com",
    to: user.email,
    subject: "Feedback from " + user.name,
    text: req.body.message,
    html: `<p>${req.body.message}</p>`,
  };

  mail.sendMail(data, function () {
    res.sendStatus(200);
  });
});

/**
 * /verify-one-time-password
 * /send-one-time-password
 * /reset-password
 * */
module.exports = app;
