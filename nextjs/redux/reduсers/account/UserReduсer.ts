import { AnyAction } from "redux";
import { UserStore } from "@modules/account/ts/types/user-store.type";

const UserReducer = (
  state: UserStore | null = null,
  action: AnyAction
): UserStore | null => {
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
