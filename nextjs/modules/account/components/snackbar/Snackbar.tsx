import React, { useState } from "react";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import UISnackbar from "@material-ui/core/Snackbar";
import classnames from "classnames";
import FaCheckLight from "@modules/icon/components/font-awesome/check/Light";
import FaExclamationTriangleLight from "@modules/icon/components/font-awesome/exclamation-triangle/Light";

const Snackbar: React.FC = ({ children }) => {
  const [open, setOpen] = React.useState(false);
  const [header, setHeader] = useState("");
  const [message, setMessage] = useState("");
  const [theme, setTheme] = useState();

  const showSnackbar = ({ header, message, theme }) => {
    setMessage(message);
    setHeader(header);
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

  function getHeaderIcon() {
    switch (theme) {
      case "success":
        return (
          <FaCheckLight className="me-12 d-inline-block account-alert-icon__success" />
        );

      case "wrong":
        return (
          <FaExclamationTriangleLight className="me-12 d-inline-block account-alert-icon__wrong" />
        );
    }
  }

  const classes = {
    muiClasses: [`account-alert__${theme}`],
    header: [`account-alert-header__${theme}`],
    icon: [`account-alert-icon__${theme}`],
  };

  return (
    <React.Fragment>
      <SnackbarContext.Provider
        value={{
          showSnackbar,
        }}
      >
        {children}
      </SnackbarContext.Provider>

      <UISnackbar
        anchorOrigin={{
          vertical: "top",
          horizontal: "center",
        }}
        classes={{
          root: "account-alert__root",
        }}
        open={open}
        autoHideDuration={3000}
        onClose={handleClose}
        className="mui-snackbar"
      >
        <div
          className={classnames(
            "mui-alert account-alert p-3 d-flex flex-column justify-content-between",
            classes.muiClasses
          )}
        >
          <h2
            className={classnames(
              "account-alert-header mb-1 d-flex align-items-center justify-content-center",
              classes.header
            )}
          >
            {getHeaderIcon()}

            {message}
          </h2>

          {/*<p className={"account-alert-content m-0"}>{message}</p>*/}
        </div>
      </UISnackbar>
    </React.Fragment>
  );
};

export default Snackbar;
