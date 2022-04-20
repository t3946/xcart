import React, { Fragment } from "react";
import { ManageList } from "@modules/account/components/lists/ManageList";
import { useRouter } from "next/router";

interface IProps {
  list: any;
}

export const ManageListPage: React.FC<IProps> = (props) => {
  const { list } = props;
  const router = useRouter();

  const onCancelClick = () => {
    router.push(`/shopping-lists/${list.cacheUrl}`);
  };

  return (
    <Fragment>
      <div className="page-label">Manage list</div>
      <ManageList list={list} onCancelClick={onCancelClick} />
    </Fragment>
  );
};
