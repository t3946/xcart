import React from "react";
import { Grid } from "@material-ui/core";
import { ColumnLegendData } from "@admin/modules/order-fraud/ts/types/settings";
import { getHeaderClassByName } from "@admin/modules/order-fraud/utils/add-color-column";
import { AddressHistoryText } from "@admin/modules/order-fraud/components/matrix-history/address-history-text/AddressHistoryText";
interface MatrixHistory {
  historyColumn: ColumnLegendData[];
}
export const MatrixHistory: React.FC<MatrixHistory> = ({ historyColumn }) => {
  return (
    <div className="history-column-info">
      <Grid
        container
        alignItems="center"
        justifyContent="center"
        direction="column"
      >
        <div>
          {historyColumn.map((columnData) => (
            <div className="history-item-column">
              <div
                className={`${getHeaderClassByName(
                  columnData.columnName
                )} column-item-history`}
              >
                {columnData.columnName}
              </div>
              <div className="history-item-info">
                <span className="description-item-history">
                  {columnData.description}
                </span>
                <span className="link-google-info-history">
                  {columnData.link ? (
                    columnData.type === "full_name" ? (
                      <a href={columnData.linkUrl}>{columnData.value}</a>
                    ) : (
                      <AddressHistoryText columnData={columnData} />
                    )
                  ) : (
                    columnData.value
                  )}
                </span>
              </div>
            </div>
          ))}
        </div>
      </Grid>
    </div>
  );
};
