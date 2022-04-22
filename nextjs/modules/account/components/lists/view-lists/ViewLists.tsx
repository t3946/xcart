import React from "react";
import { ListHeader } from "@modules/account/components/lists/list-header/ListHeader";
import { ListProductItems } from "@modules/account/components/lists/ListProductItems";
import InnerPage from "@components/common/inner-page/InnerPage";

interface ViewLists {
  list: any;
}

export const ViewLists: React.FC<ViewLists> = (props) => {
  const { list } = props;

  return (
    <InnerPage
      hatClasses={"pb-md-0 pb-lg-4"}
      bodyClasses={"px-lg-0"}
      header={<ListHeader list={list} />}
    >
      <ListProductItems list={list} />
    </InnerPage>
  );
};
