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

// обычное удаление записи из таблицы
app.post("/delete-2", checkRights, async (req, res) => {
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

app.post("/delete", checkRights, async (req, res) => {
  const { list_item_id } = req.body;

  await prisma.account_list_items.updateMany({
    where: {
      list_item_id,
      deleted: null,
    },
    data: {
      deleted: new Date(),
    },
  });

  res.sendStatus(200);
});

// undo deleting
app.post("/restore", checkRights, async (req, res) => {
  const { list_item_id } = req.body;

  await prisma.account_list_items.update({
    where: {
      list_item_id,
    },
    data: {
      deleted: null,
    },
  });

  res.sendStatus(200);
});

module.exports = app;
