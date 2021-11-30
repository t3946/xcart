import { SelectValue } from "@modules/account/ts/types/select-value.type";

export const getDaysForSelect = (
  month: number
): SelectValue<number, number>[] => {
  return Array.from(
    {
      length: new Date(new Date().getFullYear(), month, 0).getDate(),
    },
    (v, k) => k
  ).map((e) => {
    return {
      value: e,
      viewValue: e + 1,
    };
  });
};
