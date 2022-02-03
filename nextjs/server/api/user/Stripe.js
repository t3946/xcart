const apiStripe = require("express")();
const stripeService = require("../../services/stripe");

apiStripe.get("/customer/get", async function (req, res) {
  const customer = await stripeService.getCustomer(req.user.userId);

  res.json(customer);
});

apiStripe.post("/customer/change-default-source", async function (req, res) {
  await stripeService.updateCustomer(req.user.userId, {
    default_source: req.body.source,
  });

  const customer = await stripeService.getCustomer(req.user.userId);

  res.json({ customer });
});

apiStripe.get("/sources/get", async function (req, res) {
  const sources = await stripeService.getSources(req.user.userId);

  res.json(sources);
});

apiStripe.post("/sources/create", async function (req, res) {
  const source = await stripeService.createSources(
    req.user.userId,
    req.body.token
  );

  res.json(source);
});

apiStripe.post("/sources/delete", async function (req, res) {
  console.log("body", req.body)
  const source = await stripeService.source.delete(req.user.userId,req.body.cardId);

  res.json(source);
});

module.exports = apiStripe;
