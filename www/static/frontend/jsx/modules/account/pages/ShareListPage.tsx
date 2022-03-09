import React from "react";
import { useHistory, useParams } from "react-router-dom";
import Store from "@client/jsx/redux/stores/Store";
import { ShareList } from "@client/modules/account/components/lists/ShareList";
import { MobileMenuBackBtn } from "@client/modules/account/pages/MobileMenuBackBtn";

interface ShareListPageURLParams {
  id: string;
}

export const ShareListPage: React.FC = () => {
  const params = useParams<ShareListPageURLParams>();

  const lists = Store.getState().lists.lists;

  const history = useHistory();

  if (!lists) {
    history.push(`/shopping-lists/${params.id}`);
    return;
  }

  const list = lists.find((e) => e.cache_url === params.id);

  const onCancelClick = () => {
    history.push(`/shopping-lists/${list.cache_url}`);
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/shopping-lists/${list.cache_url}`}
        label={"back"}
      />
      <div className="page-label">Share list with others</div>
      <ShareList onClose={onCancelClick} />
    </div>
  );
};
