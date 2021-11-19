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
        <a className="value-link" href={columnData.linkUrl}>{`${
          columnData.value["city"]
        }, ${columnData.value["state"]} ${columnData.value["zipcode"]} ${
          columnData.value["country"] ? `,${columnData.value["country"]}` : ""
        }`}</a>
      ) : (
        columnData.value
      )}
    </Fragment>
  );
};
