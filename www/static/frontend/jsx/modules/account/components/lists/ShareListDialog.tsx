import React from "react";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import { ShareList } from "@client/modules/account/components/lists/ShareList";

interface ShareListDialogProps {
  open: boolean;
  handleClose: () => void;
}

export const ShareListDialog: React.FC<ShareListDialogProps> = ({
  open,
  handleClose,
}) => {
  return (
    <BootstrapDialogHOC
      show={open}
      title={"Invite others to your list"}
      onClose={handleClose}
    >
      <ShareList onClose={handleClose} />
    </BootstrapDialogHOC>
  );
};
