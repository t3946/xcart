const api = require("express")();
const stripeService = require("../../../services/stripe");

api.post("/confirm", async function (req, res) {
  const { pi, pm } = req.body;
  const sources = await stripeService.paymentIntent.confirm(pi, pm);

  res.json(sources);
});

module.exports = api;
