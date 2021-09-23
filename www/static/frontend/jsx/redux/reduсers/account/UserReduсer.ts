import { AnyAction } from "redux";
import { UserStore } from "../../../modules/account/ts/types/user-store.type";
import { accountUserInitialValue } from "../../../modules/account/ts/consts/account-store-initial-value";

const UserReducer = (
  state: UserStore = accountUserInitialValue,
  action: AnyAction
): UserStore => {
  switch (action.type) {
    case "USER_CLEAR":
      return null;
    case "USER_SET":
      return action.user;
    default:
      return state;
  }
};

export default UserReducer;
