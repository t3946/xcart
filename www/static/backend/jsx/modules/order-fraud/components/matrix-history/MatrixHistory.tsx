import React from "react";
import { Grid } from "@material-ui/core";
import { ColumnLegendData } from "@admin/modules/order-fraud/ts/types/settings";
import { getHeaderClassByName } from "@admin/modules/order-fraud/utils/add-color-column";
interface MatrixHistory {
  historyColumn: ColumnLegendData[];
}
export const MatrixHistory: React.FC<MatrixHistory> = ({ historyColumn }) => {
  console.log(historyColumn);
  return (
    <div className="history-column-info">
      <Grid
        container
        alignItems="center"
        justifyContent="center"
        direction="column"
      >
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
                <a href={columnData.linkUrl}>{columnData.value}</a>
              </span>
            </div>
          </div>
        ))}
      </Grid>
    </div>
  );
};
