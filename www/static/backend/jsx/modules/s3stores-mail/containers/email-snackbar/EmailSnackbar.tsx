import React, { useState } from "react";
import { EmailSnackbarContext } from "@s3stores-mail/contexts/email-snackbar/EmailSnackbar.context";
import { Snackbar } from "@material-ui/core";
import MuiAlert, { Color } from "@material-ui/lab/Alert";

export const EmailSnackbar: React.FC = ({ children }) => {
  const [open, setOpen] = React.useState(false);

  const [message, setMessage] = useState("");

  const [theme, setTheme] = useState<Color>("success");

  const showSnackbar = (message, theme) => {
    setMessage(message);
    setTheme(theme);
    setOpen(true);
  };

  const handleClose = (
    event: React.SyntheticEvent | React.MouseEvent,
    reason?: string
  ) => {
    if (reason === "clickaway") {
      return;
    }

    setOpen(false);
  };
  return (
    <div>
      <EmailSnackbarContext.Provider
        value={{
          showSnackbar,
        }}
      >
        {children}
      </EmailSnackbarContext.Provider>
      <Snackbar
        anchorOrigin={{
          vertical: "bottom",
          horizontal: "center",
        }}
        open={open}
        autoHideDuration={3000}
        onClose={handleClose}
      >
        <MuiAlert
          elevation={6}
          variant="filled"
          onClose={handleClose}
          severity={theme}
        >
          {message}
        </MuiAlert>
      </Snackbar>
    </div>
  );
};
