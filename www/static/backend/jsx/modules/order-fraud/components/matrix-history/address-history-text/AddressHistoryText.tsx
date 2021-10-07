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
        <div className="matrix-legend-address-value">
          <div className="legend-address-value legend-city-info">
            <a href={columnData.linkUrl}>{columnData.value["city"]}</a>
          </div>
          <div className="legend-address-value legend-state-info">
            <a href={columnData.linkUrl}>{columnData.value["state"]}</a>
          </div>
          <div className="legend-address-value legend-zip-info">
            <a href={columnData.linkUrl}>{columnData.value["zipcode"]}</a>
          </div>
        </div>
      ) : (
        columnData.value
      )}
    </Fragment>
  );
};
