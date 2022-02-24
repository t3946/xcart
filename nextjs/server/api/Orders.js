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
      cb_status: {
        in: ["F", "P", "Z", "AP", "P", "Q", "F", "A", "D"],
      },
    },
    select: {
      order_prefix: true,
      orderid: true,
      xcart_order_groups: {
        where: {
          cb_status: {
            in: ["AP", "P", "Q"],
          },
        },
        select: {
          order_group_id: true,
          xcart_order_statuses_history: true,
          xcart_order_tracking: {
            select: {
              id: true,
              tracknum: true,
              xcart_tracking_links_carrier: {
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

  // rename some fields
  for (const i in orders) {
    orders[i].groups = orders[i].xcart_order_groups;
    delete orders[i].xcart_order_groups;
    const groups = orders[i].groups;

    for (const group of groups) {
      group.statuses_history = group.xcart_order_statuses_history;
      delete group.xcart_order_statuses_history;
      group.trackings = group.xcart_order_tracking;
      delete group.xcart_order_tracking;

      for (const tracking of group.trackings) {
        tracking.carrier = tracking.xcart_tracking_links_carrier;
        delete tracking.xcart_tracking_links_carrier;
      }
    }
  }

  res.json(orders);
});

module.exports = app;
