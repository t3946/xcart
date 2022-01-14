export const getDataToTracking = (state: string, vertical: boolean) => {
  const deliveryStatus = [
    { codes: ["T", "K", "M", "E", "DP"], label: "Ordered" },
    { codes: ["C", "L", "DA", "B", "G"], label: "Dispatched" },
    { codes: ["S"], label: "Shipped" },
    { codes: [], label: "Out for delivery" },
    { codes: ["Z"], label: "Delivered" },
  ];

  const data = { items: null, lineWidth: null };

  let isFind = false;

  data.items = deliveryStatus.map((e, index) => {
    const roundItemProps = {
      containerClass: {},
      roundStyle: null,
      date: null,
      label: e.label,
    };
    if (e.codes.find((e) => e === state)) {
      roundItemProps.containerClass = { current: true };

      data.lineWidth =
        index === 4
          ? { [vertical ? "height" : "width"]: "100%" }
          : { [vertical ? "height" : "width"]: `${index * 25 + 12.5}%` };

      isFind = true;
    }
    if (!isFind) {
      roundItemProps.containerClass = { completed: true };
    }
    if (!isFind || e.codes.find((e) => e === state)) {
      roundItemProps.date = new Date().toLocaleDateString("en-EN", {
        month: "short",
        day: "2-digit",
        year: "numeric",
        hour: "numeric",
        minute: "numeric",
      });
    }
    if (index !== 0 && index !== 4) {
      roundItemProps.roundStyle = {
        [vertical ? "top" : "left"]: `${
          vertical ? 100 - 25 * index : 25 * index
        }%`,
        transform: "translate(-50%, 0)",
      };
    }
    return roundItemProps;
  });
  if (vertical) {
    data.items = data.items.reverse();
  }

  return data;
};
