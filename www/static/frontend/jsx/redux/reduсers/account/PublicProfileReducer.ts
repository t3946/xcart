import { AnyAction } from "redux";
import { publicProfileInitialValue } from "@client/modules/account/ts/consts/account-store-initial-value";
import { AccountPublicProfileStore } from "@client/modules/account/ts/types/account-store.type";

const PublicProfileReducer = (
  state: AccountPublicProfileStore = publicProfileInitialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "ACCOUNT_SET_ALERT_PUBLIC_PROFILE":
      state.alert = action.alert;
      return { ...state };
    default:
      return state;
  }
};

export default PublicProfileReducer;
