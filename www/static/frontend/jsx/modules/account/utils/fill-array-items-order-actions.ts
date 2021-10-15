export const fillArrayItemsOnOrderActions = (productAmount: string) => {
  return Array(Number(productAmount))
    .fill(null)
    .map((_, index) => ({ value: index + 1, viewValue: index + 1 }));
};
