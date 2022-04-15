import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import { NextPage } from "next";
import ListsPage from "@modules/account/pages/ListsPage";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import { setListView } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";

const ShoppingLists: NextPage<any> = function () {
  const router = useRouter();
  const id = parseInt(router.query.id);
  const { lists, currentList } = useSelectorAccount((state) => state.lists);
  const dispatch = useDispatch();

  React.useEffect(() => {
    if (currentList && currentList.product_list_id === id) {
      return;
    }

    for (const list of lists) {
      if (list.product_list_id === id) {
        dispatch(setListView(list));
        return;
      }
    }

    router.push("/shopping-lists");
  });

  if (!currentList) {
    return null;
  }

  return (
    <PageTwoColumns bar={<ListsSidebarMenu list={currentList} />}>
      <ListsPage list={currentList} />
    </PageTwoColumns>
  );
};

export default ShoppingLists;
