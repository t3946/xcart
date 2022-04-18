import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import ListsPage from "@modules/account/pages/ListsPage";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { NextPage } from "next";
import { useRouter } from "next/router";
import * as React from "react";

const ShoppingLists: NextPage = () => {
  const { lists } = useSelectorAccount((state) => state.lists);
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  if (!user) {
    return null;
  }

  if (lists.length === 0) {
    return "No lists";
  }

  const currentList = lists[0];

  return (
    <PageTwoColumns bar={<ListsSidebarMenu list={currentList} />}>
      <ListsPage list={currentList} />
    </PageTwoColumns>
  );
};

export default ShoppingLists;
