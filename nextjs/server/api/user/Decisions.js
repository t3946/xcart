const express = require("express");
const isAuthMiddleware = require("../../middleware/isAuth");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const app = express();

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
      order: {
        select: {
          cb_status: true,
          dc_status: true,
        },
      },
      type: true,
    },
  });

  res.json({ decision });
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

  const options = {};

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
    },
  });

  switch (decision.type.slug) {
    case "po-send-check":
      decision.solved = true;
      decision.options.address = req.body.address;

      await prisma.account_decisions.updateMany({
        data: {
          solved: 1,
          options: decision.options,
        },
        where: {
          decision_id: req.body.decision_id,
        },
      });

      break;

    case "estimated-time-arrival":
      const { advice, comment } = req.body;

      await prisma.account_decisions.updateMany({
        data: {
          solved: 1,
          options: { advice, comment },
        },
        where: {
          decision_id: req.body.decision_id,
        },
      });

      break;

    case "ach-payment-required":

      break;
  }

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  res.json({ user });
});

module.exports = app;
