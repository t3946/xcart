const app = require("express")();
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();
const canEditList = require("./utils/canEditList");

async function checkRights(req, res, done) {
  const { list_idea_id } = req.body;

  //check rights
  const list_item = await prisma.account_list_items.findFirst({
    where: {
      list_idea_id,
    },
  });

  if (
    !list_item ||
    !(await canEditList(list_item.product_list_id, req.user.userId))
  ) {
    res.sendStatus(403);
    return;
  }

  done();
}

app.post("/edit", checkRights, async (req, res) => {
  const { list_idea_id, name } = req.body;

  await prisma.account_list_ideas.update({
    where: {
      list_idea_id,
    },
    data: {
      name,
    },
  });

  res.sendStatus(200);
});

app.post("/create", async (req, res) => {
  const { name, product_list_id } = req.body;

  const idea = await prisma.account_list_ideas.create({
    data: {
      name,
    },
  });

  let list_item = await prisma.account_list_items.create({
    data: {
      product_list_id,
      list_idea_id: idea.list_idea_id,
      product_type: "idea",
    },
  });

  list_item = await prisma.account_list_items.findUnique({
    where: {
      list_item_id: list_item.list_item_id,
    },
    include: {
      idea: true,
      product: true,
    },
  });

  res.json({ list_item });
});

app.post("/delete", checkRights, async (req, res) => {
  const { list_idea_id } = req.body;

  await prisma.account_list_ideas.remove({
    where: {
      list_idea_id,
    },
  });

  res.sendStatus(200);
});

module.exports = app;
