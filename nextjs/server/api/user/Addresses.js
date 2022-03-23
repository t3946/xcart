const api = require("express")();
const isAuthMiddleware = require("../../middleware/isAuth");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

async function setAddressAsDefault(userId, addressId) {
  //reset old default
  await prisma.account_addresses.updateMany({
    where: {
      user_id: userId,
    },
    data: {
      is_default: 0,
    },
  });

  //set new default
  await prisma.account_addresses.updateMany({
    where: {
      user_id: userId,
      address_id: addressId,
    },
    data: {
      is_default: 1,
    },
  });
}

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
      state: {
        select: {
          code: true,
          stateid: true,
          state: true,
        },
      },
    },
    orderBy: {
      is_default: "desc",
    },
  });

  res.json({ addresses });
});

api.post("/set-default", isAuthMiddleware, async (req, res) => {
  const address = await prisma.account_addresses.findFirst({
    where: {
      user_id: req.user.userId,
      address_id: req.body.addressId,
    },
  });

  if (!address) {
    res.sendStatus(400);
    return;
  }

  await setAddressAsDefault(req.user.userId, req.body.addressId);

  res.sendStatus(200);
});

api.post("/remove", isAuthMiddleware, async (req, res) => {
  await prisma.account_addresses.deleteMany({
    where: {
      user_id: req.user.userId,
      address_id: req.body.addressId,
    },
  });

  res.sendStatus(200);
});

api.post("/create", isAuthMiddleware, async (req, res) => {
  const address = await prisma.account_addresses.create({
    data: {
      ...req.body.address,
      user_id: req.user.userId,
    },
  });

  if (address.is_default) {
    await setAddressAsDefault(req.user.user_id, address.address_id);
  }

  res.json({ address });
});

api.post("/edit", isAuthMiddleware, async (req, res) => {
  const address = await prisma.account_addresses.updateMany({
    data: {
      ...req.body.address,
      user_id: req.user.userId,
    },
    where: {
      address_id: req.body.address.address_id,
      user_id: req.user.userId,
    },
  });

  if (address.is_default) {
    await setAddressAsDefault(req.user.user_id, address.address_id);
  }

  res.sendStatus(200);
});

module.exports = api;
