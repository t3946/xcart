import { SubmitFormDataDto } from "../../../modules/account/ts/types/wallet.type";

export const getCards = (): any => ({
  type: "GET_CARDS",
});

export const changeDefaultCard = (id: number): any => ({
  type: "CHANGE_DEFAULT_CARD",
  id,
});

export const addDataFromSubmitCardForm = (data: SubmitFormDataDto): any => ({
  type: "ADD_SUBMIT_DATA",
  data,
});

export const addCard = (cardInfo: any, onRequestEnd): any => ({
  type: "ADD_CARD",
  cardInfo,
  onRequestEnd,
});

export const editCard = (cardInfo: any, onRequestEnd): any => ({
  type: "EDIT_CARD",
  cardInfo,
  onRequestEnd,
});

export const removeCard = (id: number, onRequestEnd) => ({
  type: "REMOVE_CARD",
  id,
  onRequestEnd,
});

export const getTransaction = () => ({
  type: "GET_TRANSACTIONS",
});
