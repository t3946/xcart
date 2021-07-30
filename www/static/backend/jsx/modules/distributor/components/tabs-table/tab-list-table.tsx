import React from "react";
import { AppBar, Tab } from "@material-ui/core";
import { TabList } from "@material-ui/lab";
interface TabListTable {
  tabValue: string;
  handleChange(event: React.ChangeEvent<{}>, newValue: string): void;
  arTableName: [];
}

export const TabListTable: React.FC<TabListTable> = ({
  tabValue,
  handleChange,
  arTableName,
}) => {
  return (
    <AppBar classes={{ colorPrimary: "app-bar__back-color" }} position="static">
      <TabList value={tabValue} onChange={handleChange}>
        {arTableName.map((table, i) => (
          <Tab label={table} value={`${i}`} />
        ))}
      </TabList>
    </AppBar>
  );
};
