const axios = require("axios");
const app = require("express")();
const AxiosInstance = axios.create();
const getBaseUrl = require("../utils/getBaseUrl");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const { normalize } = require("../utils/product");

app.post("/get", async function (req, res) {
  await AxiosInstance.post(
    getBaseUrl(req) + `/api/goods/get/${req.body.productId}/`
  ).then((apiRes) => {
    res.json(apiRes.data);
    res.send();
  });
});

app.post("/get-node", async function (req, res) {
  const product = await prisma.xcart_products.findFirst({
    where: {
      productid: req.body.productid,
    },
    select: {
      productid: true,
      new_map_price: true,
      pricings: true,
      is_group_root: true,
    },
  });

  await normalize(product);

  res.json({ product });
});

module.exports = app;
