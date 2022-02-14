import Store from "@redux/stores/Store";
import {
  showSnackbar,
  hideSnackbar,
} from "@redux/actions/account-actions/SnackbarActions";
import React from "react";

export enum VariantsEnum {
  success = "success",
  warning = "warning",
  error = "error",
}

export const useSnackbar = () => {
  return { show: setSnackbar, close: () => Store.dispatch(hideSnackbar()) };
};

const setSnackbar = (
  message: React.ReactNode | string,
  duration = 3000,
  variant: VariantsEnum = VariantsEnum.success
) => {
  Store.dispatch(
    showSnackbar({
      alert: { duration: duration, message: message, variant: variant },
    })
  );
};

export default useSnackbar;
