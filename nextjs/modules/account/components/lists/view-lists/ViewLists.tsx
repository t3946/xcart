import React from "react";
import { ListHeader } from "@modules/account/components/lists/ListHeader";
// import { ListProductItems } from "@modules/account/components/lists/ListProductItems";
// import ListProductItems from "@modules/account/components/lists/list-items/ListProductItems";
// import ListProductItems from "@modules/account/components/lists/list-items/ListProductItems";
import { ListProductItems } from "@modules/account/components/lists/ListProductItems";
import InnerPage from "@components/common/inner-page/InnerPage";

interface ViewLists {
  list: any;
  isShoppingList: boolean;
}

export const ViewLists: React.FC<ViewLists> = (props) => {
  const { isShoppingList, list } = props;

  return (
    <InnerPage
      bodyClasses={"p-0"}
      headerClasses={"p-0"}
      header={<ListHeader list={list} isShoppingList={isShoppingList} />}
    >
      <ListProductItems list={list} />
    </InnerPage>
  );
};
