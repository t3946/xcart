import { UserStore } from "../../../modules/account/ts/types/user-store.type";

export const userClearAction = (): any => ({
  type: "USER_CLEAR",
});

export const userSetAction = (user: UserStore): any => ({
  type: "USER_SET",
  user,
});
