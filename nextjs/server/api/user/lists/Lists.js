const app = require("express")();
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const { encrypt, decrypt } = require("../../../utils/aes256ctrEncrypt");
const canEditList = require("./utils/canEditList");
const apiItem = require("./Item");
const apiIdea = require("./Idea");

app.use("/item", apiItem);
app.use("/idea", apiIdea);

app.post("/get", async (req, res) => {
  const list = await prisma.account_product_lists.findFirst({
    where: {
      product_list_id: req.body.product_list_id,
    },
    include: {
      addresses: true,
      owner: {
        select: {
          name: true,
          avatar_image: true,
        },
      },
      items: {
        where: {
          deleted: null,
        },
        include: {
          idea: true,
          product: true,
        },
      },
      roles: {
        select: {
          user_id: true,
          role: true,
        },
      },
    },
  });

  res.json(list);
});

app.get("/get-all", async (req, res) => {
  const lists = await prisma.account_product_lists.findMany({
    where: {
      user_id: req.user.userId,
    },
    include: {
      addresses: true,
      owner: {
        select: {
          name: true,
          avatar_image: true,
        },
      },
      items: {
        where: {
          deleted: null,
        },
      },
      roles: {
        select: {
          user_id: true,
          role: true,
        },
      },
    },
  });

  res.json(lists);
});

app.get("/get-by-cache/:cache", async (req, res) => {
  const list = await prisma.account_product_lists.findFirst({
    where: {
      user_id: req.user.userId,
      cache_url: req.params.cache,
    },
    include: {
      addresses: true,
      owner: {
        select: {
          name: true,
          avatar_image: true,
        },
      },
      items: true,
      roles: {
        select: {
          user_id: true,
          role: true,
        },
      },
    },
  });

  res.json(list);
});

app.post("/create", async (req, res) => {
  const list = await prisma.account_product_lists.create({
    data: {
      user_id: req.user.userId,
      name: req.body.name,
    },
  });

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
    data.birthday = req.body.birthday;
  }

  if (typeof req.body.address_id !== "undefined") {
    data.address_id = req.body.address_id;
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

app.post("/get-invite-url", async (req, res) => {
  const { list_id, invite_user_id, role } = req.body;
  const data = [list_id, invite_user_id, role].join(",");
  const { iv, content } = encrypt(data);
  const url = `/api-client/user/lists/use-invite/${iv}/${content}`;

  res.json({ url });
});

app.get("/use-invite/:iv/:content", async (req, res) => {
  const data = decrypt(req.params).split("/");

  res.json(data);
});

app.post("/reorder-product", async (req, res) => {
  const items = await prisma.account_list_items.findMany({
    where: {
      list_item_id: {
        in: req.body.productIds,
      },
      account_product_lists: {
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
