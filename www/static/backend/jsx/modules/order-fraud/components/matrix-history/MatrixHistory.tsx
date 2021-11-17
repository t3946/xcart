import React from "react";
import { ColumnLegendData } from "@admin/modules/order-fraud/ts/types/settings";
import { getHeaderClassByName } from "@admin/modules/order-fraud/utils/add-color-column";
import { AddressHistoryText } from "./address-history-text/AddressHistoryText";
interface MatrixHistory {
  historyColumn: ColumnLegendData[];
}
export const MatrixHistory: React.FC<MatrixHistory> = ({ historyColumn }) => {
  return (
    <table border={1}>
      <tr className="history-header">
        <th>Code</th>
        <th style={{ width: 140 }}>Type</th>
        <th style={{ width: 140 }}>Provider</th>
        <th style={{ width: 140 }}>Inferred from [Type]</th>
        <th>Value</th>
        <th style={{ width: 90 }}>Source type</th>
        <th>Inferred by [Source]</th>
      </tr>
      {historyColumn.map((columnData) => (
        <tr>
          <td className={getHeaderClassByName(columnData.columnName)}>
            {columnData.columnName}
          </td>
          <td style={{ width: 200 }}>{columnData.frontendType}</td>
          <td>{columnData.provider}</td>
          <td>{columnData.inferredFrom}</td>
          <td>
            {columnData.link ? (
              columnData.type === "full_name" ? (
                <a href={columnData.linkUrl}>{columnData.value}</a>
              ) : (
                <AddressHistoryText columnData={columnData} />
              )
            ) : (
              columnData.value
            )}
          </td>
          <td className={getHeaderClassByName(columnData.columnName)}>
            {columnData.sourceType}
          </td>
          <td>{columnData.isMelissa && "Melissa"}</td>
        </tr>
      ))}
    </table>
  );
};
