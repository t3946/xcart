const app = require("express")();
const isAuthMiddleware = require("../middleware/isAuth");
const axios = require("axios");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

app.get("/get/:type/:date", isAuthMiddleware, async (req, res) => {
  const { type, date } = req.params;
  const userId = req.user.userId;
  const orders = await axios
    .get(
      process.env.BASE_URL_NGINX +
        `/api/account/orders/get/${userId}/${type}/${date}`
    )
    .then((response) => response.data);
  res.json(orders);
});

app.get("/get-order-groups", isAuthMiddleware, async (req, res) => {
  const orders = await prisma.xcart_orders.findMany({
    where: {
      user_id: req.user.userId,
    },
    select: {
      order_prefix: true,
      orderid: true,
      groups: {
        where: {
          cb_status: {
            in: ["AP", "P", "Q"],
          },
        },
        select: {
          order_group_id: true,
          statuses_history: true,
          trackings: {
            select: {
              id: true,
              tracknum: true,
              carrier: {
                select: {
                  link: true,
                },
              },
            },
          },
        },
      },
    },
  });

  res.json(orders);
});

module.exports = app;
