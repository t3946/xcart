import React from "react";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";

interface IProps {
  items: MobileMenuForListItem[];
  dialogOpen: boolean;
  dialogOnClose: () => void;
  hat?: any;
}

export const MobileMenuForList: React.FC<IProps> = (props) => {
  const { items, dialogOnClose, dialogOpen, hat } = props;

  return (
    <BootstrapDialogHOC
      classes={{
        body: "list-menu-actions-dialog-body",
        header: "list-menu-actions-dialog-header",
        modal: "list-menu-actions-dialog-modal",
      }}
      show={dialogOpen}
      onClose={dialogOnClose}
    >
      {!!hat && hat()}
      {items.map((e, i) => {
        return (
          <div
            className="mobile-menu-for-list-item"
            onClick={e.onClick}
            key={`item-list-${i}`}
          >
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
