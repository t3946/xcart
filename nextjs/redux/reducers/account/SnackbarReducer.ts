import { AnyAction } from "redux";
import { AccountSnackBarStore } from "@modules/account/ts/types/store.type";
import { VariantsEnum } from "@modules/account/components/shared/SnackBar";

const accountSideBarMenuReducer = (
  state: AccountSnackBarStore = { alert: null },
  action: AnyAction
): AccountSnackBarStore => {
  switch (action.type) {
    case "SHOW_SNACKBAR":
      const {
        alert: { duration = 3000, variant = VariantsEnum.success, message },
      } = action.payload;
      return { alert: { duration, variant, message } };
    case "HIDE_SNACKBAR":
      return { alert: null };
    default:
      return state;
  }
};
export default accountSideBarMenuReducer;
