const app = require("express")();
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const canEditList = require("./utils/canEditList");

async function checkRights(req, res, done) {
  const { list_item_id } = req.body;

  //check rights
  const list_item = await prisma.account_list_items.findFirst({
    where: {
      list_item_id,
    },
    include: {
      list: true,
    },
  });

  if (!list_item || !list_item.list) {
    res.sendStatus(400);
    return;
  }

  if (!(await canEditList(list_item.list.product_list_id, req.user.userId))) {
    res.sendStatus(403);
    return;
  }

  done();
}

app.post("/edit", checkRights, async (req, res) => {
  const { list_item_id, comment, needs, has, priority } = req.body;

  await prisma.account_list_items.update({
    where: {
      list_item_id,
    },
    data: {
      comment,
      needs,
      has,
      priority,
    },
  });

  res.sendStatus(200);
});

// todo: что за метод не знаю
// обычное удаление записи из таблицы
app.post("/delete", checkRights, async (req, res) => {
  const { list_item_id } = req.body;

  const list_item = await prisma.account_list_items.findUnique({
    where: {
      list_item_id,
    },
  });

  if (list_item.list_idea_id) {
    await prisma.account_list_ideas.deleteMany({
      where: {
        list_idea_id: list_item.list_idea_id,
      },
    });
  }

  await prisma.account_list_items.deleteMany({
    where: {
      list_item_id,
    },
  });

  res.sendStatus(200);
});

// undo deleting
app.post("/restore", async (req, res) => {
  const { listItem } = req.body;

  if (!canEditList(listItem.product_list_id, req.user.userId)) {
    res.sendStatus(403);
    return;
  }

  switch (listItem.product_type) {
    case "idea":
      const idea = await prisma.account_list_ideas.create({
        data: {
          name: listItem.idea.name,
        },
      });

      listItem.list_idea_id = idea.list_idea_id;
      break;

    case "product":
      const item = await prisma.account_list_items.findFirst({
        where: {
          product_list_id: listItem.product_list_id,
          product_id: listItem.product_id,
        },
      });

      if (item) {
        res.sendStatus(403);
        return;
      }

      break;
  }

  await prisma.account_list_items.create({
    data: {
      product_id: listItem.product_id,
      list_idea_id: listItem.list_idea_id,
      product_list_id: listItem.product_list_id,
      order_by: listItem.order_by,
      product_type: listItem.product_type,
      comment: listItem.comment,
      priority: listItem.priority,
      needs: listItem.needs,
      has: listItem.has,
    },
  });

  res.sendStatus(200);
});

module.exports = app;
