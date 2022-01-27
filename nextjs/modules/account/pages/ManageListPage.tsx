import React, { Fragment } from "react";
import { ManageList } from "@modules/account/components/lists/ManageList";
import { List } from "@modules/account/ts/types/list.type";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";

interface ManageListPage {
  listHash: string;
}

export const ManageListPage: React.FC<ManageListPage> = ({ listHash }) => {
  const router = useRouter();
  const lists: List[] = useSelectorAccount((state) => state.lists.lists);

  const list = lists.find((list) => list.cacheUrl === listHash);

  const onCancelClick = () => {
    router.push(`/shopping-lists/${list.cacheUrl}`);
  };

  return (
    <Fragment>
      <div className="page-label">Manage list</div>
      <ManageList info={list} onCancelClick={onCancelClick} />
    </Fragment>
  );
};
