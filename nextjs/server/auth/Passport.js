module.exports = (function () {
  const passport = require("passport");

  require("./strategies/Local")(passport);
  require("./strategies/JWT")(passport);
  require("./strategies/Bearer")(passport);

  return passport;
})();
