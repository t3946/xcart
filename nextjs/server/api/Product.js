const app = require("express")();
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const { normalize } = require("../utils/product");

app.post("/get", async function (req, res) {
  const product = await prisma.xcart_products.findUnique({
    where: { productid: req.body.productId },
  });

  await normalize(req.storefront, product);
  res.json({ product });
});

module.exports = app;
