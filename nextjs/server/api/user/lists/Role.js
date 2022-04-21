const app = require("express")();
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const getListsById = require("./utils/getListsById");

app.post("/delete", async (req, res) => {
  const { product_list_id, user_id } = req.body;
  const list = await getListsById(prisma, product_list_id);

  if (
    parseInt(list.owner.user_id) === req.user.userId ||
    user_id === req.user.userId
  ) {
    await prisma.product_list_user_roles.deleteMany({
      where: {
        product_list_id,
        user_id,
      },
    });

    res.sendStatus(200);
    return;
  }

  res.sendStatus(403);
});

app.post("/update", async (req, res) => {
  const { user_id, product_list_id, role } = req.body;
  const list = await getListsById(prisma, product_list_id);

  // can't edit list
  if (parseInt(list.user_id) !== req.user.userId) {
    res.sendStatus(403);
    return;
  }

  await prisma.product_list_user_roles.updateMany({
    where: {
      user_id,
      product_list_id,
    },
    data: {
      role,
    },
  });

  res.sendStatus(200);
});

module.exports = app;
