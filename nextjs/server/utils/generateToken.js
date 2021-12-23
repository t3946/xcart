const crypto = require("crypto");

module.exports = function generateToken(sizeB = 8) {
  return crypto.randomBytes(sizeB).toString("hex");
};
