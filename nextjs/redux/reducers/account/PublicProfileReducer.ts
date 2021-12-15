import { AnyAction } from "redux";
import { AccountPublicProfileStore } from "@modules/account/ts/types/store.type";

const initialState = {
  alert: null,
};

const PublicProfileReducer = (
  state: AccountPublicProfileStore = initialState,
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