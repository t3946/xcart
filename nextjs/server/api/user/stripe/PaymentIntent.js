const api = require("express")();
const stripeService = require("../../../services/stripe");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

api.post("/create", async function (req, res) {
  const { amount } = req.body;
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });
  const paymentIntentObject = await stripeService.paymentIntent.create({
    amount,
    currency: "usd",
    payment_method_types: ["card"],
    description: "Support for VSU",
    customer: user.stripe_customer_id,
  });

  res.json({ paymentIntentObject, customer: user.stripe_customer_id });
});

api.post("/confirm", async function (req, res) {
  const { pi, pm } = req.body;
  const result = await stripeService.paymentIntent
    .confirm(pi, pm)
    .catch((e) => {
      res.json({error: e.message});
    });

  res.json(result);
});

module.exports = api;
