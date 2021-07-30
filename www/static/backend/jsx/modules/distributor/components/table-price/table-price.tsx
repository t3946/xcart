import React, { useState } from "react";
import { selectColumn } from "@admin/modules/distributor/components/table-price/constants";
import { SelectField } from "@admin/modules/distributor/components/field-form-price/field-select";
import { Grid, Switch, Tooltip, Typography } from "@material-ui/core";

interface ITablePrice {
  arTable: [];
  select: { get: any; set: any };
  indexTable: number;
  checked: { get: any; set: any };
}

export const TablePrice: React.FC<ITablePrice> = ({
  arTable,
  select,
  indexTable,
  checked,
}) => {
  const [pop, setPop] = useState("");

  return (
    <>
      <Grid
        alignItems="center"
        justifyContent="center"
        container
        direction="row"
      >
        <Typography variant="body2">Productcode</Typography>
        <Switch
          checked={checked.get[indexTable] !== "productcode"}
          onChange={checked.set}
          name="checked"
          inputProps={{
            "aria-label": "secondary checkbox",
            "data-index": indexTable,
          }}
        />
        <Typography variant="body2">UPC</Typography>
      </Grid>

      <table className="table__dx-price" id="somethingUnique" cellSpacing="0">
        <tr>
          {arTable[0].map((cell, i) => (
            <th style={{ width: 200 }}>
              <SelectField
                valueList={select.get}
                indexTable={indexTable}
                onChange={select.set}
                index={i}
                options={selectColumn}
              />
            </th>
          ))}
        </tr>
        {arTable.map((row, i) => (
          <tr>
            {row.map((el, index) => (
              <Tooltip
                classes={{ tooltip: "pop-menu__table-dx" }}
                title={el}
                open={pop === `${i}.${index}`}
              >
                <td
                  onDoubleClick={() => setPop(`${i}.${index}`)}
                  className="td-price-form"
                >
                  {el}
                </td>
              </Tooltip>
            ))}
          </tr>
        ))}
      </table>
    </>
  );
};
