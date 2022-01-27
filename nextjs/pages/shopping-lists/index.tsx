import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import ListsPage from "@modules/account/pages/ListsPage";
import { useEffect } from "react";
import { useDispatch } from "react-redux";
import { setListView } from "@redux/actions/account-actions/ListsActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { NextPage } from "next";
import {ListSource} from "@modules/account/ts/types/list.type";

const ShoppingLists: NextPage = () => {
  const { lists, listView } = useSelectorAccount((state) => state.lists);
  const dispatch = useDispatch();
  useEffect(() => {
    if (lists) {
      dispatch(setListView(lists[0]));
    }
  }, [lists]);
  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      {listView && <ListsPage />}
    </PageTwoColumns>
  );
};

export default ShoppingLists;
