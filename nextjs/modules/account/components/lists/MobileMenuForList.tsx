import React from "react";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";

interface MobileMenuForListProps {
  items: MobileMenuForListItem[];
  dialogOpen: boolean;
  dialogOnClose: () => void;
}

export const MobileMenuForList: React.FC<MobileMenuForListProps> = ({
  items,
  dialogOnClose,
  dialogOpen,
}) => {
  return (
    <BootstrapDialogHOC
      classes={{
        body: "list-menu-actions-dialog-body",
        header: "list-menu-actions-dialog-header",
        modal: "list-menu-actions-dialog-modal",
      }}
      show={dialogOpen}
      title=""
      onClose={dialogOnClose}
    >
      {items.map((e) => {
        return (
          <div className="mobile-menu-for-list-item" onClick={e.onClick}>
            {e?.image && (
              <img className="mobile-menu-for-list-item-img" src={e.image} />
            )}
            {e?.label && (
              <div className="mobile-menu-for-list-item-text">{e.label}</div>
            )}
            {e?.component}
          </div>
        );
      })}
    </BootstrapDialogHOC>
  );
};
