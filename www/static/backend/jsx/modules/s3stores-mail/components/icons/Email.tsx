import React from "react";
import { IconButton, Tooltip } from "@material-ui/core";
import EmailIcon from "@material-ui/icons/Email";

export const EmailIc: React.FC = () => {
  return (
    <Tooltip title="Delete">
      <IconButton>
        <EmailIcon />
      </IconButton>
    </Tooltip>
  );
};
