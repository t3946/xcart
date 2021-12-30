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

module.exports = app;
