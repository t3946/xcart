const api = require("express")();
const apiCard = require("./Card");
const apiCustomer = require("./Customer");
const apiPaymentIntent = require("./PaymentIntent");
const isAuthMiddleware = require("../../../middleware/isAuth");

api.use("/card", isAuthMiddleware, apiCard);
api.use("/customer", isAuthMiddleware, apiCustomer);
api.use("/payment-intent", isAuthMiddleware, apiPaymentIntent);

module.exports = api;
