import React, { useEffect, useState } from "react";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { Collapse, Grid } from "@material-ui/core";
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
      // const result = convertObjectToArray(res);
      if (result) {
        const countCiel = Math.ceil(result.config.length / 3);
        const newConfig = { section: [] };
        let startSlice = 0;
        for (let i = 0; i < 3; i++) {
          newConfig.section[i] = result.config.slice(
            startSlice,
            startSlice + countCiel
          );
          startSlice += countCiel;
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
          direction="row"
          justifyContent="center"
          alignItems="center"
        >
          {configList.section &&
            configList.section.map((section) => (
              <ConfigPanelSection arItems={section} />
            ))}
        </Grid>
      </Collapse>
    </div>
  );
};
