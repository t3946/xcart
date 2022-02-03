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
  });

  const orderGroups = [];

  for (const order of orders) {
    const groups = await prisma.xcart_order_groups.findMany({
      where: {
        orderid: order.orderid,
      },
    });

    for (const group of groups) {
      const statuses = await prisma.xcart_order_statuses_history.findMany({
        where: {
          group_id: group.order_group_id,
        },
      });

      orderGroups.push({
        orderNumber: order.order_prefix + order.orderid,
        order_group_id: group.order_group_id,
        tracking: group.tracking,
        statuses,
      });
    }
  }

  res.json(orderGroups);
});

module.exports = app;
