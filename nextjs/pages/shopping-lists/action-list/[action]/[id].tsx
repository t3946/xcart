import { NextPage } from "next";
import { useRouter } from "next/router";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import * as React from "react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { ManageListPage } from "@modules/account/pages/ManageListPage";
import { DeleteListPage } from "@modules/account/pages/DeleteListPage";
import { ShareListPage } from "@modules/account/pages/ShareListPage";
import { AddIdeaPage } from "@modules/account/pages/AddIdeaPage";

const ActionListPage: NextPage = () => {
  const router = useRouter();
  const { action, id } = router.query;
  const { lists } = useSelectorAccount((state) => state.lists);
  const list = lists.find((list) => list.product_list_id === parseInt(id));

  function getComponent() {
    switch (action) {
      case "manage-list":
        return <ManageListPage list={list} />;
      case "delete-list":
        return <DeleteListPage list={list} />;
      case "share-list":
        return <ShareListPage list={list} />;
      case "add-idea":
        return <AddIdeaPage list={list} />;
    }
  }

  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      {lists && getComponent()}
    </PageTwoColumns>
  );
};
export default ActionListPage;
