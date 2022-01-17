import { AccountSnackBarStore } from "@modules/account/ts/types/store.type";

export const showSnackBar = (payload: AccountSnackBarStore): any => ({
  type: "SHOW_SNACKBAR",
  payload,
});

export const hideSnackBar = (): any => ({ type: "HIDE_SNACKBAR" });
