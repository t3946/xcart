import React from "react";
import { Grid, IconButton, Paper } from "@material-ui/core";
import ChevronLeftIcon from "@material-ui/icons/ChevronLeft";
import ChevronRightIcon from "@material-ui/icons/ChevronRight";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import {IconsList} from "@s3stores-mail/components/simple/icons-list/IconsList";

interface EmailListHeaderPropsDto {
  getNewPage: (count: number) => void;
  page: number;
  maxPage: number;
  paginate: () => string;
}

export const EmailListHeader: React.FC<EmailListHeaderPropsDto> = ({
  getNewPage,
  page,
  maxPage,
  paginate,
}) => {
  const itemsCount = useSelector((state: StoreDto) => state.itemsCount);
  return (
    <div>
      <Paper className="header-wrap" square={true}>
        <Grid alignItems="center" container justify={"space-between"}>
          <IconsList />
          <div className="pagination-wrap">
            <div className="faxage-text paginate">
              <span>
                {paginate()} of {itemsCount ? itemsCount : "many"}
              </span>
            </div>
            <IconButton disabled={page === 1} onClick={() => getNewPage(-1)}>
              <ChevronLeftIcon />
            </IconButton>
            <IconButton
              disabled={maxPage === page}
              onClick={() => {
                getNewPage(1);
              }}
            >
              <ChevronRightIcon />
            </IconButton>
          </div>
        </Grid>
      </Paper>
    </div>
  );
};
