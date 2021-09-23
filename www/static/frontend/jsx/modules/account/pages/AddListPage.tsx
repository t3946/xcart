import React from "react";
import { useHistory } from "react-router-dom";
import { CreateNewList } from "@client/modules/account/components/lists/CreateNewList";

export const AddListPage: React.FC = () => {
  const history = useHistory();

  const onCancelClick = () => {
    history.push(`/account/your-lists`);
  };

  return (
    <div>
      <div className="page-label">Create list</div>
      <CreateNewList onCancelBtnClick={onCancelClick} />
    </div>
  );
};
