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

      res.json({ user });

      break;
  }
});

app.post("/change-phone", isAuthMiddleware, async function (req, res) {
  const userWithNewPhone = await prisma.xcart_users.findUnique({
    where: {
      phone: req.body.phone,
    },
  });

  if (userWithNewPhone) {
    res.json({ error: "Such phone already used" });
    return;
  }

  switch (req.body.step) {
    case "send-otp":
      const secret = authenticator.generateSecret();
      const token = authenticator.generate(secret);

      res.json({
        secret,
      });

      await AxiosInstance.post(getBaseUrl(req) + `/api/account/send-sms`, {
        phone: req.body.phone,
        message: "This is your One Time Password: " + token,
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

    case "change-phone":
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
          phone: req.body.phone,
        },
      });

      user = await prisma.xcart_users.findUnique({
        where: {
          user_id: req.user.userId,
        },
      });

      res.json({ user });

      break;
  }
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

function countGroupTaxes(group) {
  const taxes = [];

  for (const groupTax of group.tax_rates) {
    const tax = groupTax.tax_rate.tax.tax_name;

    if (taxes[tax]) {
      taxes[tax] = 0;
    }

    taxes[tax] += groupTax.value;
  }

  return taxes;
}

function countOrderTaxes(groups) {
  const taxes = [];

  for (group of groups) {
    const groupTaxes = countGroupTaxes(group);

    for (const taxName in groupTaxes) {
      taxes[taxName] += groupTaxes[taxName];
    }
  }

  return taxes;
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
          shipping_cost: true,
          xcart_order_transactions: {
            select: {
              transaction_amount: true,
              type: true,
              transaction_status: true,
            },
          },
          phone: true,
          email: true,
          firstname: true,
          lastname: true,
          non_us_confirmation: true,
          xcart_refund_groups: {
            select: {
              xcart_refunded_products: {
                select: {
                  ref_price: true,
                  ref_qty: true,
                  xcart_order_details: {
                    select: {
                      itemid: true,
                      productcode: true,
                      product: true,
                      price: true,
                      amount: true,
                    },
                  },
                },
              },
            },
          },
          xcart_order_groups: {
            select: {
              total_gross: true,
              xcart_order_details: {
                select: {
                  itemid: true,
                  productcode: true,
                  product: true,
                  price: true,
                  amount: true,
                },
              },
              xcart_shipping: {
                select: {
                  shipping: true,
                },
              },
              xcart_order_group_taxes: {
                select: {
                  value: true,
                  xcart_tax_rates: {
                    select: {
                      xcart_taxes: {
                        select: {
                          tax_name: true,
                        },
                      },
                    },
                  },
                },
              },
              cb_status_rel: {
                select: {
                  xcart_order_human_readable_statuses: {
                    select: {
                      name: true,
                    },
                  },
                },
              },
              dc_status_rel: {
                select: {
                  xcart_order_human_readable_statuses: {
                    select: {
                      name: true,
                    },
                  },
                },
              },
            },
          },
          xcart_states_xcart_orders_b_state_idToxcart_states: true,
          xcart_states_xcart_orders_s_state_idToxcart_states: true,
          xcart_countries_xcart_countriesToxcart_orders_b_country_id: true,
          xcart_countries_xcart_countriesToxcart_orders_s_country_id: true,
          xcart_order_statuses_xcart_order_statusesToxcart_orders_cb_status_id: true,
          xcart_order_statuses_xcart_order_statusesToxcart_orders_dc_status_id: true,
          order_extra: true,
        },
      },
    },
  });
  const orders = data === null ? [] : data.xcart_orders;
  const cards = (await stripeService.getSources(req.user.userId)).data;

  for (const order of orders) {
    for (const group of order.xcart_refund_groups) {
      group.xcart_order_details = [];

      for (const refundedProduct of group.xcart_refunded_products) {
        refundedProduct.xcart_order_details.price = refundedProduct.ref_price;
        refundedProduct.xcart_order_details.amount = refundedProduct.ref_qty;
        group.xcart_order_details.push(refundedProduct.xcart_order_details);
      }

      delete group.xcart_refunded_products;
    }

    let totalShipping = 0;
    order.taxes = {};
    for (const group of order.xcart_order_groups) {
      totalShipping = totalShipping + parseFloat(group.shipping_gross);
      group.paymentStatus = getPaymentStatusCommonName(group.cb_status);
      group.shippingStatus = getShippingStatusCommonName(group.dc_status);

      //count taxes
      for (const tax of group.xcart_order_group_taxes) {
        const value = parseFloat(tax.value);
        const name = tax.xcart_tax_rates.xcart_taxes.tax_name;

        if (!order.taxes[name]) {
          order.taxes[name] = 0;
        }

        order.taxes[name] += value;
      }
    }

    order.status = {
      name: order
        .xcart_order_statuses_xcart_order_statusesToxcart_orders_cb_status_id
        ?.name,
    };
    order.s_state =
      order.xcart_states_xcart_orders_s_state_idToxcart_states?.state;
    order.b_state =
      order.xcart_states_xcart_orders_b_state_idToxcart_states?.state;
    order.s_country =
      order.xcart_countries_xcart_countriesToxcart_orders_s_country_id?.name;
    order.b_country =
      order.xcart_countries_xcart_countriesToxcart_orders_b_country_id?.name;
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
