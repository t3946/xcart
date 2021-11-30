import { allMonths } from "@modules/account/ts/consts/all-months";

export const fillingMassForMonths = () => {
  return Array.from({ length: 12 }, (v, k) => k).map((e, index) => {
    return {
      value: index + 1,
      viewValue: allMonths[index],
    };
  });
};
