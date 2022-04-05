const express = require("express");
const isAuthMiddleware = require("../../middleware/isAuth");
const stripeService = require("../../services/stripe");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const app = express();
const getBaseUrl = require("../../utils/getBaseUrl");
const md5 = require("md5");

function getPaypalUrl(req, order) {
  const [firstName, lastName] = order.b_firstname.split(" ");
  const orderHash = md5([order.orderid, order.s_zipcode, order.email].join(""));
  const returnUrl =
    getBaseUrl(req) + `/payment/return/paypal/${order.orderid}/${orderHash}`;
  const cancel_return = getBaseUrl(req) + `/payment/cancel/paypal/`;
  const params = {
    cmd: "_ext-enter",
    redirect_cmd: "_xclick",
    mrb: "R-2JR83330TB370181P",
    pal: "RDGQCFJTT6Y6A",
    rm: "2",
    custom: order.orderid,
    business: "paypal@s3stores.com",
    email: order.email,
    first_name: firstName,
    last_name: lastName,
    day_phone_a: order.phone.substr(0, 3),
    day_phone_b: order.phone.substr(3, 3),
    day_phone_c: order.phone.substr(6, 4),
    night_phone_a: order.phone.substr(0, 3),
    night_phone_b: order.phone.substr(3, 3),
    night_phone_c: order.phone.substr(6, 4),
    item_name: `S3 Stores, Inc. Order # ${order.order_prefix}${order.orderid}`,
    amount: parseFloat(order.total),
    currency_code: order.currency,
    bn: "x-cart",
    paymentaction: "authorization",
    address1: order.b_address,
    country: order.b_country,
    state: order.b_state,
    city: order.b_city,
    zip: order.b_zipcode,
    return: returnUrl,
    cancel_return: cancel_return,
  };
  const baseUrl = "https://www.paypal.com/cgi-bin/webscr";
  const urlParamsList = [];

  for (const key in params) {
    const value = params[key];

    urlParamsList.push(`${key}=${value}`);
  }

  const urlParams = urlParamsList.join("&");

  return `${baseUrl}?${urlParams}`;
}

//get current user decisions
app.get("/get-initial-state", isAuthMiddleware, async function (req, res) {
  const { skip, take } = req.body;
  const queryOptions = {
    where: {
      order: {
        user_id: req.user.userId,
      },
    },
  };

  queryOptions.where.solved = 1;
  const solvedItems = await prisma.account_decisions.findMany({
    skip,
    take,
    ...queryOptions,
    include: {
      type: true,
    },
  });
  const solvedTotal = await prisma.account_decisions.count(queryOptions);

  queryOptions.where.solved = 0;
  const notSolvedItems = await prisma.account_decisions.findMany({
    skip,
    take,
    ...queryOptions,
    include: {
      type: true,
    },
  });
  const notSolvedTotal = await prisma.account_decisions.count(queryOptions);

  const decisions = {
    solved: {
      items: solvedItems,
      total: solvedTotal,
    },
    notSolved: {
      items: notSolvedItems,
      total: notSolvedTotal,
    },
  };

  res.send(decisions);
});

