export const getCards = (): any => ({
  type: "GET_CARDS",
});

export const changeDefaultCard = (id: number): any => ({
  type: "CHANGE_DEFAULT_CARD",
  id,
});

export const addDataFromSubmitCardForm = (data: any): any => ({
  type: "ADD_SUBMIT_DATA",
  data,
});

export const addCard = (cardInfo: any, onRequestEnd): any => ({
  type: "ADD_CARD",
  cardInfo,
  onRequestEnd,
});
