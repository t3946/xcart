const api = require("express")();
const apiCard = require("./Card");
const apiCustomer = require("./Customer");
const isAuthMiddleware = require("../../../middleware/isAuth");

api.use("/card", isAuthMiddleware, apiCard);
api.use("/customer", isAuthMiddleware, apiCustomer);

module.exports = api;
