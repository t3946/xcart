import React from "react";
import { ListHeader } from "@modules/account/components/lists/ListHeader";
import { ListProductItems } from "@modules/account/components/lists/ListProductItems";
import InnerPage from "@components/common/inner-page/InnerPage";

interface ViewLists {
  isShoppingList: boolean;
}
export const ViewLists: React.FC<ViewLists> = ({ isShoppingList }) => {
  return (
    <InnerPage
      bodyClasses={"p-0"}
      headerClasses={"p-0"}
      header={<ListHeader isShoppingList={isShoppingList} />}
    >
      <ListProductItems />
    </InnerPage>
  );
};
