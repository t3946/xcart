export const solveDecisionAction = (payload: Record<any, any>): any => ({
  type: "SOLVE_DECISION",
  payload,
});

export const resetAction = (): any => ({
  type: "RESET_DECISION",
});

export const addAction = (decisions: Record<any, any>): any => ({
  type: "ADD_DECISION",
  decisions,
});

export const loadMoreAction = (payload: Record<any, any>): any => ({
  type: "LOAD_MORE_DECISION",
  payload,
});

export const getEtaProductsAction = (payload: Record<any, any>): any => ({
  type: "GET_ETA_PRODUCTS_DECISION",
  payload,
});
