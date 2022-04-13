import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import ListsPage from "@modules/account/pages/ListsPage";
import { useDispatch } from "react-redux";
import { setListView } from "@redux/actions/account-actions/ListsActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { NextPage } from "next";
import { useRouter } from "next/router";
import * as React from "react";

const ShoppingLists: NextPage = () => {
  const { lists } = useSelectorAccount((state) => state.lists);
  const dispatch = useDispatch();
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);

  return "ShoppingLists";

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  React.useEffect(() => {
    if (lists && lists[0]) {
      dispatch(setListView(lists[0]));
    }
  }, [lists]);

  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      {"<ListsPage />"}
    </PageTwoColumns>
  );
};

export default ShoppingLists;
