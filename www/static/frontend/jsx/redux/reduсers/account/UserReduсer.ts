import { AnyAction } from "redux";
import { UserStoreDto } from "../../../modules/account/ts/types/user-store.type";
import { accountUserInitialValue } from "../../../modules/account/ts/consts/account-store-initial-value";

const UserReducer = (
  state: UserStoreDto = accountUserInitialValue,
  action: AnyAction
): UserStoreDto => {
  switch (action.type) {
    default:
      return state;
  }
};

export default UserReducer;
