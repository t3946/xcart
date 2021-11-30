import React from "react";
import { useHistory, useParams } from "react-router-dom";
import Store from "@redux/stores/Store";
import { ShareList } from "@modules/account/components/lists/ShareList";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

interface ShareListPageURLParams {
  id: string;
}

export const ShareListPage: React.FC = () => {
  const params = useParams<ShareListPageURLParams>();

  const lists = Store.getState().lists.lists;

  const history = useHistory();

  if (!lists) {
    history.push(`/account/your-lists/${params.id}`);
    return;
  }

  const list = lists.find((e) => e.cache_url === params.id);

  const onCancelClick = () => {
    history.push(`/account/your-lists/${list.cache_url}`);
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/account/your-lists/${list.cache_url}`}
        label={"back"}
      />
      <div className="page-label">Share list with others</div>
      <ShareList onClose={onCancelClick} />
    </div>
  );
};
