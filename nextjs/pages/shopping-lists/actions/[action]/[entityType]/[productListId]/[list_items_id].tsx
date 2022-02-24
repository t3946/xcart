import { useRouter } from "next/router";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import * as React from "react";
import { DeleteProductPage } from "@modules/account/pages/DeleteProductPage";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { MoveProductPage } from "@modules/account/pages/MoveProductPage";
import { EditInfoInListProductPage } from "@modules/account/pages/EditInfoInListProductPage";

const ActionProductList = () => {
  const router = useRouter();
  const { lists } = useSelectorAccount((state) => state.lists);
  const getActionsComponent = () => {
    switch (router.query.action) {
      case "delete-product":
        return <DeleteProductPage />;
      case "move-product":
        return <MoveProductPage />;
      case "add-comment":
        return <EditInfoInListProductPage />;
      default:
        return null;
    }
  };
  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      {lists && getActionsComponent()}
    </PageTwoColumns>
  );
};
export default ActionProductList;
