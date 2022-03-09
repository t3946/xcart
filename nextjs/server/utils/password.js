const bcrypt = require("bcrypt");
const config = require("../config/auth");

module.exports = {
  async encryptPassword(password) {
    const saltRounds = 10;
    let hashed;

    password += config.passwordSalt;

    await bcrypt.genSalt(saltRounds).then(async function (salt) {
      await bcrypt.hash(password, salt).then((hash) => {
        hashed = hash;
      });
    });

    return hashed;
  },

  async comparePassword(password, hash) {
    password += config.passwordSalt;

    let isPasswordMatch = false;

    await bcrypt
      .compare(password, hash)
      .then((result) => (isPasswordMatch = result));

    return isPasswordMatch;
  },
};
