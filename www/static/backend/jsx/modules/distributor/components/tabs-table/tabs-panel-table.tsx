import React, { Fragment } from "react";
import { TablePrice } from "@admin/modules/distributor/components/table-price/table-price";
import { TabPanel } from "@material-ui/lab";
import { IListTablePrice } from "@admin/modules/distributor/ts/types/table-price.types";

export const TabsPanelTable: React.FC<IListTablePrice> = ({
  arTable,
  select,
  checked,
  activeField,
}) => {
  return (
    <Fragment>
      {arTable.map((table, i) => (
        <TabPanel style={{ padding: "12px" }} value={`${i}`}>
          <TablePrice
            arTable={arTable[i]}
            indexTable={i}
            select={select}
            checked={checked}
            activeField={activeField}
          />
        </TabPanel>
      ))}
    </Fragment>
  );
};
