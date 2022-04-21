import { useRouter } from "next/router";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import * as React from "react";
import { DeleteProductPage } from "@modules/account/pages/DeleteProductPage";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { MoveProductPage } from "@modules/account/pages/MoveProductPage";
import { EditInfoInListProductPage } from "@modules/account/pages/EditInfoInListProductPage";
import { List } from "@modules/account/ts/types/list.type";

const ActionProductList = () => {
  const router = useRouter();
  const { lists } = useSelectorAccount((state) => state.lists);
  const { productListId, itemId } = router.query;
  const list = lists.find(
    (list: List) => list.product_list_id === Number(productListId)
  );

  if (!list) {
    router.push("/shopping-lists");
    return null;
  }

  const item = list.items.find((item) => item.list_item_id === Number(itemId));

  if (!item) {
    router.push("/shopping-lists");
    return null;
  }

  function getActionsComponent() {
    switch (router.query.action) {
      case "delete-product":
        return <DeleteProductPage list={list} listItem={item} />;
      case "move-product":
        return <MoveProductPage list={list} listItem={item} />;
      case "add-comment":
        return <EditInfoInListProductPage list={list} listItem={item} />;
      default:
        return null;
    }
  }

  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      {lists && getActionsComponent()}
    </PageTwoColumns>
  );
};
export default ActionProductList;
