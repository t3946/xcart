import React, { Fragment } from "react";
import { ColumnLegendData } from "@admin/modules/order-fraud/ts/types/settings";
interface AddressHistoryText {
  columnData: ColumnLegendData;
}
export const AddressHistoryText: React.FC<AddressHistoryText> = ({
  columnData,
}) => {
  /* TODO: В будущем переписать, поскольку непонятные перебросы строк */
  const formatAddress = () => {
    let result = "";
    ["street1", "street2", "city", "state", "zipcode", "country"].forEach(
      (attr) => {
        if (attr === "street2" && columnData.value[attr]) {
          result += "<br/>";
        }
        if (columnData.value[attr]) {
          result += `${columnData.value[attr]} `;
        }
        if (attr === "street2" && columnData.value["street1"]) {
          result += "<br/>";
        }
      }
    );
    return result.trim();
  };
  return (
    <Fragment>
      {columnData.value !== "N/A" ? (
        <a
          dangerouslySetInnerHTML={{ __html: formatAddress() }}
          target="_blank"
          className="value-link"
          href={columnData.linkUrl}
        />
      ) : (
        columnData.value
      )}
    </Fragment>
  );
};
