export const headerItems = [
  {
    label: "Order tracking",
    to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/order-tracking`,
  },
  {
    label: "Products ordered",
    to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/products-ordered`,
  },
  {
    label: "Addresses and contacts",
    to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/addresses`,
  },
  {
    label: "Order actions",
    to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/order-actions`,
  },
  {
    label: "Order communication",
    to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/communication`,
  },
  {
    label: "Order log",
    to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/log`,
  },
];
