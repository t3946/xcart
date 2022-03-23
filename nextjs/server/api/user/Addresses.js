const api = require("express")();
const isAuthMiddleware = require("../../middleware/isAuth");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

api.get("/get", isAuthMiddleware, async (req, res) => {
  const addresses = await prisma.account_addresses.findMany({
    where: {
      user_id: req.user.userId,
    },
    include: {
      country: {
        select: {
          code: true,
          country_id: true,
          name: true,
        },
      },
    },
  });

  res.json({ addresses });
});

module.exports = api;
