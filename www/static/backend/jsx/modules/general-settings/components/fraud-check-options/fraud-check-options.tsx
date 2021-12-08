import React, { useEffect, useState } from "react";
import { CheckSettingsForm } from "@admin/modules/general-settings/components/fraud-check-options/check-settings-form/CheckSettingsForm";
import { TableMatrixQuestions } from "@admin/modules/general-settings/components/fraud-check-options/table-matrix-questions/TableMatrixQuestions";
import { setFraudSettings } from "@redux/actions/fraudSettingsActions";
import { useDispatch, useSelector } from "react-redux";
import { StoreGeneralSettings } from "@admin/modules/general-settings/ts/types/general-settings/generalSettings.type";
import { TableBaseQuestions } from "@admin/modules/general-settings/components/fraud-check-options/table-base-questions/TableBaseQuestions";
import { AppBar, Container, Grid, Tab, Typography } from "@mui/material";
import TabContext from "@mui/lab/TabContext";
import TabList from "@mui/lab/TabList";
import TabPanel from "@mui/lab/TabPanel";

export const FraudCheckOptions: React.FC = () => {
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
          <TabList onChange={handleChange} centered>
            {[
              "FC options",
              "FN CCM parameters",
              "A CCM parameters",
              "Base question list",
            ].map((tab, i) => (
              <Tab
                classes={{ selected: "tab-fraud-admin" }}
                label={tab}
                value={i.toString()}
              />
            ))}
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
              FN CCM parameters
            </Typography>
            {fraudSettings.faQuestions.full_name && (
              <TableMatrixQuestions
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
              A CCM parameters
            </Typography>
            {fraudSettings.faQuestions.address && (
              <TableMatrixQuestions
                columns={fraudSettings.faQuestions.address.columns}
                data={fraudSettings.faQuestions.address.data}
                type="address"
              />
            )}
          </Grid>
        </TabPanel>
        <TabPanel value="3">
          <Grid
            container
            justifyContent="center"
            alignItems="center"
            direction="column"
          >
            <Typography variant="h6" align="center">
              Base fraud check question
            </Typography>
            {fraudSettings.baseQuestions && <TableBaseQuestions />}
          </Grid>
        </TabPanel>
      </TabContext>
    </Container>
  );
};
