import React from "react";
import { useHistory, useParams } from "react-router-dom";
import { AddIdea } from "@client/modules/account/components/lists/AddIdea";

interface AddIdeaPageURLParams {
  listHash: string;
}

export const AddIdeaPage: React.FC = () => {
  const history = useHistory();

  const params = useParams<AddIdeaPageURLParams>();

  const onCancelClick = () => {
    history.push(`/account/your-lists/${params.listHash}`);
  };

  return (
    <div>
      <div className="page-label">Add idea</div>
      <AddIdea listHash={params.listHash} onCancelBtnClick={onCancelClick} />
    </div>
  );
};
