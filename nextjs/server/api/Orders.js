const app = require("express")();
const isAuthMiddleware = require("../middleware/isAuth");
const axios = require("axios");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const getBaseUrl = require("../utils/getBaseUrl");

app.get("/get/:type/:date", isAuthMiddleware, async (req, res) => {
  const { type, date } = req.params;
  const userId = req.user.userId;
  const orders = await axios
    .get(getBaseUrl(req) + `/api/account/orders/get/${userId}/${type}/${date}`)
    .then((response) => response.data);
  res.json(orders);
});

app.get("/get-order-groups", isAuthMiddleware, async (req, res) => {
  const orders = await prisma.xcart_orders.findMany({
    where: {
      user_id: req.user.userId,
      cb_status: {
        in: ["P", "Z", "AP", "P", "Q", "IO", "O"],
      },
      NOT: {
        AND: [
          {
            cb_status: "P",
          },
          {
            dc_status: "Z",
          },
        ],
      },
    },
    select: {
      order_prefix: true,
      orderid: true,
      groups: {
        where: {
          cb_status: {
            in: ["AP", "P", "Q", "IO", "O"],
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

app.post("/get", isAuthMiddleware, async (req, res) => {
  const order = await prisma.xcart_orders.findFirst({
    where: {
      user_id: req.user.userId,
      orderid: req.body.orderId,
    },
    include: {
      decisions: true,
      groups: {
        select: {
          order_group_id: true,
          total_gross: true,
          xcart_order_statuses_history: true,
          cb_status_rel: {
            select: {
              xcart_order_human_readable_statuses: {
                select: {
                  name: true,
                },
              },
            },
          },
          dc_status_rel: {
            select: {
              xcart_order_human_readable_statuses: {
                select: {
                  name: true,
                },
              },
            },
          },
          manufacturer: {
            select: {
              m_city: true,
              m_country: true,
              m_state: true,
            },
          },
          details: {
            select: {
              price: true,
              product: true,
              amount: true,
              xcart_products: {
                select: {
                  productid: true,
                  productcode: true,
                  images: {
                    orderBy: {
                      order_by: "asc",
                    },
                    take: 1,
                    where: {
                      is_active: 1,
                    },
                    select: {
                      image: {
                        select: {
                          path: true,
                        },
                      },
                    },
                  },
                },
              },
            },
          },
          xcart_order_group_taxes: {
            select: {
              value: true,
              xcart_tax_rates: {
                select: {
                  xcart_taxes: {
                    select: {
                      tax_name: true,
                    },
                  },
                },
              },
            },
          },
        },
      },
    },
  });

  res.json({ order });
});

module.exports = app;
