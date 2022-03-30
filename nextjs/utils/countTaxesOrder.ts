export function getTaxesGroup(group: Record<any, any>): Record<string, number> {
  const taxes: any = {};

  for (const tax of group.xcart_order_group_taxes) {
    const value = parseFloat(tax.value);
    const name = tax.xcart_tax_rates.xcart_taxes.tax_name;

    taxes[name] = value;
  }

  return taxes;
}

export function countTaxesOrder(
  order: Record<any, any>
): Record<string, number> {
  const taxes: any = {};

  for (const group of order.groups) {
    const groupTaxes = getTaxesGroup(group);

    for (const name in groupTaxes) {
      const value = groupTaxes[name];

      if (!taxes[name]) {
        taxes[name] = 0;
      }

      taxes[name] += value;
    }
  }

  return taxes;
}

export default {
  getTaxesGroup,
  countTaxesOrder,
};
