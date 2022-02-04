const Stripe = require("stripe");
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

async function getClient() {
  const paymentProcessorModel = await prisma.xcart_payment_processor.findFirst({
    where: {
      processor_name: "Stripe",
    },
  });
  const stripeSK = paymentProcessorModel.param02;

  return Stripe(stripeSK);
}

async function getCustomer(userId, stripe) {
  if (!stripe) {
    stripe = await getClient();
  }

  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: userId,
    },
  });

  //user no registered in stripe
  if (user.stripe_customer_id === null) {
    const customer = await stripe.customers.create({
      description: "Client id: " + userId,
      email: user.email,
    });

    if (!customer) {
      return;
    }

    await prisma.xcart_users.update({
      where: {
        user_id: userId,
      },
      data: {
        stripe_customer_id: customer.id,
      },
    });

    user.stripe_customer_id = customer.id;
  }

  return await stripe.customers.retrieve(user.stripe_customer_id);
}

async function updateCustomer(userId, params) {
  const stripe = await getClient();
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: userId,
    },
  });

  return stripe.customers.update(user.stripe_customer_id, params);
}

async function getSources(userId) {
  const stripe = await getClient();
  const customer = await getCustomer(userId);

  return stripe.customers.listSources(customer.id, {
    object: "card",
  });
}

async function createSources(userId, sourceToken, metadata) {
  const stripe = await getClient();
  const customer = await getCustomer(userId, stripe);

  return stripe.customers.createSource(customer.id, {
    source: sourceToken,
    metadata,
  });
}

module.exports = {
  getCustomer,
  updateCustomer,

  getSources,
  createSources,

  source: {
    delete: async function (userId, cardId) {
      const stripe = await getClient();
      const customer = await getCustomer(userId, stripe);

      console.log("deltee", [customer.id, cardId]);
      return await stripe.customers.deleteSource(customer.id, cardId);
    },
  },

  paymentIntent: {
    confirm: async function (pi, pm) {
      const stripe = await getClient();

      return await stripe.paymentIntents.confirm(pi, {
        payment_method: pm,
      });
    },
  },
};
