const { normalize } = require("../../../../utils/product");

module.exports = async function getListsById(prisma, listId) {
  const list = await prisma.account_product_lists.findFirst({
    where: {
      product_list_id: listId,
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

  for (const item of list.items) {
    if (item.product) {
      await normalize(item.product);
    }
  }

  return list;
};
