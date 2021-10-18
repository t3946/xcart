import React, { useState, Fragment } from "react";
import { selectColumn } from "@admin/modules/distributor/components/table-price/constants";
import { SelectField } from "@admin/modules/distributor/components/field-form-price/field-select";
import { Grid, Switch, Tooltip, Typography } from "@material-ui/core";
import { ITablePrice } from "@admin/modules/distributor/ts/types/table-price.types";

export const TablePrice: React.FC<ITablePrice> = ({
  arTable,
  select,
  indexTable,
  checked,
  activeField,
  needSend,
}) => {
  const [pop, setPop] = useState("");

  const issetForSaleField = (numRow: number): boolean => {
    if (select.get[indexTable]) {
      if (select.get[indexTable][numRow] === "for_sale") {
        return true;
      }
    }
    return false;
  };
  const getClassTd = (rowIndex: number) => {
    const classList = ["td-price-form"];
    if (rowIndex === 0) {
      classList.push("td-first-item");
    }
    return classList.join(" ");
  };
  return (
    <Fragment>
      <Grid
        alignItems="center"
        justifyContent="center"
        container
        direction="row"
      >
        <Typography variant="body2">Product code</Typography>
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
      <div>
        <Typography variant="h5" align="center">
          Need send to active products?
        </Typography>
        <Grid
          alignItems="center"
          justifyContent="center"
          container
          direction="row"
        >
          <Typography variant="body2">No</Typography>
          <Switch
            checked={needSend.get === true}
            onChange={(event) => needSend.set(event.target.checked)}
            name="checked"
            inputProps={{
              "aria-label": "secondary checkbox",
              "data-index": indexTable,
            }}
          />
          <Typography variant="body2">Yes</Typography>
        </Grid>
      </div>

      <table className="table__dx-price" id="somethingUnique" cellSpacing="0">
        <thead>
          <tr>
            {arTable[0].map((_, i) => (
              <th style={{ width: 200 }}>
                <SelectField
                  valueList={select.get}
                  indexTable={indexTable}
                  onChange={select.set}
                  index={i}
                  options={selectColumn}
                />
                {issetForSaleField(i) ? (
                  <input
                    value={activeField.get[indexTable] ?? ""}
                    id={indexTable.toString()}
                    onChange={(event) => activeField.set(event)}
                  />
                ) : (
                  <div style={{ height: "26px" }} />
                )}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
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
                    className={getClassTd(i)}
                  >
                    {el}
                  </td>
                </Tooltip>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </Fragment>
  );
};
