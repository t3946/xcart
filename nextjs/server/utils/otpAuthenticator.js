const { authenticator } = require("otplib");

authenticator.options = {
  epoch: Date.now(),
  step: 300,
  window: 1,
};

module.exports = authenticator;
