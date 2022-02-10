const app = require("express")();
const passport = require("../../auth/Passport");
const generateToken = require("../../utils/generateToken");
const isAuthMiddleware = require("../../middleware/isAuth");
const setSessionCookie = require("../../utils/session").setCookie;
const passwordUtils = require("../../utils/password");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const axios = require("axios");
const mail = require("../../services/mail");
const AxiosInstance = axios.create({
  baseURL: process.env.BASE_URL_NGINX,
});
const apiStripe = require("./stripe/Stripe");
const stripeService = require("../../services/stripe");

app.use("/stripe", isAuthMiddleware, apiStripe);

app.post("/login", function (req, res) {
  passport.authenticate("local", { session: false }, async (err, result) => {
    if (!result) {
      return res.send({ error: err.message });
    }

    req.login(result.user, { session: false }, async (err) => {
      if (err) {
        return res.send(err);
      }

      const params = {
        userId: result.user.user_id,
        rememberMe: req.body.rememberMe,
      };

      await setSessionCookie(res, params);

      res.json({ user: result.user });
    });
  })(req, res);
});

app.get("/info", isAuthMiddleware, async (req, res) => {
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json(user);
});

app.get("/logout", isAuthMiddleware, async function (req, res) {
  await prisma.xcart_users.update({
    where: {
      user_id: req.user.userId,
    },
    data: {
      access_token: generateToken(),
    },
  });

  res.clearCookie("session");

  //drop xcart session id
  for (const cookieName in req.cookies) {
    console.log("cookieName", cookieName);
    if (cookieName.search(/^xid\d+/) !== -1) {
      console.log("drop", cookieName);
      res.clearCookie(cookieName);
    }
  }

  res.sendStatus(200);
});

app.post("/check-login", async function (req, res) {
  const user = await prisma.xcart_users.findFirst({
    where: {
      OR: [
        {
          email: req.body.login,
        },
        {
          phone: req.body.login,
        },
      ],
    },
  });

  if (user) {
    res.send();
  } else {
    res.json({ error: "User not found", user: req.body });
  }
});

app.post("/create", async function (req, res) {
  const { email, name, password } = req.body;
  let users = await prisma.xcart_users.findMany({
    where: {
      email,
    },
  });

  if (users.length) {
    res.json({ error: { email: "This email already registered" } });
  } else {
    const user = await prisma.xcart_users.create({
      data: {
        email,
        name,
        password: await passwordUtils.encryptPassword(password),
        access_token: generateToken(),
      },
    });

    await setSessionCookie(res, { userId: user.user_id });

    delete user.password;
    res.json({ user });
  }
});

app.post("/send-otp", async function (req, res) {
  const user = await prisma.xcart_users.findFirst({
    where: {
      OR: [
        {
          email: req.body.login,
        },
        {
          phone: req.body.login,
        },
      ],
    },
  });

  if (!user) {
    res.json({ error: { login: "User not found" } });
    return;
  }

  await AxiosInstance.post(
    "/api/account/reset-password/send-one-time-password",
    {
      login: req.body.login,
    }
  ).then((phpRes) => {
    res.json(phpRes.data);
  });
});

app.post("/verify-otp", async function (req, res) {
  AxiosInstance.post("/api/account/reset-password/verify-one-time-password", {
    login: req.body.login,
    otp: req.body.otp,
  }).then((apiRes) => {
    res.json(apiRes.data);
  });
});

app.post("/reset-password", async function (req, res) {
  AxiosInstance.post("/api/account/reset-password/reset-password", {
    resetPasswordToken: req.body.resetPasswordToken,
    login: req.body.login,
    password: await passwordUtils.encryptPassword(req.body.password),
  }).then(() => {
    res.sendStatus(200);
  });
});

