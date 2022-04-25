import React from "react";
import { CreateNewList } from "@modules/account/components/lists/CreateNewList";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

export const AddListPage: React.FC = () => {
  return (
    <div>
      <MobileMenuBackBtn redirectUrl={`/shopping-lists`} label={"back"} />
      <div className="page-label">Create list</div>
      <CreateNewList />
    </div>
  );
};
