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

module.exports = api;
