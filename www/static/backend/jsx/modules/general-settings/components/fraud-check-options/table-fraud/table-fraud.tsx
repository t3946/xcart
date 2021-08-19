import React, { useContext, useState } from "react";
import { Button, Grid } from "@material-ui/core";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { TableDataResponse } from "@admin/modules/general-settings/ts/types/fraud-check/data-table";
import { ResponseFraudSave } from "@admin/modules/general-settings/ts/types/fraud-check/response";

interface ITableFraud {
  columns: string[];
  data: TableDataResponse[];
}
const api = new ApiService();
export const TableFraud: React.FC<ITableFraud> = ({ columns, data }) => {
  const [change, setOnChange] = useState("");
  const [dataChange, setDataChange] = useState({});
  const { showSnackbar } = useContext(SnackbarContext);

  const checkSection = (columnName: string, index: number): any => {
    if (dataChange[getColumn(columnName, index)] != undefined) {
      return dataChange[getColumn(columnName, index)];
    }
    const el = data.find((fraud) => {
      return fraud.section === getColumn(columnName, index);
    });
    if (el) {
      return el.value ?? "";
    }
    return "Redundant";
  };

  const onDoubleClickHandler = (name: string): void => {
    setOnChange(name);
  };
  const onChangeDataHandler = (event) => {
    setDataChange({
      ...dataChange,
      [event.target.id]: event.target.value,
    });
  };
  const getColumn = (first: string, index: number): string => {
    return `${first}:${columns[index - 1]}`;
  };
  const onSave = () => {
    const frm = new FormData();
    frm.append("update", JSON.stringify(dataChange));
    api.post("/api/fraud/update/weight", frm).then((res: ResponseFraudSave) => {
      if (res.status) {
        showSnackbar("You have successfully updated the data");
      } else if (res.error || !res.status) {
        showSnackbar(`error: ${res.error ?? "unexpected error"}`, "error");
      }
    });
  };
  const onKeyPressInput = (event) => {
    if (["Enter"].includes(event.key)) {
      setOnChange("");
    }
  };
  return (
    <div>
      <table border="1">
        <tr>
          <th className="table-header-fraud-empty">code</th>
          {columns.map((column) => (
            <th className="table-header-fraud">{column}</th>
          ))}
        </tr>
        {columns.map((column) => {
          return (
            <tr>
              {[""].concat(columns).map((col, d) => {
                if (d === 0) {
                  return <td className="table-header-fraud">{column}</td>;
                } else if (column === columns[d - 1]) {
                  return <td>&#10003;</td>;
                } else {
                  return (
                    <td
                      onDoubleClick={() =>
                        onDoubleClickHandler(`${column}:${columns[d - 1]}`)
                      }
                      className="table__change_item"
                    >
                      {change === getColumn(column, d) ? (
                        <input
                          onChange={onChangeDataHandler}
                          id={getColumn(column, d)}
                          value={checkSection(column, d)}
                          onKeyPress={onKeyPressInput}
                          className="input__fraud_edit"
                        />
                      ) : (
                        checkSection(column, d)
                      )}
                    </td>
                  );
                }
              })}
            </tr>
          );
        })}
      </table>
      <div className="btn_save_fraud">
        <Button
          disabled={JSON.stringify(dataChange) === "{}"}
          variant="contained"
          onClick={onSave}
        >
          Save
        </Button>
      </div>
    </div>
  );
};
