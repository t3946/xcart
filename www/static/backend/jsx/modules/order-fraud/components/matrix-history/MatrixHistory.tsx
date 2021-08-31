import React from "react";
import { Grid } from "@material-ui/core";
import { ColumnLegendData } from "@admin/modules/order-fraud/ts/types/settings";
interface MatrixHistoryFA {
  historyColumn: ColumnLegendData[];
}
export const MatrixHistory: React.FC<MatrixHistoryFA> = ({ historyColumn }) => {
  return (
    <Grid
      container
      alignItems="center"
      justifyContent="center"
      direction="column"
    >
      {historyColumn.map((columnData) => (
        <div className="history-item-column">
          <div className="table-item-wrapper-red column-item-history">
            {columnData.columnName}
          </div>
          <span>{columnData.description}</span>
          <span className="link-google-info-history">
            <a href="#">{columnData.value}</a>
          </span>
        </div>
      ))}
    </Grid>
  );
};
