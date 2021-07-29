export const register = (payload: any): any => ({
  type: "ACCOUNT_REGISTER",
  payload,
});

export const loginAction = (payload: any): any => ({
  type: "ACCOUNT_LOGIN",
  payload,
});

export const logoutAction = (payload: any): any => ({
  type: "ACCOUNT_LOGOUT",
  payload,
});
