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
  passport.authenticate("local", { session: false }, (err, user) => {
    if (!user) {
      return res.sendStatus(401);
    }

    req.login(user, { session: false }, (err) => {
      if (err) {
        return res.send(err);
      }

      const token = jwt.sign(user, authConfig.jwtSecret);

      res.status(200);
      res.header("Authorization", `bearer ${token}`);
      res.cookie("session", `${token}`);
      res.send();
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

  res.header("Authorization", "");
  res.cookie("Authorization", "");
  res.status(200);
  res.send();
});

app.get("/check-login", async function (req, res) {
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

  res.status(user ? 200 : 404);
  res.send();
});

module.exports = app;
