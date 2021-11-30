import { CardItemDto } from "@modules/account/ts/types/wallet.type";

export const convertDataToEditCardForm = (data: CardItemDto) => {
  const expires = new Date(Number(data.expires));

  let viewMonth = expires.getMonth().toString();

  if (viewMonth.length === 1) {
    viewMonth = "0" + viewMonth;
  }

  return {
    ...data,
    expiresMonth: {
      value: expires.getMonth(),
      viewValue: viewMonth,
    },
    expiresYear: {
      value: expires.getFullYear(),
      viewValue: expires.getFullYear(),
    },
  };
};
