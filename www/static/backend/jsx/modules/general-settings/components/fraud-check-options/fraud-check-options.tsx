import React, { useEffect, useState } from "react";
import { AppBar, Container, Grid, Tab, Typography } from "@material-ui/core";
import { CheckSettingsForm } from "@admin/modules/general-settings/components/fraud-check-options/check-settings-form/check-settings-form";
import { TabContext, TabList, TabPanel } from "@material-ui/lab";
import { TableFraud } from "@admin/modules/general-settings/components/fraud-check-options/table-fraud/table-fraud";
import { setFraudSettings } from "@redux/actions/fraudSettingsActions";
import { useDispatch, useSelector } from "react-redux";
import { StoreGeneralSettings } from "@admin/modules/general-settings/ts/types/general-settings/generalSettings.type";

export const FraudCheckOptions: React.FC<any> = () => {
  const [tabIndex, setTabIndex] = useState<string>(`0`);
  const fraudSettings = useSelector(
    (state: StoreGeneralSettings) => state.fraudSettings
  );
  const dispatch = useDispatch();
  useEffect(() => {
    dispatch(setFraudSettings());
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
            {fraudSettings.faQuestions.full_name && (
              <TableFraud
                data={fraudSettings.faQuestions.full_name.data}
                columns={fraudSettings.faQuestions.full_name.columns}
                type="full_name"
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
            {fraudSettings.faQuestions.address && (
              <TableFraud
                columns={fraudSettings.faQuestions.address.columns}
                data={fraudSettings.faQuestions.address.data}
                type="address"
              />
            )}
          </Grid>
        </TabPanel>
      </TabContext>
    </Container>
  );
};
