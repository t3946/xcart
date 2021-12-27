const jwt = require("jsonwebtoken");
const authConfig = require("../config/auth");
const { PrismaClient } = require("@prisma/client");
const prisma = new PrismaClient();

async function generateJWT(userId) {
  const user = await prisma.user.findUnique({
    where: {
      user_id: userId,
    },
  });
  const payload = {
    userId: user.user_id,
    createdTime: new Date().getTime(),
    accessToken: user.access_token,
  };

  return jwt.sign(payload, authConfig.jwtSecret);
}

//generate session token and set it in response cookie
module.exports.setCookie = async function (res, userId) {
  const sessionToken = await generateJWT(userId);

  res.cookie("session", sessionToken, {
    maxAge: authConfig.userSessionTimeoutS * 1000,
  });
};
