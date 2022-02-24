import React from "react";
import { useHistory, useParams } from "react-router-dom";
import { AddIdea } from "@client/modules/account/components/lists/AddIdea";
import { MobileMenuBackBtn } from "@client/modules/account/pages/MobileMenuBackBtn";
import Store from "@client/jsx/redux/stores/Store";

interface AddIdeaPageURLParams {
  listHash: string;
}

export const AddIdeaPage: React.FC = () => {
  const history = useHistory();

  const params = useParams<AddIdeaPageURLParams>();

  const lists = Store.getState().lists.lists;

  if (!lists) {
    history.push(`/account/your-lists/${params.listHash}`);
    return;
  }

  const onCancelClick = () => {
    history.push(`/account/your-lists/${params.listHash}`);
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/account/your-lists/${params.listHash}`}
        label={"back"}
      />
      <div className="page-label">Add idea</div>
      <AddIdea listHash={params.listHash} onCancelBtnClick={onCancelClick} />
    </div>
  );
};
