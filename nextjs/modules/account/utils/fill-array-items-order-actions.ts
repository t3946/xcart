import { SelectValue } from "@modules/account/ts/types/select-value.type";

export function fillArrayItemsOnOrderActions(
  productAmount: number | string
): SelectValue<number, number>[] {
  const mass = Array(Number(productAmount))
    .fill(null)
    .map((_, index) => ({ value: index + 1, viewValue: index + 1 }));

  mass.unshift({ value: 0, viewValue: 0 });

  return mass;
}
