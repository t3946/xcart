import React, { Fragment } from "react";
import { TablePrice } from "@admin/modules/distributor/components/table-price/table-price";
import { TabPanel } from "@material-ui/lab";

interface ITabsPanelTable {
  arTable: [];
  select: { get: any; set: any };
  checked: { get: any; set: any };
  activeField: {
    get: any;
    set: (event: React.ChangeEvent<HTMLInputElement>) => void;
  };
}

export const TabsPanelTable: React.FC<ITabsPanelTable> = ({
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
