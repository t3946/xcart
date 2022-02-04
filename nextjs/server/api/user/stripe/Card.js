const api = require("express")();
const stripeService = require("../../../services/stripe");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

api.get("/get", async function (req, res) {
  const sources = await stripeService.getSources(req.user.userId);

  for (const i in sources.data) {
    if (!sources.data[i].metadata.addressId) {
      continue;
    }

    sources.data[i].metadata.address =
      await prisma.account_addresses.findUnique({
        where: {
          address_id: parseInt(sources.data[i].metadata.addressId),
        },
      });
  }

  res.json(sources);
});

api.post("/create", async function (req, res) {
  const metadata = {};

  if (req.body.addressId) {
    metadata.addressId = req.body.addressId;
  }

  const source = await stripeService.createSources(
    req.user.userId,
    req.body.token,
    metadata
  );

  res.json(source);
});

api.post("/delete", async function (req, res) {
  const source = await stripeService.source.delete(
    req.user.userId,
    req.body.cardId
  );

  res.json(source);
});

module.exports = api;
