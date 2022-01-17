const passport = require("../auth/Passport");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

module.exports = [
  passport.authenticate("jwt", { session: false }),

  async function (req, res, next) {
    const nowTimeMS = new Date().getTime();

    //session outdated
    if (req.user.timeout < nowTimeMS) {
      return res.sendStatus(401);
    }

    const user = await prisma.xcart_users.findUnique({
      where: {
        user_id: req.user.userId,
      },
    });

    //user not found
    if (!user) {
      return res.sendStatus(401);
    }

    //session unauthorised
    if (user.access_token !== req.user.accessToken) {
      return res.sendStatus(401);
    }

    next();
  },
];
