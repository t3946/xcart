const apiStripe = require("express")();
const stripeService = require("../../services/stripe");

apiStripe.get("/get-customer", async function (req, res) {
  const customer = await stripeService.getCustomer(req.user.userId);

  res.json(customer);
});

apiStripe.get("/get-sources", async function (req, res) {
  const sources = await stripeService.getSources(req.user.userId);

  res.json(sources);
});

apiStripe.post("/create-sources", async function (req, res) {
  const source = await stripeService.createSources(
    req.user.userId,
    req.body.token
  );

  res.json(source);
});

apiStripe.post("/change-default-source", async function (req, res) {
  await stripeService.updateCustomer(req.user.userId, {
    default_source: req.body.source,
  });

  res.sendStatus(200);
});

module.exports = apiStripe;
