import React, { Fragment } from "react";
import { ColumnLegendData } from "@admin/modules/order-fraud/ts/types/settings";
interface AddressHistoryText {
  columnData: ColumnLegendData;
}
export const AddressHistoryText: React.FC<AddressHistoryText> = ({
  columnData,
}) => {
  return (
    <Fragment>
      {columnData.value !== "N/A" ? (
        <a target="_blank" className="value-link" href={columnData.linkUrl}>
          {Object.values(columnData.value).join(", ")}
        </a>
      ) : (
        columnData.value
      )}
    </Fragment>
  );
};
