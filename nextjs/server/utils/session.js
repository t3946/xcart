const jwt = require("jsonwebtoken");
const authConfig = require("../config/auth");
const { PrismaClient } = require("@prisma/client");
const prisma = new PrismaClient();

async function generateJWT(userId, timeoutMS) {
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: userId,
    },
  });
  const now = new Date().getTime();
  const payload = {
    userId: user.user_id,
    createdTime: new Date().getTime(),
    accessToken: user.access_token,
    timeout: now + timeoutMS,
  };

  return jwt.sign(payload, authConfig.jwtSecret);
}

//generate session token and set it in response cookie
module.exports.setCookie = async function (res, params) {
  const timeoutMS =
    params.rememberMe === true
      ? authConfig.rememberedUserSessionTimeoutS * 1000
      : authConfig.userSessionTimeoutS * 1000;
  const sessionToken = await generateJWT(params.userId, timeoutMS);

  res.cookie("session", sessionToken, {
    maxAge: timeoutMS,
  });
};
