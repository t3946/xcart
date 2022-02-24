import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import { NextPage } from "next";
import { useEffect } from "react";
import { useRouter } from "next/router";
import { useDispatch } from "react-redux";
import { fetchListByCache } from "@redux/actions/account-actions/ListsActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { AccountListsStore } from "@modules/account/ts/types/store.type";
import ListsPage from "@modules/account/pages/ListsPage";
const ShoppingLists: NextPage = () => {
  const dispatch = useDispatch();
  const router = useRouter();
  const { cache } = router.query;
  const { listView }: AccountListsStore = useSelectorAccount(
    (state) => state.lists
  );
  useEffect(() => {
    if (cache) {
      dispatch(fetchListByCache(String(cache)));
    }
  }, [cache]);
  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      {listView && <ListsPage />}
    </PageTwoColumns>
  );
};
export default ShoppingLists;
