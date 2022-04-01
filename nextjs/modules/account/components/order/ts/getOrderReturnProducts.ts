// получить список продуктов, которые могут быть возвращены
export const getOrderReturnProducts = (groups) => {
  console.log({ groups });

  return groups.reduce((products, group) => {
    let shippedStatus;

    for (const statusesHistoryElement of group.xcart_order_statuses_history) {
      if (statusesHistoryElement.status === "Z") {
        shippedStatus = statusesHistoryElement;
        break;
      }
    }

    if (shippedStatus) {
      const timeOneDay = 1000 * 60 * 60 * 24;
      const timeDelivered = new Date(shippedStatus.updated).getTime();
      const dateEndReturn = new Date(timeDelivered + timeOneDay * 14);

      if (dateEndReturn < new Date()) {
        return products;
      }
    }

    return products.concat(group.products);
  }, []);
};

// получить список продуктов, которые могут быть отменены
export const getOrderCancelProducts = (groups) => {
  return groups.reduce((products, group) => {
    let dispatched = false;
    const dispatchStatuses = ["T", "K", "M", "E", "DP"];

    for (const statusesHistoryElement of group.xcart_order_statuses_history) {
      if (dispatchStatuses.indexOf(statusesHistoryElement.status) !== -1) {
        dispatched = true;
        break;
      }
    }

    //не выводить продукты, если прошли диспетчеризацию
    if (dispatched) {
      return products;
    }

    return products.concat(group.products);
  }, []);
};
