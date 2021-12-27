const app = require("express")();
const passport = require("../auth/Passport");
const jwt = require("jsonwebtoken");
const generateToken = require("../utils/generateToken");
const isAuthMiddleware = require("../middleware/isAuth");
const authConfig = require("../config/auth");
const passwordUtils = require("../utils/password");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

app.post("/login", function (req, res) {
  passport.authenticate("local", { session: false }, async (err, result) => {
    if (!result) {
      return res.send({ error: err.message });
    }

    req.login(result.user, { session: false }, (err) => {
      if (err) {
        return res.send(err);
      }

      const token = jwt.sign(result.payload, authConfig.jwtSecret);
      const timeDayMS = 1000 * 60 * 60 * 24;

      res.status(200);
      res.cookie("session", `${token}`, {
        maxAge: timeDayMS * 60,
      });

      res.send({ user: result.user });
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

  res.clearCookie("session", "");
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

module.exports = app;
