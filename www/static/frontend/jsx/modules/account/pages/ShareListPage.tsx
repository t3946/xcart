import React from "react";
import { useHistory, useParams } from "react-router-dom";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { ShareList } from "@client/modules/account/components/lists/ShareList";

interface ShareListPageURLParams {
  id: string;
}

export const ShareListPage: React.FC = () => {
  const params = useParams<ShareListPageURLParams>();

  const history = useHistory();

  const list = accountStore
    .getState()
    .lists.lists.find((e) => e.cache_url === params.id);

  const onCancelClick = () => {
    history.push(`/account/your-lists/${list.cache_url}`);
  };

  return (
    <div>
      <div className="page-label">Share list with others</div>
      <ShareList onClose={onCancelClick} />
    </div>
  );
};
