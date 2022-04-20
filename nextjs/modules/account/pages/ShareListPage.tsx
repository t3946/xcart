import React from "react";
import { ShareList } from "@modules/account/components/lists/ShareList";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import { useRouter } from "next/router";

interface IProps {
  list: any;
}

export const ShareListPage: React.FC<IProps> = (props) => {
  const router = useRouter();
  const { list } = props;

  function onCancelClick() {
    router.push(`/shopping-lists/${list.product_list_id}`);
  }

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/shopping-lists/${list.product_list_id}`}
        label={"back"}
      />
      <div className="page-label">Share list with others</div>
      <ShareList onClose={onCancelClick} list={list} />
    </div>
  );
};
