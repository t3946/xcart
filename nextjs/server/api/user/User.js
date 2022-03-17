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
const AxiosInstance = axios.create();
const apiTwoStepVerification = require("./TwoStepVerification");
const apiStripe = require("./stripe/Stripe");
const stripeService = require("../../services/stripe");
const getBaseUrl = require("../../utils/getBaseUrl");
const authenticator = require("../../utils/otpAuthenticator");

app.use("/stripe", isAuthMiddleware, apiStripe);
app.use("/tsv", apiTwoStepVerification);

app.post("/login", function (req, res) {
  passport.authenticate("local", { session: false }, async (error, result) => {
    if (!result) {
      return res.send({ error });
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

      result.user.avatar_image =
        "https://i1.s3stores.com/" + result.user.avatar_image;

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

  user.avatar_image = "https://i1.s3stores.com/" + user.avatar_image;

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
    if (cookieName.search(/^xid\d+/) !== -1) {
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
  const maxWrongPasswordAttempts = 3;

  if (user) {
    res.send({
      captchaRequired: user.wrong_password_attempts >= maxWrongPasswordAttempts,
      email: user.email,
    });
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

app.post("/send-login-otp", async function (req, res) {
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

  // check user preferences to submit sms

  if (user.tsv_preferred_method === "na") {
    res.sendStatus(403);
    return;
  }

  const authApp = await prisma.xcart_authenticators.findFirst({
    where: {
      user_id: user.user_id,
    },
  });

  if (user.tsv_preferred_method === "authenticator_app" && authApp) {
    res.sendStatus(403);
    return;
  }

  // send sms with otp

  // try to send old otp
  let otp = await prisma.xcart_one_time_passwords.findFirst({
    where: {
      user_id: user.user_id,
      label: "login-by-sms",
    },
  });

  if (otp) {
    const now = new Date().getTime();
    const leftTime = Math.ceil((parseInt(otp.expired) - now) / 1000);

    //return old otp
    if (leftTime > 0) {
      delete otp.one_time_password;
      otp.leftTimeS = leftTime;
      res.json({
        otp,
      });
      return;
    }

    //delete expired otp
    await prisma.xcart_one_time_passwords.delete({
      where: {
        one_time_password_id: otp.one_time_password_id,
      },
    });
  }

  //send new otp
  const expTime = new Date().getTime() + 1000 * 120;
  const code = 100000 + Math.floor(Math.random() * 899999);
  otp = await prisma.xcart_one_time_passwords.create({
    data: {
      user_id: user.user_id,
      one_time_password: code.toString(),
      label: "login-by-sms",
      expired: expTime.toString(),
    },
  });

  await AxiosInstance.post(getBaseUrl(req) + `/api/account/send-sms`, {
    phone: user.phone,
    message: "This is your One Time Password: " + otp.one_time_password,
  });

  delete otp.one_time_password;
  otp.leftTimeS = Math.ceil(
    (parseInt(otp.expired) - new Date().getTime()) / 1000
  );
  res.json({ otp });
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
    getBaseUrl(req) + `/api/account/reset-password/send-one-time-password`,
    {
      login: req.body.login,
    }
  ).then((phpRes) => {
    res.json(phpRes.data);
  });
});

app.post("/verify-otp", async function (req, res) {
  AxiosInstance.post(
    getBaseUrl(req) + `/api/account/reset-password/verify-one-time-password`,
    {
      login: req.body.login,
      otp: req.body.otp,
    }
  ).then((apiRes) => {
    res.json(apiRes.data);
  });
});

app.post("/reset-password", async function (req, res) {
  AxiosInstance.post(
    getBaseUrl(req) + `/api/account/reset-password/reset-password`,
    {
      resetPasswordToken: req.body.resetPasswordToken,
      login: req.body.login,
      password: await passwordUtils.encryptPassword(req.body.password),
    }
  ).then(() => {
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
  const userWithNewEmail = await prisma.xcart_users.findUnique({
    where: {
      email: req.body.email,
    },
  });

  if (userWithNewEmail) {
    res.json({ error: "Such email already used" });
    return;
  }

  switch (req.body.step) {
    case "send-otp":
      const secret = authenticator.generateSecret();
      const token = authenticator.generate(secret);

      res.json({
        secret,
      });

      const data = {
        from: "vl0809081@gmail.com",
        to: req.body.email,
        subject: "Change email otp",
        text: `Change email otp: ${token}`,
        html: `<p>Change email otp: ${token}</p>`,
      };

      mail.sendMail(data, function () {
        res.sendStatus(200);
      });

      break;

    case "check-otp":
      const result = authenticator.check(req.body.token, req.body.secret);

      if (result) {
        res.sendStatus(200);
        return;
      }

      res.json({ error: "Invalid OTP. Please check your code and try again." });
      break;

    case "change-email":
      let user = await prisma.xcart_users.findUnique({
        where: {
          user_id: req.user.userId,
        },
      });

      const isPasswordsMatch = await passwordUtils.comparePassword(
        req.body.password,
        user.password
      );

      if (!isPasswordsMatch) {
        res.json({ error: "Your password is incorrect" });
        return;
      }

      await prisma.xcart_users.update({
        where: {
          user_id: req.user.userId,
        },
        data: {
          email: req.body.email,
        },
      });

      user = await prisma.xcart_users.findUnique({
        where: {
          user_id: req.user.userId,
        },
      });

      user.avatar_image = "https://i1.s3stores.com/" + user.avatar_image;
      res.json({ user });

      break;
  }
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

function getPaymentStatusCommonName(code) {
  let paymentStatus = null;

  switch (code) {
    case "P":
    case "AP":
    case "CH":
    case "V":
    case "3":
      paymentStatus = "Paid";
      break;

    case "S1":
    case "S2":
    case "S3":
    case "S4":
    case "Q":
    case "O":
    case "IO":
    case "I":
      paymentStatus = "Unpaid";
      break;

    case "A":
      paymentStatus = "Canceled";
      break;

    case "H":
    case "R":
      paymentStatus = "Refunded";
      break;
  }

  return paymentStatus;
}

function getShippingStatusCommonName(code) {
  let shippingStatus = null;

  switch (code) {
    case "T":
    case "K":
    case "M":
    case "E":
    case "DP":
      shippingStatus = "Ordered";
      break;

    case "C":
    case "L":
    case "DA":
    case "B":
    case "G":
      shippingStatus = "Dispatched";
      break;

    case "S":
      shippingStatus = "Shipped";
      break;

    case "OD":
      shippingStatus = "Out for delivery";
      break;

    case "Z":
      shippingStatus = "Delivered";
      break;
  }

  return shippingStatus;
}

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
          subtotal: true,
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
          non_us_confirmation: true,
        },
      },
    },
  });
  const orders = data === null ? [] : data.xcart_orders;
  const cards = (await stripeService.getSources(req.user.userId)).data;

  for (const order of orders) {
    //get order taxes
    await AxiosInstance.post(
      getBaseUrl(req) + `/api/account/orders/get-order-taxes`,
      {
        order_id: order.orderid,
      }
    ).then((res) => {
      order.taxes = res.data;
    });

    order.groups = await prisma.xcart_order_groups.findMany({
      where: {
        orderid: order.orderid,
      },
      include: {
        xcart_order_details: true,
      },
    });

    const deliveryMethods = [];
    let totalShipping = 0;

    for (const group of order.groups) {
      totalShipping = totalShipping + parseFloat(group.shipping_gross);

      //get order group taxes
      await AxiosInstance.post(
        getBaseUrl(req) + `/api/account/orders/get-order-group-taxes`,
        {
          order_group_id: group.order_group_id,
        }
      ).then((res) => {
        group.taxes = res.data;
      });
      group.paymentStatus = getPaymentStatusCommonName(group.cb_status);
      group.shippingStatus = getShippingStatusCommonName(group.dc_status);

      if (!group.shippingid) {
        continue;
      }

      const shippingName = (
        await prisma.xcart_shipping.findUnique({
          where: { shippingid: group.shippingid },
        })
      ).shipping;

      if (deliveryMethods.indexOf(shippingName) === -1) {
        deliveryMethods.push(shippingName);
      }
    }

    order.totalShipping = totalShipping.toFixed(2);
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
    )?.state;

    order.b_state = (
      await prisma.xcart_states.findFirst({
        where: { code: order.b_state, country_code: order.b_country },
      })
    )?.state;

    order.s_country = (
      await prisma.xcart_countries.findFirst({
        where: { code: order.s_country },
      })
    )?.name;

    order.b_country = (
      await prisma.xcart_countries.findFirst({
        where: { code: order.b_country },
      })
    )?.name;

    const ORDER_STATUS_UNPAID_PO = "O";
    const ORDER_STATUS_INCOMPLETE_PO = "IO";
    const poStatuses = [ORDER_STATUS_UNPAID_PO, ORDER_STATUS_INCOMPLETE_PO];

    if (poStatuses.indexOf(order.cb_status) !== -1) {
      await AxiosInstance.post(getBaseUrl(req) + `/api/get-extra`, {
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
