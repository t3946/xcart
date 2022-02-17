export const getDataToTracking = (
  statuses: {
    id: number;
    group_id: number;
    status: string;
    old_status: string;
    updated: string;
  }[],
  vertical: boolean
) => {
  const deliveryStatus = [
    { codes: ["T", "K", "M", "E", "DP"], label: "Ordered" },
    { codes: ["C", "L", "DA", "B", "G"], label: "Dispatched" },
    { codes: ["S"], label: "Shipped" },
    { codes: ["OD"], label: "Out for delivery" },
    { codes: ["Z"], label: "Delivered" },
  ];

  const data = { items: null, lineWidth: null };

  data.items = deliveryStatus.map((e, index) => {
    const roundItemProps = {
      containerClass: {},
      roundStyle: null,
      date: null,
      label: e.label,
    };
    const status = statuses.find((s) => e.codes.includes(s.status));
    if (status) {
      if (statuses.lastIndexOf(status) === statuses.length - 1) {
        roundItemProps.containerClass.current = true;
      }
      data.lineWidth =
        index === 4
          ? { [vertical ? "height" : "width"]: "100%" }
          : { [vertical ? "height" : "width"]: `${index * 25 + 12.5}%` };

      roundItemProps.containerClass.completed = true;
      roundItemProps.date = new Date(status.updated).toLocaleDateString(
        "en-EN",
        {
          month: "short",
          day: "2-digit",
          year: "numeric",
          hour: "numeric",
          minute: "numeric",
        }
      );
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
