const app = require("express")();
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const canEditList = require("./utils/canEditList");
const getListsById = require("./utils/getListsById");
const apiItem = require("./Item");
const apiIdea = require("./Idea");
const apiRole = require("./Role");
const apiInvite = require("./Invite");
const { normalize } = require("../../../utils/product");

app.use("/item", apiItem);
app.use("/idea", apiIdea);
app.use("/role", apiRole);
app.use("/invite", apiInvite);

app.post("/get", async (req, res) => {
  const list = await getListsById(prisma, req.body.product_list_id);

  res.json(list);
});

app.get("/get-all", async (req, res) => {
  const lists = await prisma.account_product_lists.findMany({
    where: {
      OR: [
        {
          users: {
            some: {
              user_id: req.user.userId,
            },
          },
        },
        {
          user_id: req.user.userId,
        },
      ],
    },
    include: {
      addresses: true,
      owner: {
        select: {
          user_id: true,
          name: true,
          avatar_image: true,
        },
      },
      items: {
        orderBy: {
          order_by: "asc",
        },
        include: {
          idea: true,
          product: {
            include: {
              pricings: true,
            },
          },
        },
      },
      users: {
        select: {
          role: true,
          user: {
            select: {
              user_id: true,
              public_name: true,
              name: true,
              avatar_image: true,
            },
          },
        },
      },
    },
  });

  for (const list of lists) {
    for (const item of list.items) {
      if (item.product) {
        await normalize(item.product);
      }
    }
  }

  res.json(lists);
});

app.post("/create", async (req, res) => {
  const newList = await prisma.account_product_lists.create({
    data: {
      user_id: req.user.userId,
      name: req.body.name,
    },
  });

  const list = await getListsById(prisma, newList.product_list_id);

  res.json(list);
});

app.post("/update", async (req, res) => {
  const data = {};

  if (typeof req.body.description !== "undefined") {
    data.description = req.body.description;
  }

  if (typeof req.body.name !== "undefined") {
    data.name = req.body.name;
  }

  if (typeof req.body.recipient_name !== "undefined") {
    data.recipient_name = req.body.recipient_name;
  }

  if (typeof req.body.recipient_email !== "undefined") {
    data.recipient_email = req.body.recipient_email;
  }

  if (typeof req.body.birthday !== "undefined") {
    data.birthday = req.body.birthday + "";
  }

  if (typeof req.body.address_id !== "undefined") {
    data.address_id = req.body.address_id;
  }

  if (typeof req.body.default !== "undefined") {
    data.default = 1;

    await prisma.account_product_lists.updateMany({
      where: {
        user_id: req.user.userId,
      },
      data: {
        default: 0,
      },
    });
  }

  await prisma.account_product_lists.updateMany({
    data,
    where: {
      user_id: req.user.userId,
      product_list_id: req.body.product_list_id,
    },
  });

  const list = await prisma.account_product_lists.findFirst({
    where: {
      user_id: req.user.userId,
      product_list_id: req.body.product_list_id,
    },
  });

  res.json(list);
});

app.post("/delete", async (req, res) => {
  await prisma.account_product_lists.deleteMany({
    where: {
      user_id: req.user.userId,
      product_list_id: req.body.product_list_id,
    },
  });

  res.sendStatus(200);
});

app.post("/reorder-product", async (req, res) => {
  const items = await prisma.account_list_items.findMany({
    where: {
      list_item_id: {
        in: req.body.productIds,
      },
      list: {
        owner: {
          user_id: req.user.userId,
        },
      },
    },
  });

  for (const item of items) {
    const list_item_id = item.list_item_id;
    const i = req.body.productIds.indexOf(list_item_id);

    if (i !== -1) {
      await prisma.account_list_items.update({
        where: {
          list_item_id: list_item_id,
        },
        data: {
          order_by: i,
        },
      });
    }
  }

  res.sendStatus(200);
});

app.post("/transfer", async (req, res) => {
  const listItem = await prisma.account_list_items.findUnique({
    where: { list_item_id: req.body.list_item_id },
  });

  // can't edit source
  if (!(await canEditList(listItem.product_list_id, req.user.userId))) {
    res.sendStatus(403);
    return;
  }

  // can't edit destination
  if (!(await canEditList(req.body.product_list_id, req.user.userId))) {
    res.sendStatus(403);
    return;
  }

  await prisma.account_list_items.update({
    where: { list_item_id: req.body.list_item_id },
    data: {
      product_list_id: req.body.product_list_id,
    },
  });

  res.sendStatus(200);
});

app.post("/add-product", async (req, res) => {
  const { product_id, product_list_id } = req.body;

  // can't edit list
  if (!(await canEditList(product_list_id, req.user.userId))) {
    res.sendStatus(403);
    return;
  }

  const listItem = await prisma.account_list_items.findFirst({
    where: {
      product_id,
      product_list_id,
    },
  });

  if (!listItem) {
    await prisma.account_list_items.create({
      data: {
        product_id,
        product_list_id,
        product_type: "product",
      },
    });
  }

  res.sendStatus(200);
});

app.post("/add-idea", async (req, res) => {
  const { idea_id, product_list_id } = req.body;

  // can't edit list
  if (!(await canEditList(product_list_id, req.user.userId))) {
    res.sendStatus(403);
    return;
  }

  const listItem = await prisma.account_list_items.findFirst({
    where: {
      list_idea_id: idea_id,
      product_list_id,
    },
  });

  if (!listItem) {
    await prisma.account_list_items.create({
      data: {
        list_idea_id: idea_id,
        product_list_id,
        product_type: "idea",
      },
    });
  }

  res.sendStatus(200);
});

module.exports = app;
