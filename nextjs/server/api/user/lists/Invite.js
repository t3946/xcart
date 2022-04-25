const app = require("express")();
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const { encrypt, decrypt } = require("../../../utils/aes256ctrEncrypt");
const getListsById = require("./utils/getListsById");

app.post("/generate", async (req, res) => {
  const { product_list_id, role } = req.body;

  if (["editor", "viewer"].indexOf(role) === -1) {
    res.sendStatus(400);
    return;
  }

  const list = await getListsById(prisma, product_list_id, req.storefront);

  if (!list) {
    res.sendStatus(400);
    return;
  }

  if (parseInt(list.owner.user_id) !== req.user.userId) {
    res.sendStatus(403);
    return;
  }

  const data = [product_list_id, role].join(",");
  const { iv, content } = encrypt(data);

  res.json({ iv, content });
});

app.get("/info/:iv/:content", async (req, res) => {
  const [productListIdStr, role] = decrypt(req.params).split(",");
  const product_list_id = parseInt(productListIdStr);
  let list = await getListsById(prisma, product_list_id, req.storefront);

  if (!list) {
    res.sendStatus(400);
    return;
  }

  //user can't invite yourself
  if (parseInt(list.owner.user_id) === req.user.userId) {
    res.sendStatus(403);
    return;
  }

  res.json({
    list,
    role,
  });
});

app.get("/use/:iv/:content", async (req, res) => {
  const [productListIdStr, roleStr] = decrypt(req.params).split(",");
  const product_list_id = parseInt(productListIdStr);
  let list = await getListsById(prisma, product_list_id, req.storefront);

  if (!list) {
    res.sendStatus(400);
    return;
  }

  //user can't invite yourself
  if (parseInt(list.owner.user_id) === req.user.userId) {
    res.sendStatus(403);
    return;
  }

  const role = await prisma.product_list_user_roles.findFirst({
    where: {
      product_list_id: list.product_list_id,
      user_id: req.user.userId,
    },
  });

  //user already invited with this role
  if (role) {
    res.sendStatus(403);
    return;
  }

  await prisma.product_list_user_roles.create({
    data: {
      role: roleStr,
      product_list_id: list.product_list_id,
      user_id: req.user.userId,
    },
  });

  list = await getListsById(prisma, product_list_id, req.storefront);

  res.json({
    list,
  });
});

module.exports = app;
