//todo: looks like file some congested, need refactoring maybe
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

export const getAction = (payload: Record<any, any>): any => ({
  type: "GET_DECISIONS",
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

export const uploadOriginalPurchaseOrderAction = (
  payload: Record<any, any>
): any => ({
  type: "UPLOAD_ORIGINAL_PURCHASE_ORDER_DECISION",
  payload,
});

export const iSentOriginalPurchaseOrderViaFaxAction = (
  payload: Record<any, any>
): any => ({
  type: "I_SENT_ORIGINAL_PURCHASE_ORDER_VIA_FAX_DECISION",
  payload,
});

export const sentAchTransferAction = (payload: Record<any, any>): any => ({
  type: "SENT_ACH_TRANSFER_DECISION",
  payload,
});

export const payOrderByCardAction = (payload: Record<any, any>): any => ({
  type: "PAY_ORDER_BY_CARD_DECISION",
  payload,
});

export const payOrderByPaypalAction = (payload: Record<any, any>): any => ({
  type: "PAY_ORDER_BY_PAYPAL_DECISION",
  payload,
});

export const submitResponsibilityForCustomDutiesAction = (
  payload: Record<any, any>
): any => ({
  type: "SUBMIT_RESPONSIBILITY_FOR_CUSTOM_DUTIES_DECISION",
  payload,
});

export const formAnswersLTLFreightShipmentAction = (
  payload: Record<any, any>
): any => ({
  type: "FORM_ANSWERS_LTL_FREIGHT_SHIPMENT_DECISION",
  payload,
});

export const poAdditionalInformationRequiredAction = (
  payload: Record<any, any>
): any => ({
  type: "PO_ADDITIONAL_INFORMATION_REQUIRED_DECISION",
  payload,
});

export const submitAlternativeItemsOffer = (
  payload: Record<any, any>
): any => ({
  type: "SUBMIT_ALTERNATIVE_ITEMS_OFFER_DECISION",
  payload,
});
