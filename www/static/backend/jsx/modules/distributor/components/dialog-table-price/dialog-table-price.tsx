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
import { TabContext, TabList } from "@material-ui/lab";
import { TabListTable } from "@admin/modules/distributor/components/tabs-table/tab-list-table";
import { validFileData } from "@admin/modules/distributor/components/dialog-table-price/constants";
import CloseIcon from "@material-ui/icons/Close";

interface IDialogTablePrice {
  state: { get: any; set: any };
  arTable: [];
  file: any;
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
  const { showSnackbar } = useContext(SnackbarContext);

  const onSaveHandler = () => {
    if (validFileData(select, mainCheck)) {
      const data = new FormData();
      data.append("file", file);
      data.append("select", JSON.stringify(select));
      data.append("dx", dx);
      data.append("checkField", JSON.stringify(mainCheck));
      setLoading(true);
      api.post("/api/dx/products-price/save", data).then((res: IResponse) => {
        if (res) {
          state.set(false);
          showSnackbar(
            `You have successfully updated ${res.countUpdate} products`,
            "success"
          );
        }
      });
    } else {
      showSnackbar(`Add the selected required field to the list`, "error");
    }
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
    api.get(`/api/dx/column/get/${dx}`).then((res: {}) => {
      setSelect(res);
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
            onClick={() => state.set(false)}
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
