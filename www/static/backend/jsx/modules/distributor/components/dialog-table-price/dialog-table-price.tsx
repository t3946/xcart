import React, { useContext, useEffect, useState } from "react";
import Button from "@material-ui/core/Button";
import Dialog from "@material-ui/core/Dialog";
import DialogActions from "@material-ui/core/DialogActions";
import DialogContent from "@material-ui/core/DialogContent";
import { Grid } from "@material-ui/core";
import LoadingDialog from "@admin/modules/distributor/components/dialog-table-price/loading";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { TabsPanelTable } from "@admin/modules/distributor/components/tabs-table/tabs-panel-table";
import { TabContext } from "@material-ui/lab";
import { TabListTable } from "@admin/modules/distributor/components/tabs-table/tab-list-table";
import Divider from "@mui/material/Divider";
import CloseIcon from "@material-ui/icons/Close";
import axios from "axios";
import {
  ResponsePricesSettings,
  Site,
} from "@admin/modules/distributor/ts/types/table-price.types";
import { Stack } from "@mui/material";
import { StorefrontSelect } from "@admin/modules/distributor/components/storefront-select/StorefrontSelect";
import { SwitchActionProducts } from "@admin/modules/distributor/components/switch-action-products/SwitchActionProducts";

interface IDialogTablePrice {
  pathFile: string;
  arTable: any[];
  state: { get: any; set: any };
  dx: number;
  arTableName: string[];
  folderDx: string;
  closeHandle: () => void;
}

export const DialogTablePrice: React.FC<IDialogTablePrice> = ({
  state,
  arTable,
  pathFile,
  dx,
  arTableName,
  closeHandle,
}) => {
  const api = new ApiService();
  const [select, setSelect] = useState<any>({});
  const [loading, setLoading] = useState(true);
  const [tabIndex, setTabIndex] = useState(`0`);
  const [mainCheck, setMainCheck] = useState<any>({});
  const [valueActive, setValueActive] = useState({});
  const [sites, setSites] = useState<Site[]>(null);
  const [needSend, setNeedSend] = useState(false);
  const [storefront, setStorefront] = useState("");
  const [create, setCreate] = useState(false);
  const { showSnackbar } = useContext(SnackbarContext);

  const onSaveHandler = () => {
    if (create && storefront === "") {
      console.log(select);
      showSnackbar("Please, select storefront", "info");
      return;
    }
    const data = JSON.stringify({
      select,
      dx,
      valueActive,
      checkField: mainCheck,
      needSend,
      storefront,
      pathFile,
      create,
    });
    setLoading(true);
    axios
      .post("/api/dx/products-price/save", data)
      .then((response) => {
        if (response.data) {
          state.set(false);
          showSnackbar(
            `You successfully uploaded the file, within 5 minutes information about the download will appear in the log`,
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
    api.get(`/api/dx/column/get/${dx}`).then((res: ResponsePricesSettings) => {
      setSelect(res.column);
      setValueActive(res.for_sale_value);
      setSites(res.sites);
    });
  }, []);
  /* Затирание не нужных сохранённых столбцов */
  useEffect(() => {
    arTable.forEach((table, indexTable) => {
      const columnsTable = table[0].map((item, key) => key);
      if (select[indexTable]) {
        const arData = [];
        Object.keys(select[indexTable]).forEach((indexColumn) => {
          if (columnsTable.includes(Number(indexColumn))) {
            arData[indexColumn] = select[indexTable][indexColumn];
          }
        });
        setSelect((prev) => ({
          ...prev,
          ...{
            [indexTable]: arData,
          },
        }));
      }
    });
  }, [arTable]);
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

  return (
    <Dialog
      fullWidth={true}
      maxWidth="xl"
      open={state.get}
      aria-labelledby="max-width-dialog-title"
    >
      <TabContext value={tabIndex}>
        <div className="close-dialog__button">
          <CloseIcon cursor="pointer" fontSize="medium" onClick={closeHandle} />
        </div>
        {!loading && (
          <Stack justifyContent="center">
            {sites && (
              <StorefrontSelect
                sites={sites}
                handleChange={(e: React.ChangeEvent<HTMLSelectElement>) =>
                  setStorefront(e.target.value)
                }
                storefront={storefront}
              />
            )}
            <SwitchActionProducts
              state={create}
              onChange={(e) => setCreate(e.target.checked)}
              text="Create new products?"
            />
            <SwitchActionProducts
              state={needSend}
              onChange={(e) => setNeedSend(e.target.checked)}
              text="Disable active products?"
            />
          </Stack>
        )}
        <DialogContent>
          <Divider />
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
