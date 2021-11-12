import React, { useContext, useEffect, useState } from "react";
import Button from "@material-ui/core/Button";
import Dialog from "@material-ui/core/Dialog";
import DialogActions from "@material-ui/core/DialogActions";
import DialogContent from "@material-ui/core/DialogContent";
import { Grid, Typography } from "@material-ui/core";
import LoadingDialog from "@admin/modules/distributor/components/dialog-table-price/loading";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { TabsPanelTable } from "@admin/modules/distributor/components/tabs-table/tabs-panel-table";
import { TabContext } from "@material-ui/lab";
import { TabListTable } from "@admin/modules/distributor/components/tabs-table/tab-list-table";
import CloseIcon from "@material-ui/icons/Close";
import axios from "axios";

interface IDialogTablePrice {
  state: { get: any; set: any };
  arTable: [];
  file: { get: any; set: any };
  dx: number;
  arTableName: [];
}
interface IResponse {
  countUpdate: number;
  error?: string;
  status: boolean;
}

export const DialogTablePrice: React.FC<IDialogTablePrice> = ({
  state,
  arTable,
  file,
  dx,
  arTableName,
}) => {
  const api = new ApiService();
  const [select, setSelect] = useState<any>({});
  const [loading, setLoading] = useState(true);
  const [tabIndex, setTabIndex] = useState(`0`);
  const [mainCheck, setMainCheck] = useState<any>({});
  const [valueActive, setValueActive] = useState({});
  const [needSend, setNeedSend] = useState(false);
  const { showSnackbar } = useContext(SnackbarContext);

  const onSaveHandler = () => {
    const data = new FormData();
    data.append("file", file.get);
    data.append("select", JSON.stringify(select));
    data.append("dx", dx);
    data.append("active_value", JSON.stringify(valueActive));
    data.append("checkField", JSON.stringify(mainCheck));
    data.append("need_send", needSend);
    setLoading(true);
    axios
      .post("/api/dx/products-price/save", data)
      .then((response) => {
        if (response.data) {
          state.set(false);
          showSnackbar(
            `You have successfully updated ${response.data.countUpdate} products`,
            "success"
          );
        }
      })
      .catch((error) => {
        state.set(false);
        showSnackbar(error.response.data.message, "error");
      });
  };

  const onChangeSelectHandler = (
    event: React.ChangeEvent<HTMLSelectElement>
  ) => {
    const indexTable = event.target.dataset.indexTable;
    if (select[indexTable]) {
      if (select[indexTable][event.target.id] && event.target.value === "") {
        setSelect((prevState) => {
          delete prevState[indexTable][event.target.id];
          return { ...prevState };
        });
        return;
      }
    }
    setSelect((prev) => ({
      ...prev,
      ...{
        [indexTable]: {
          ...prev[indexTable],
          ...{ [event.target.id]: event.target.value },
        },
      },
    }));
  };
  useEffect(() => {
    if (arTable.length) {
      setLoading(false);
      arTable.forEach((i_table, i) => {
        setMainCheck((prev) => ({ ...prev, ...{ [i]: "productcode" } }));
      });
    }
  }, [arTable]);
  useEffect(() => {
    api
      .get(`/api/dx/column/get/${dx}`)
      .then((res: { column: any; for_sale_value: any }) => {
        setSelect(res.column);
        setValueActive(res.for_sale_value);
      });
  }, []);
  const handleTabChange = (event: React.ChangeEvent<{}>, newValue: string) => {
    setTabIndex(newValue);
  };
  const handleCheckedChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const indexTable = event.target.dataset.index;
    setMainCheck((prev) => ({
      ...prev,
      ...{ [indexTable]: !event.target.checked ? "productcode" : "upc" },
    }));
  };
  const onChangeForSaleFieldValue = (
    event: React.ChangeEvent<HTMLInputElement>
  ) => {
    setValueActive((prev) => ({
      ...prev,
      [event.target.id]: event.target.value,
    }));
  };

  const onCloseDialog = () => {
    file.set(null);
    state.set(false);
  };

  return (
    <Dialog
      fullWidth={true}
      maxWidth="xl"
      open={state.get}
      aria-labelledby="max-width-dialog-title"
    >
      <TabContext value={tabIndex}>
        <div className="close-dialog__button">
          <CloseIcon
            cursor="pointer"
            fontSize="medium"
            onClick={onCloseDialog}
          />
        </div>
        <Typography align="center" variant="h6">
          Price List
        </Typography>
        <DialogContent>
          {!loading && arTable.length ? (
            <TabsPanelTable
              arTable={arTable}
              select={{ get: select, set: onChangeSelectHandler }}
              checked={{ get: mainCheck, set: handleCheckedChange }}
              activeField={{ get: valueActive, set: onChangeForSaleFieldValue }}
              needSend={{ get: needSend, set: setNeedSend }}
            />
          ) : (
            <LoadingDialog />
          )}
        </DialogContent>
        <DialogActions>
          <Grid
            container
            alignItems="center"
            direction="column"
            justifyContent="center"
          >
            {!loading && (
              <TabListTable
                tabValue={tabIndex}
                handleChange={handleTabChange}
                arTableName={arTableName}
              />
            )}
            <Button disabled={loading} color="primary" onClick={onSaveHandler}>
              Save
            </Button>
          </Grid>
        </DialogActions>
      </TabContext>
    </Dialog>
  );
};
