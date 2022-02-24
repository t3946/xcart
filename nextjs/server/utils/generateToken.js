const crypto = require("crypto");

//generate random token
module.exports = function generateToken(sizeB = 8) {
  return crypto.randomBytes(sizeB).toString("hex");
};
