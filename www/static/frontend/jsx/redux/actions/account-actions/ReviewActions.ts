export const createReviewAction = (data: Record<any, any>): any => ({
  type: "CREATE_REVIEW",
  data,
});

export const getVideoHeaderAction = (data: Record<any, any>): any => ({
  type: "GET_VIDEO_HEADERS",
  data,
});
