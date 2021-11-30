import React from "react";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { ShareList } from "@modules/account/components/lists/ShareList";

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
      title={"Share list with others"}
      onClose={handleClose}
    >
      <ShareList onClose={handleClose} />
    </BootstrapDialogHOC>
  );
};
