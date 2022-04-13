const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

async function canEditList(list_id, user_id) {
  const list = await prisma.account_product_lists.findUnique({
    where: {
      product_list_id: list_id,
    },
    include: {
      roles: true,
    },
  });

  return parseInt(list.user_id) === user_id;
}

module.exports = canEditList;
