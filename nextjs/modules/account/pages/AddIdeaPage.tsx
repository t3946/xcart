import React from "react";
import { AddIdea } from "@modules/account/components/lists/AddIdea";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import { useRouter } from "next/router";

export const AddIdeaPage: React.FC = () => {
  const router = useRouter();
  const { cache } = router.query;

  const onCancelClick = () => {
    router.push(`/shopping-lists/${cache}`);
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/shopping-lists/${cache}`}
        label={"back"}
      />
      <div className="page-label">Add idea</div>
      <AddIdea listHash={cache} onCancelBtnClick={onCancelClick} />
    </div>
  );
};
