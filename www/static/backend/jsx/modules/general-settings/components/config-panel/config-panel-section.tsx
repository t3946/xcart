import React from "react";
import { Grid } from "@material-ui/core";
import { Link } from "react-router-dom";

interface IConfigPanelSection {
  arItems: {
    lang: string;
    isNew: boolean;
    name: string;
    url: string | boolean;
  }[];
}

export const ConfigPanelSection: React.FC<IConfigPanelSection> = ({
  arItems,
}) => {
  return (
    <div className="section-config-block">
      <Grid
        container
        direction="column"
        alignItems="center"
        justifyContent="center"
        style={{ width: "auto" }}
      >
        {arItems.map((item) => {
          return (
            <div className="section-item-block">
              {!item.isNew ? (
                <a href={`${item.link}`} className="section-item-link">
                  {item.lang}
                </a>
              ) : (
                <Link className="section-item-link" to={item.link}>
                  {item.lang}
                </Link>
              )}
            </div>
          );
        })}
      </Grid>
    </div>
  );
};
