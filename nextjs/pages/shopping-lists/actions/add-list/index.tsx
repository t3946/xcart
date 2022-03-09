import { NextPage } from "next";
import { AddListPage } from "@modules/account/pages/AddListPage";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const AddList: NextPage = () => {
  const { lists } = useSelectorAccount((state) => state.lists);
  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      {lists && <AddListPage />}
    </PageTwoColumns>
  );
};
export default AddList;
