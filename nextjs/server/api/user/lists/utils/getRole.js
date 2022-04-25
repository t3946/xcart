const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

async function getRole(list_id, user_id) {
  const list = await prisma.account_product_lists.findUnique({
    where: {
      product_list_id: list_id,
    },
    include: {
      users: true,
    },
  });

  if (parseInt(list.user_id) === user_id) {
    return "owner";
  }

  const role = await prisma.product_list_user_roles.findFirst({
    where: {
      list_id,
      user_id,
    },
  });

  if (role) {
    return role.role;
  }

  return null;
}

module.exports = getRole;
