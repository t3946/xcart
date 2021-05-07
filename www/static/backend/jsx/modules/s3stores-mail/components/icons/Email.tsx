import React from "react";
import { IconButton, Tooltip } from "@material-ui/core";
import EmailIcon from "@material-ui/icons/Email";

export const EmailIc = () => {
  return (
    <Tooltip title="Delete">
      <IconButton>
        <EmailIcon />
      </IconButton>
    </Tooltip>
  );
};
