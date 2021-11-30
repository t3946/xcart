import React from "react";
import { ListPrivateEnum } from "@modules/account/ts/consts/list-private.enum";

interface ListsSidebarLabelProps {
  label: string;
  privateType: ListPrivateEnum;
}

export const ListsSidebarLabel: React.FC<ListsSidebarLabelProps> = ({
  label,
  privateType,
}) => {
  return (
    <div className="d-flex justify-content-between alight-center lists-sidebar-label-content">
      <div className="lists-sidebar-label-text">{label}</div>
      <img
        src={`/static/frontend/images/icons/account/list-${privateType}.svg`}
      />
    </div>
  );
};
