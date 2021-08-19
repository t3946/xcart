import React, { useEffect, useState } from "react";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { AppBar, Container, Grid, Tab, Typography } from "@material-ui/core";
import { TableFraud } from "@admin/modules/general-settings/components/fraud-check-options/table-fraud/table-fraud";
import { CheckSettingsForm } from "@admin/modules/general-settings/components/fraud-check-options/check-settings-form/check-settings-form";
import { TabContext, TabList, TabPanel } from "@material-ui/lab";
import { TableDataResponse } from "@admin/modules/general-settings/ts/types/fraud-check/data-table";

const api = new ApiService();

interface FraudsTableData {
  address?: { data: TableDataResponse[]; columns: string[] };
  full_name?: { data: TableDataResponse[]; columns: string[] };
}

interface ResponseTableData extends FraudsTableData {
  status: boolean;
}

export const FraudCheckOptions: React.FC<any> = () => {
  const [frauds, setFrauds] = useState<FraudsTableData>({});
  const [tabIndex, setTabIndex] = useState<string>(`0`);

  useEffect(() => {
    api.get("/api/fraud/get/all").then((res: ResponseTableData) => {
      if (res.status) {
        delete res.status;
        setFrauds(res);
      }
    });
  }, []);
  const handleChange = (event, newValue) => {
    setTabIndex(newValue);
  };

  return (
    <Container>
      <TabContext value={tabIndex}>
        <AppBar
          classes={{ colorPrimary: "tab-fraud__app-bar" }}
          position="static"
        >
          <TabList
            onChange={handleChange}
            centered
            aria-label="simple tabs example"
          >
            <Tab label="Check options settings" value="0" />
            <Tab label="Table full name" value="1" />
            <Tab label="Table address" value="2" />
          </TabList>
        </AppBar>
        <TabPanel value="0">
          <CheckSettingsForm />
        </TabPanel>
        <TabPanel value="1">
          <Grid
            container
            justifyContent="center"
            alignItems="center"
            direction="row"
          >
            <Typography variant="h6" align="center">
              Table full name fraud
            </Typography>
            {frauds.full_name && (
              <TableFraud
                data={frauds.full_name.data}
                columns={frauds.full_name?.columns}
              />
            )}
          </Grid>
        </TabPanel>
        <TabPanel value="2">
          <Grid
            container
            justifyContent="center"
            alignItems="center"
            direction="column"
          >
            <Typography variant="h6" align="center">
              Table address fraud
            </Typography>
            {frauds.address && (
              <TableFraud
                columns={frauds.address.columns}
                data={frauds.address.data}
              />
            )}
          </Grid>
        </TabPanel>
      </TabContext>
    </Container>
  );
};
