const app = require("express")();
const passport = require("../auth/Passport");
const generateToken = require("../utils/generateToken");
const isAuthMiddleware = require("../middleware/isAuth");
const setSessionCookie = require("../utils/session").setCookie;
const passwordUtils = require("../utils/password");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

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

app.post("/reset-password", isAuthMiddleware, (req, res) => {});

app.post("/register");

app.post("/change-password", isAuthMiddleware, async (req, res) => {
  const hashed = await passwordUtils.encryptPassword(req.body.password);

  await prisma.user.update({
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
  const user = await prisma.user.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json(user);
});

app.get("/logout", isAuthMiddleware, async function (req, res) {
  await prisma.user.update({
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
  const user = await prisma.user.findFirst({
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
  let user = await prisma.user.findUnique({
    where: {
      email,
    },
  });

  if (user) {
    res.json({ error: { email: "This email already registered" } });
  } else {
    await prisma.user.create({
      data: {
        email,
        name,
        password: await passwordUtils.encryptPassword(password),
      },
    });

    user = await prisma.user.findUnique({
      where: {
        email,
      },
    });

    await setSessionCookie(res, result.user.user_id);

    res.json(user);
  }
});

module.exports = app;
