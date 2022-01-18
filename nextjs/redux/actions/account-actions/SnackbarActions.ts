import { AccountSnackbarStore } from "@modules/account/ts/types/store.type";

export const showSnackbar = (payload: AccountSnackbarStore): any => ({
  type: "SHOW_SNACKBAR",
  payload,
});

export const hideSnackbar = (): any => ({ type: "HIDE_SNACKBAR" });
