import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import { NextPage } from "next";
import ListsPage from "@modules/account/pages/ListsPage";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import { useDispatch } from "react-redux";
import { loadLists } from "@redux/actions/account-actions/ListsActions";

const ShoppingLists: NextPage<any> = function () {
  const dispatch = useDispatch();
  const router = useRouter();
  const { lists } = useSelectorAccount((state) => state.lists);

  const user = useSelectorAccount((state) => state.user);

  React.useEffect(() => {
    if (!list) {
      router.push("/shopping-lists");
    }

    if (!user) {
      router.push("/login");
    }
  });

  if (lists === null) {
    dispatch(loadLists());
    return null;
  }

  const list =
    lists && lists.find((e) => e.product_list_id === parseInt(router.query.id));

  if (!list) {
    return null;
  }

  return (
    <PageTwoColumns bar={<ListsSidebarMenu list={list} />}>
      <ListsPage list={list} />
    </PageTwoColumns>
  );
};

export default ShoppingLists;
