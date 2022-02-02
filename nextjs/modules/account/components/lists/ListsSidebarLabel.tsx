import React from "react";
import { ListPrivateEnum } from "@modules/account/ts/consts/list-private.enum";
import LockIcon from "@modules/icon/components/account/lock/Lock";
import ShareIcon from "@modules/icon/components/account/share/ShareIcon";

import Styles from "@modules/account/components/lists/ListsSidebarLabel.module.scss";

interface ListsSidebarLabelProps {
  label: string;
  privateType: ListPrivateEnum;
}

export const ListsSidebarLabel: React.FC<ListsSidebarLabelProps> = ({
  label,
  privateType,
}) => {
  return (
    <div className="d-flex justify-content-between align-items-center alight-center lists-sidebar-label-content">
      <div className="lists-sidebar-label-text">{label}</div>
      {(() => {
        switch (privateType) {
          case ListPrivateEnum.PRIVATE:
            return <LockIcon className={Styles.icon} />;
          case ListPrivateEnum.SHARED:
            return <ShareIcon className={Styles.icon} />;
        }
      })()}
    </div>
  );
};
