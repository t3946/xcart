const express = require("express");
const isAuthMiddleware = require("../middleware/isAuth");
const PrismaClient = require("@prisma/client").PrismaClient;

const prisma = new PrismaClient();
const app = express();

//get current user decisions
app.get("/get-initial-state", isAuthMiddleware, async function (req, res) {
  const { skip, take } = req.body;
  const queryOptions = {
    where: {
      xcart_orders: {
        user_id: req.user.userId,
      },
    },
  };

  queryOptions.where.solved = 1;
  const solvedItems = await prisma.account_decisions.findMany({
    skip,
    take,
    ...queryOptions,
  });
  const solvedTotal = await prisma.account_decisions.count(queryOptions);

  queryOptions.where.solved = 0;
  const notSolvedItems = await prisma.account_decisions.findMany({
    skip,
    take,
    ...queryOptions,
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

  const decision = await prisma.account_decisions.create({
    data: {
      type,
      order_id,
      order_number: [order.order_prefix, order.orderid].join(""),
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
      xcart_orders: {
        user_id: req.user.userId,
      },
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

module.exports = app;
