export const sendFeedback = (payload: Record<any, any>): any => ({
  type: "SEND_FEEDBACK",
  payload,
});
