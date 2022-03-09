export const getRatingsAndReviewsAction = (payload: Record<any, any>): any => ({
  type: "GET_PRODUCT_RATINGS_AND_REVIEWS",
  payload,
});

export const markHelpfulAction = (payload: Record<any, any>): any => ({
  type: "MARK_HELPFUL",
  payload,
});

export const unmarkHelpfulAction = (payload: Record<any, any>): any => ({
  type: "UNMARK_HELPFUL",
  payload,
});

export const getReviewsAction = (payload: Record<any, any>): any => ({
  type: "GET_REVIEWS",
  payload,
});

export const addReviewsAction = (payload: Record<any, any>): any => ({
  type: "ADD_REVIEWS",
  payload,
});

export const clearReviewsAction = (payload: Record<any, any>): any => ({
  type: "CLEAR_REVIEWS",
  payload,
});
