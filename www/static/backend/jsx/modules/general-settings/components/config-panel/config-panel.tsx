import React, { useEffect, useState } from "react";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { Collapse, Grid, Typography } from "@material-ui/core";
import { ConfigPanelSection } from "@admin/modules/general-settings/components/config-panel/config-panel-section";
import { HeaderConfigPanel } from "@admin/modules/general-settings/components/config-panel/header-config-panel";

interface IConfig {
  section?: [];
}

const api = new ApiService();
export const ConfigPanel: React.FC<any> = () => {
  const [configList, setConfigList] = useState<IConfig>({});
  const [collapse, setCollapse] = useState(true);
  useEffect(() => {
    api.get("/api/config/get/all").then((result: {}) => {
      if (result) {
        const countItemSection = Math.ceil(result.options.length / 3);
        const countModuleSection = Math.ceil(result.modules.length / 3);
        const startSlice = { module: 0, options: 0 };
        const newConfig = { options: [], modules: [] };
        for (let i = 0; i < 3; i++) {
          newConfig.options[i] = result.options.slice(
            startSlice.options,
            startSlice.options + countItemSection
          );
          newConfig.modules[i] = result.modules.slice(
            startSlice.module,
            startSlice.module + countModuleSection
          );

          startSlice.module += countModuleSection;
          startSlice.options += countItemSection;
        }
        setConfigList((prev) => ({ ...prev, ...newConfig }));
      }
    });
  }, []);
  const onCollapseHandler = () => {
    setCollapse((prevState) => !prevState);
  };

  return (
    <div>
      <HeaderConfigPanel
        collapseState={{ get: collapse, set: onCollapseHandler }}
      />
      <Collapse in={collapse}>
        <Grid
          container
          direction="column"
          justifyContent="center"
          alignItems="center"
        >
          <Grid container direction="row" alignItems="center">
            {configList.options &&
              configList.options.map((section) => (
                <ConfigPanelSection arItems={section} />
              ))}
          </Grid>
          <div className="panel-module-block">
            <Typography variant="h5" align="center">
              Modules options
            </Typography>
          </div>
          <Grid container direction="row" alignItems="center">
            {configList.modules &&
              configList.modules.map((section) => (
                <ConfigPanelSection arItems={section} />
              ))}
          </Grid>
        </Grid>
      </Collapse>
    </div>
  );
};
