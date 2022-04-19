module.exports = async function getListsById(prisma, listId) {
  return await prisma.account_product_lists.findFirst({
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
        where: {
          deleted: null,
        },
        orderBy: {
          order_by: "asc",
        },
        include: {
          idea: true,
          product: true,
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
            },
          },
        },
      },
    },
  });
};
