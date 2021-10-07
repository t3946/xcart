import React from "react";
import { TablePrice } from "@admin/modules/distributor/components/table-price/table-price";
import { TabPanel } from "@material-ui/lab";

interface ITabsPanelTable {
  arTable: [];
  select: { get: any; set: any };
  checked: { get: any; set: any };
}

export const TabsPanelTable: React.FC<ITabsPanelTable> = ({
  arTable,
  select,
  checked,
}) => {
  return (
    <>
      {arTable.map((table, i) => {
        return (
          <TabPanel style={{ padding: "12px" }} value={`${i}`}>
            <TablePrice
              arTable={arTable[i]}
              indexTable={i}
              select={select}
              checked={checked}
            />
          </TabPanel>
        );
      })}
    </>
  );
};
