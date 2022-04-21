import React from "react";
import { EditComment } from "@modules/account/components/lists/EditComment";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { List } from "@modules/account/ts/types/list.type";
import { useRouter } from "next/router";

interface IProps {
  list: any;
  listItem: any;
}

export const EditInfoInListProductPage: React.FC<IProps> = (props) => {
  const { list, listItem } = props;
  const router = useRouter();

  function onCloseClick() {
    router.push(`/shopping-lists/${list.product_list_id}`);
  }

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/shopping-lists/${list.product_list_id}`}
        label={"back"}
      />
      <div className="page-label">Edit comment, quantity & priority</div>

      <EditComment listItem={listItem} onCloseClick={onCloseClick} />
    </div>
  );
};
