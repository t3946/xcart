import { Card as ICard } from "@stripe/stripe-js";
import { date } from "yup/lib/locale";

export const convertDataToEditCardForm = (data: ICard) => {
  return {
    cardHolderName: data.metadata.cardHolderName ?? "",
  };
};