app.post("/get", isAuthMiddleware, async function (req, res) {
  const { decisionId } = req.body;
  const decision = await prisma.account_decisions.findUnique({
    where: {
      decision_id: decisionId,
    },
    include: {
      type: true,
      order: {
        select: {
          alt_items: true,
          cb_status: true,
          dc_status: true,
          subtotal: true,
          total: true,
          shipping_cost: true,
          groups: {
            select: {
              order_group_id: true,
              total_gross: true,
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
                  items_stock: true,
                  price: true,
                  product: true,
                  amount: true,
                  xcart_products: {
                    select: {
                      productid: true,
                      productcode: true,
                      eta_date_mm_dd_yyyy: true,
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
      },
      files: {
        select: {
          file: {
            select: {
              path: true,
              original_name: true,
            },
          },
        },
      },
    },
  });
  const altItemsSku = decision.order.alt_items.split(",");

  decision.order.alt_items = await prisma.xcart_products.findMany({
    where: {
      productcode: {
        in: altItemsSku,
      },
    },
    select: {
      product: true,
      productcode: true,
    },
  });

  const resBody = { decision };
  const order = await prisma.xcart_orders.findFirst({
    where: {
      orderid: decision.order.orderId,
    },
  });

  if (decision.type.slug === "unpaid-order") {
    resBody.paypalUrl = getPaypalUrl(req, order);
  }

  res.json(resBody);
});

app.post("/create", isAuthMiddleware, async function (req, res) {
  const { type, order_id } = req.body;
  const order = await prisma.xcart_orders.findFirst({
    where: {
      orderid: order_id,
    },
  });
  const decisionType = await prisma.decision_types.findFirst({
    where: {
      slug: type,
    },
  });

  const options = req.body.options || {};

  switch (type) {
    case "po-send-check":
      const addresses = await prisma.account_addresses.findMany({
        where: {
          address_id: {
            in: req.body.addresses,
          },
        },
        include: {
          country: true,
          state: true,
        },
      });

      options.addresses = addresses;
      break;
  }

  const decision = await prisma.account_decisions.create({
    data: {
      decision_type_id: decisionType.decision_type_id,
      order_id,
      order_number: [order.order_prefix, order.orderid].join(""),
      options,
    },
  });

  res.json({ decision });
});

app.post("/get-list", isAuthMiddleware, async function (req, res) {
  const { skip, take, solved } = req.body;

  const decisions = await prisma.account_decisions.findMany({
    skip,
    take,
    where: {
      solved,
      order: {
        user_id: req.user.userId,
      },
    },
    include: {
      type: true,
    },
  });

  res.send(decisions);
});

app.post("/set-all-not-solved", isAuthMiddleware, async function (req, res) {
  await prisma.account_decisions.updateMany({
    where: {
      order_id: req.body.order_id,
    },
    data: {
      solved: 0,
    },
  });

  res.sendStatus(200);
});

app.post("/solve", isAuthMiddleware, async function (req, res) {
  const decision = await prisma.account_decisions.findUnique({
    where: {
      decision_id: req.body.decision_id,
    },
    include: {
      type: true,
      order: true,
    },
  });

  switch (decision.type.slug) {
    case "po-send-check":
      decision.options.address = req.body.address;
      break;

    case "alternative-items-offer":
    case "estimated-time-arrival":
      decision.options.advice = req.body.advice;
      decision.options.comment = req.body.comment;
      break;

    case "ach-payment-required":
      break;

    case "increase-shipping-charge":
      decision.options.choice = req.body.choice;
      break;

    case "street-address-required":
      const address = await prisma.account_addresses.findUnique({
        where: {
          address_id: req.body.addressId,
        },
        include: {
          state: true,
          country: true,
        },
      });

      decision.options.newAddress = address;
      break;

    case "questions-ltl-freight-shipment":
      decision.options.deliveryType = req.body.deliveryType;
      decision.options.requireLiftGate = req.body.requireLiftGate;
      decision.options.deliveryOutfit = req.body.deliveryOutfit;
      decision.options.phoneCode = req.body.phoneCode;
      decision.options.phone = req.body.phone;
      decision.options.phone_ext = req.body.phone_ext;
      break;

    case "send-us-po":
      decision.options.action = req.body.method;
      break;

    case "unpaid-order":
      decision.options.action = req.body.action;

      switch (req.body.action) {
        case "pay-by-card":
          const user = await prisma.xcart_users.findUnique({
            where: {
              user_id: req.user.userId,
            },
          });
          const { orderid, order_prefix } = decision.order;
          const paymentIntentObject = await stripeService.paymentIntent.create({
            amount: parseFloat(decision.order.total) * 100,
            currency: "usd",
            payment_method_types: ["card"],
            description: `S3 Stores, Inc. Order # ${order_prefix}${orderid}`,
            customer: user.stripe_customer_id,
            capture_method: "manual",
          });

          await stripeService.paymentIntent.confirm(
            paymentIntentObject.id,
            req.body.cardId
          );

          await prisma.xcart_orders.update({
            where: {
              orderid: decision.order.orderid,
            },
            data: {
              cb_status: "AP",
            },
          });

          await prisma.xcart_order_groups.updateMany({
            where: {
              orderid: decision.order.orderid,
            },
            data: {
              cb_status: "AP",
            },
          });

          const card = await stripeService.card.retrieve(
            user.stripe_customer_id,
            req.body.cardId
          );

          decision.options.card = {
            brand: card.brand,
            last4: card.last4,
          };

          break;

        case "cancel-order":
          await prisma.xcart_orders.update({
            where: {
              orderid: decision.order.orderid,
            },
            data: {
              cb_status: "F",
            },
          });

          await prisma.xcart_order_groups.updateMany({
            where: {
              orderid: decision.order.orderid,
            },
            data: {
              cb_status: "F",
            },
          });
          break;

        case "pay-by-paypal":
          re;
      }

      break;

    case "additional-shipping-charge":
      decision.options.action = req.body.method;
      decision.options.card_id = req.body.card_id;
      break;
  }

  await prisma.account_decisions.updateMany({
    data: {
      solved: 1,
      options: decision.options,
    },
    where: {
      decision_id: req.body.decision_id,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  res.json({ user });
});

module.exports = app;
