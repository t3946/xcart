const api = require("express")();
const stripeService = require("../../../services/stripe");

api.get("/get", async function (req, res) {
  const customer = await stripeService.getCustomer(req.user.userId);

  res.json(customer);
});

api.post("/change-default-source", async function (req, res) {
  await stripeService.updateCustomer(req.user.userId, {
    default_source: req.body.source,
  });

  const customer = await stripeService.getCustomer(req.user.userId);

  res.json({ customer });
});

api.post("/update-source", async function (req, res) {
  await stripeService.customer.updateSource(
    req.user.userId,
    req.body.cardId,
    req.body.params
  );

  res.sendStatus(200);
});

module.exports = api;
