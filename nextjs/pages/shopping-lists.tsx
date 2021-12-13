import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import ListsPage from "@modules/account/pages/ListsPage";

function ShoppingLists() {
  return (
    <PageTwoColumns>
      <ListsSidebarMenu />
      <ListsPage />
    </PageTwoColumns>
  );
}

export default ShoppingLists;
