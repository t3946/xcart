import { transportationTypes } from "@client/modules/account/ts/consts/transportations-types";

export const getDataToTracking = (state: string) => {
  const stateIndex = transportationTypes.findIndex((value) => value === state);

  const data = { items: null, lineWidth: null };

  data.items = transportationTypes.map((_, index) => {
    const roundItemProps = {
      containerClass: null,
      roundStyle: null,
      date: null,
    };
    if (stateIndex === index) {
      roundItemProps.containerClass =
        "order-tracking-line-round-container__this-state";

      data.lineWidth =
        index === 4 ? { width: "100%" } : { width: `${index * 25 + 12.5}%` };
    }
    if (stateIndex > index) {
      roundItemProps.containerClass =
        "order-tracking-line-round-container__completed-state";
    }
    if (stateIndex >= index) {
      roundItemProps.date = new Date().toLocaleDateString("en-EN", {
        month: "long",
        day: "2-digit",
        year: "numeric",
        hour: "numeric",
        minute: "numeric",
      });
    }
    if (index !== 0 && index !== 4) {
      roundItemProps.roundStyle = {
        left: `${25 * index}%`,
        transform: "translate(-50%, 0)",
      };
    }
    return roundItemProps;
  });

  return data;
};
