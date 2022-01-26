import React from "react";
import cn from "classnames";
import CatalogContext from "@modules/components/catalog/CatalogContext";
import ListIcon from "@components/common/icons/view-mode/List";
import TileIcon from "@components/common/icons/view-mode/Tile";
import Styles from "@modules/components/catalog/CatalogViewMode.module.scss";

export default class CatalogViewMode extends React.Component {
  constructor() {
    super();

    this.LIST_MODE = "list";
    this.TILE_MODE = "tile";
  }

  setMode(e, mode) {
    e.preventDefault();

    //update view mode in state and locale storage
    this.context.onViewModeChange(mode);
  }

  render() {
    const mode = this.context.viewMode || this.TILE_MODE;
    const classes = {
      button: {
        tile: [
          Styles.button,
          "d-flex",
          "me-2",
          {
            [Styles.button_active]: mode === this.TILE_MODE,
          },
        ],
        list: [
          Styles.button,
          "d-flex",
          {
            [Styles.button_active]: mode === this.LIST_MODE,
          },
        ],
      },

      icon: [Styles.icon],
    };

    return (
      <div className="action_block view d-flex align-items-center ">
        <span className="show-for-large lh-1 me-2">View as</span>

        <a
          onClick={(e) => this.setMode(e, this.TILE_MODE)}
          href="#"
          className={cn(classes.button.tile)}
          data-value="tile-view"
        >
          <TileIcon className={classes.icon} />
        </a>

        <a
          onClick={(e) => this.setMode(e, this.LIST_MODE)}
          href="#"
          className={cn(classes.button.list)}
          data-value="list-view"
        >
          <ListIcon className={classes.icon} />
        </a>
      </div>
    );
  }
}

CatalogViewMode.contextType = CatalogContext;
