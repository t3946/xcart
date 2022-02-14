const api = require("express")();
const isAuthMiddleware = require("../../middleware/isAuth");
const axios = require("axios");
const AxiosInstance = axios.create({
  baseURL: process.env.BASE_URL_NGINX,
});
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

api.post("/confirm-device", isAuthMiddleware, async (req, res) => {
  await AxiosInstance.post("/api/account/tsv/check-code", {
    code: req.body.code,
    secret: req.body.secret,
  }).then(async (innerRes) => {
    const { checkResult } = innerRes.data;

    if (innerRes.data.checkResult === false) {
      res.json({ checkResult });
      return;
    }

    await prisma.xcart_authenticators.create({
      data: { secret: req.body.secret, user_id: req.user.userId },
    });

    const user = await prisma.xcart_users.findUnique({
      where: { user_id: req.user.userId },
    });

    delete user.password;

    res.json({ checkResult, user });
  });
});

api.get("/generate", isAuthMiddleware, async function (req, res) {
  const user = await prisma.xcart_users.findUnique({
    where: { user_id: req.user.userId },
  });

  await AxiosInstance.post("/api/account/tsv/generate", {
    accountName: user.email,
  }).then((apiRes) => {
    res.json(apiRes.data);
    res.send();
  });
});

api.get("/disable", isAuthMiddleware, async function (req, res) {
  await prisma.xcart_authenticators.deleteMany({
    where: {
      user_id: req.user.userId,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: { user_id: req.user.userId },
  });

  res.json({ user });
});

api.get("/require-for-all", isAuthMiddleware, async function (req, res) {
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

module.exports = api;
