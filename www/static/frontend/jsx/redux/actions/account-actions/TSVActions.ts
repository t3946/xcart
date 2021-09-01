export const confirmCodeAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_TSV_CONFIRM_CODE",
  payload,
});

export const disableAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_TSV_DISABLE",
  payload,
});