app.post("/change-name", isAuthMiddleware, async function (req, res) {
  await prisma.xcart_users.update({
    where: {
      user_id: req.user.userId,
    },
    data: {
      name: req.body.name,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json({ user });

  res.clearCookie("session");
  res.sendStatus(200);
});

app.post("/change-email", isAuthMiddleware, async function (req, res) {
  await prisma.xcart_users.update({
    where: {
      user_id: req.user.userId,
    },
    data: {
      email: req.body.email,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json({ user });

  res.sendStatus(200);
});

app.post("/change-phone", isAuthMiddleware, async function (req, res) {
  //check phone
  const phone = req.body.phone;

  const userWithEqualPhone = await prisma.xcart_users.findUnique({
    where: {
      phone,
    },
  });

  if (userWithEqualPhone) {
    res.json({ errors: { phone: "This phone already exits" } });
    return;
  }

  const countryCode = parseInt(phone.slice(1, -10));
  const country = await prisma.xcart_countries.findUnique({
    where: {
      phone_code: countryCode,
    },
  });

  await prisma.xcart_users.update({
    where: {
      user_id: req.user.userId,
    },
    data: {
      phone,
      phone_country_code: country.code,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json({ user });
  res.sendStatus(200);
});

app.post("/change-password", isAuthMiddleware, async function (req, res) {
  const { oldPassword, newPassword } = req.body;
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  const isPasswordsMatch = await passwordUtils.comparePassword(
    oldPassword,
    user.password
  );

  if (!isPasswordsMatch) {
    res.json({ errors: { oldPassword: "Wrong password" } });
    return;
  }

  const hashed = await passwordUtils.encryptPassword(newPassword);

  await prisma.xcart_users.update({
    where: {
      user_id: user.user_id,
    },
    data: {
      password: hashed,
    },
  });

  res.sendStatus(200);
});

app.post("/tsv/confirm-code", isAuthMiddleware, async function (req, res) {
  await AxiosInstance.post("/api/account/tsv/confirm-code", {
    code: req.body.code,
    userId: req.user.userId,
  }).then((apiRes) => {
    res.json(apiRes.data);
    res.send();
  });
});

app.get("/tsv/disable", isAuthMiddleware, async function (req, res) {
  await AxiosInstance.post("/api/account/tsv/disable", {
    userId: req.user.userId,
  }).then((apiRes) => {
    res.json(apiRes.data);
    res.send();
  });
});

app.get("/tsv/get", isAuthMiddleware, async function (req, res) {
  await AxiosInstance.post("/api/account/tsv/get", {
    userId: req.user.userId,
  }).then((apiRes) => {
    res.json(apiRes.data);
    res.send();
  });
});

app.get("/tsv/require-for-all", isAuthMiddleware, async function (req, res) {
  await prisma.xcart_fingerprints.deleteMany({
    where: {
      user_id: req.user.userId,
    },
  });

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  delete user.password;

  res.json({ user });
});

app.post("/send-feedback", isAuthMiddleware, async function (req, res) {
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  const data = {
    from: "vl0809081@gmail.com",
    to: user.email,
    subject: "Feedback from " + user.name,
    text: req.body.message,
    html: `<p>${req.body.message}</p>`,
  };

  mail.sendMail(data, function () {
    res.sendStatus(200);
  });
});

app.get("/get-transactions", isAuthMiddleware, async function (req, res) {
  const data = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
    select: {
      xcart_orders: {
        select: {
          orderid: true,
          date: true,
          total: true,
          order_prefix: true,
          order_type: true,
          s_firstname: true,
          s_company: true,
          s_address: true,
          s_city: true,
          s_state: true,
          s_zipcode: true,
          s_country: true,
          b_firstname: true,
          b_company: true,
          b_address: true,
          b_city: true,
          b_state: true,
          b_zipcode: true,
          b_country: true,
          payment_method: true,
          paymentid: true,
          cb_status: true,
          xcart_order_transactions: true,
          phone: true,
          email: true,
          firstname: true,
          lastname: true,
        },
      },
    },
  });
  const orders = data === null ? [] : data.xcart_orders;
  const cards = (await stripeService.getSources(req.user.userId)).data;

  for (const order of orders) {
    order.groups = await prisma.xcart_order_groups.findMany({
      where: {
        orderid: order.orderid,
      },
      include: {
        xcart_order_details: true,
      },
    });

    const deliveryMethods = [];

    for (const group of order.groups) {
      const shippingName = (
        await prisma.xcart_shipping.findUnique({
          where: { shippingid: group.shippingid },
        })
      ).shipping;

      if (deliveryMethods.indexOf(shippingName) === -1) {
        deliveryMethods.push(shippingName);
      }
    }

    order.deliveryMethods = deliveryMethods.join(", ");

    order.status = await prisma.xcart_order_statuses.findFirst({
      where: {
        code: order.cb_status,
      },
      select: {
        name: true,
      },
    });

    order.s_state = (
      await prisma.xcart_states.findFirst({
        where: { code: order.s_state, country_code: order.s_country },
      })
    ).state;

    order.b_state = (
      await prisma.xcart_states.findFirst({
        where: { code: order.b_state, country_code: order.b_country },
      })
    ).state;

    order.s_country = (
      await prisma.xcart_countries.findFirst({
        where: { code: order.s_country },
      })
    ).name;

    order.b_country = (
      await prisma.xcart_countries.findFirst({
        where: { code: order.b_country },
      })
    ).name;

    const ORDER_STATUS_UNPAID_PO = "O";
    const ORDER_STATUS_INCOMPLETE_PO = "IO";
    const poStatuses = [ORDER_STATUS_UNPAID_PO, ORDER_STATUS_INCOMPLETE_PO];

    if (poStatuses.indexOf(order.cb_status) !== -1) {
      await AxiosInstance.post("/api/get-extra", {
        order_id: order.orderid,
      }).then((res) => {
        order.extra = res.data;
      });
    }
  }

  res.json({
    orders,
    cards,
  });
});

/**
 * /verify-one-time-password
 * /send-one-time-password
 * /reset-password
 */
module.exports = app;
