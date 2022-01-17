import Store from "@redux/stores/Store";
import {
  showSnackBar,
  hideSnackBar,
} from "@redux/actions/account-actions/SnackbarActions";
import { VariantsEnum } from "@modules/account/components/shared/SnackBar";
import React from "react";

export const useSnackBar = () => {
  return { show: setSnackBar, close: () => Store.dispatch(hideSnackBar()) };
};

const setSnackBar = (
  message: React.ReactNode | string,
  duration = 3000,
  variant: VariantsEnum = VariantsEnum.success
) => {
  Store.dispatch(
    showSnackBar({
      alert: { duration: duration, message: message, variant: variant },
    })
  );
};
