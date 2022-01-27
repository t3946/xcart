import React, { Fragment } from "react";
import { ListHeader } from "@modules/account/components/lists/ListHeader";
import { ListProductItems } from "@modules/account/components/lists/ListProductItems";
import { useRouter } from "next/router";
import { List } from "@modules/account/ts/types/list.type";
interface ViewLists {
  isShoppingList: boolean;
}
export const ViewLists: React.FC<ViewLists> = ({ isShoppingList }) => {
  const router = useRouter();
  return (
    <Fragment>
      <ListHeader isShoppingList={isShoppingList} />
      <ListProductItems />
    </Fragment>
  );
};
