import React from "react";
import { ListPrivateEnum } from "@modules/account/ts/consts/list-private.enum";
import LockIcon from "@modules/icon/components/account/lock/Lock";
import ShareIcon from "@modules/icon/components/account/share/ShareIcon";
import Styles from "@modules/account/components/lists/ListsSidebarLabel.module.scss";

interface ListsSidebarLabelProps {
  label: string;
  isPrivate: boolean;
}

export const ListsSidebarLabel: React.FC<ListsSidebarLabelProps> = ({
  label,
  isPrivate,
}) => {
  return (
    <div className="d-flex justify-content-between align-items-center alight-center lists-sidebar-label-content">
      <div className={Styles.listsSidebarLabelText} title={label}>
        {label}
      </div>
      {isPrivate ? (
        <LockIcon className={Styles.icon} />
      ) : (
        <ShareIcon className={Styles.icon} />
      )}
    </div>
  );
};
