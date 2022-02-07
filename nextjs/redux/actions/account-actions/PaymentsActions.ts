import { SubmitFormDataDto } from "../../../modules/account/ts/types/wallet.type";

export const getCards = (): any => ({
  type: "GET_CARDS",
});

export const changeDefaultCard = (payload: any): any => ({
  type: "CHANGE_DEFAULT_CARD",
  payload,
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

export const addCardSaga = (payload: any): any => ({
  type: "ADD_CARD_SAGA",
  payload,
});

export const deleteCard = (payload: any): any => ({
  type: "DELETE_CARD",
  payload,
});

export const changeAddressCard = (payload: {
  addressId: number;
  cardId: string;
  success: (res) => void;
}): any => ({
  type: "CHANGE_ADDRESS_CARD",
  payload,
});

export const changeCardHolderName = (payload: {
  cardHolderName: string;
  cardId: string;
  success: (res) => void;
}): any => ({
  type: "CHANGE_CARDHOLDER_NAME",
  payload,
});
