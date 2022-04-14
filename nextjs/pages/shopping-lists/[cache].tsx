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
  const { cache } = router.query;
  const lists = useSelectorAccount((state) => state.lists.lists);
  const dispatch = useDispatch();
  let currentList: any;

  for (const list of lists) {
    if (list.cache_url === cache) {
      currentList = list;
      break;
    }
  }

  React.useEffect(() => {
    dispatch(setListView(currentList));
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
