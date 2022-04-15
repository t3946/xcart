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
  const { action, cache } = router.query;
  const { lists } = useSelectorAccount((state) => state.lists);
  const getComponent = () => {
    switch (action) {
      case "manage-list":
        return <ManageListPage listHash={cache} />;
      case "delete-list":
        return <DeleteListPage />;
      case "share-list":
        return <ShareListPage />;
      case "add-idea":
        return <AddIdeaPage />;
    }
  };
  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      {lists && getComponent()}
    </PageTwoColumns>
  );
};
export default ActionListPage;
