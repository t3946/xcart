import React from "react";
import { Grid, IconButton, Paper } from "@material-ui/core";
import ChevronLeftIcon from "@material-ui/icons/ChevronLeft";
import ChevronRightIcon from "@material-ui/icons/ChevronRight";
import { useSelector } from "react-redux";
import { IconsList } from "../icons-list/IconsList";

export const EmailListHeader: React.FC<any> = ({
  getNewPage,
  page,
  maxPage,
  paginate,
}) => {
  const itemsCount = useSelector((state: any) => state.itemsCount);
  return (
    <div>
      <Paper className="header-wrap" square={true}>
        <Grid container justify={"space-between"}>
          <IconsList />
          <div className="pagination-wrap">
            <div className="faxage-text paginate">
              <span>
                {paginate()} of {itemsCount}
              </span>
            </div>
            <IconButton disabled={page === 1} onClick={() => getNewPage(-1)}>
              <ChevronLeftIcon />
            </IconButton>
            <IconButton
              disabled={maxPage === page}
              onClick={() => getNewPage(1)}
            >
              <ChevronRightIcon />
            </IconButton>
          </div>
        </Grid>
      </Paper>
    </div>
  );
};
