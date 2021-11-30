export const verifyOneTimePasswordAction = (
  payload: Record<any, any>
): any => ({
  type: "PA_VERIFY_ONE_TIME_PASSWORD",
  payload,
});

export const sendOneTimePasswordAction = (payload: Record<any, any>): any => ({
  type: "PA_SEND_ONE_TIME_PASSWORD",
  payload,
});

export const resetPasswordAction = (payload: Record<any, any>): any => ({
  type: "PA_RESET_PASSWORD",
  payload,
});
