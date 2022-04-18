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

  const currentList = lists.length ? lists[0] : null;

  return (
    <PageTwoColumns bar={<ListsSidebarMenu list={currentList} />}>
      {currentList && <ListsPage list={currentList} />}
    </PageTwoColumns>
  );
};

export default ShoppingLists;
