import React from "react";
import { AddIdea } from "@modules/account/components/lists/AddIdea";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import { useRouter } from "next/router";

interface IProps {
  list: any;
}

export const AddIdeaPage: React.FC<IProps> = (props) => {
  const router = useRouter();
  const { list } = props;

  const onCancelClick = () => {
    router.push(`/shopping-lists/${list.product_list_id}`);
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/shopping-lists/${list.product_list_id}`}
        label={"back"}
      />
      <div className="page-label">Create a new Idea</div>
      <AddIdea list={list} onCancelBtnClick={onCancelClick} />
    </div>
  );
};
