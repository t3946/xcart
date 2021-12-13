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

export const uploadLicense = (payload: Record<any, any>): any => ({
  type: "UPLOAD_LICENSE_DECISION",
  payload,
});

export const setDecisionsAction = (decisions: Record<any, any>): any => ({
  type: "SET_DECISIONS",
  decisions,
});

export const payOrderAction = (payload: Record<any, any>): any => ({
  type: "PAY_ORDER_DECISION",
  payload,
});

export const approveIncreaseInShippingChargeAction = (
  payload: Record<any, any>
): any => ({
  type: "APPROVE_INCREASE_IN_SHIPPING_CHARGE_DECISION",
  payload,
});

export const cancelOrderAction = (payload: Record<any, any>): any => ({
  type: "CANCEL_ORDER_DECISION",
  payload,
});

export const checkSentAction = (payload: Record<any, any>): any => ({
  type: "CHECK_SENT_DECISION",
  payload,
});