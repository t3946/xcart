export const verifyOneTimePasswordAction = (
  payload: Record<any, any>
): any => ({
  type: "PA_VERIFY_ONE_TIME_PASSWORD",
  payload,
});

export const sendEmailAction = (payload: Record<any, any>): any => ({
  type: "PA_SEND_EMAIL",
  payload,
});

export const resetPasswordAction = (payload: Record<any, any>): any => ({
  type: "PA_RESET_PASSWORD",
  payload,
});
