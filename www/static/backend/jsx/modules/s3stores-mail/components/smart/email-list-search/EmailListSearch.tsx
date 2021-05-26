import React, { useState } from "react";
import { Grid } from "@material-ui/core";
import SearchIcon from "@material-ui/icons/Search";
import ExpandLessIcon from "@material-ui/icons/ExpandLess";
import ExpandMoreIcon from "@material-ui/icons/ExpandMore";
import { useDispatch } from "react-redux";
import { setSearchOptions } from "@redux/actions";
import { EmailSearchDialog } from "../../../contexts/email-search-dialog.tsx/EmailSearchDialog";

export const EmailListSearch: React.FC = () => {
  const [focus, setFocus] = useState(false);

  const [searchValue, setSearchValue] = useState("");

  const [open, setOpen] = React.useState(false);

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };
  const dispatch = useDispatch();

  const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setSearchValue(event.target.value);
    dispatch(setSearchOptions(event.target.value));
  };

  return (
    <div className="search-back">
      <Grid
        alignItems={"center"}
        className={`search-wrap ${focus && "focus"}`}
        container
        justify={"space-around"}
      >
        <Grid spacing={3} xs justify={"center"} container alignItems={"center"}>
          <SearchIcon />
          <span className="search-text">Search mail</span>
        </Grid>

        <Grid spacing={3} xs={10}>
          <input
            value={searchValue}
            onChange={handleChange}
            onBlur={() => setFocus(false)}
            onFocus={() => setFocus(true)}
            className="search-input"
          />
        </Grid>
        <div onClick={handleClickOpen}>
          {focus ? (
            <ExpandLessIcon className={"icon"} />
          ) : (
            <ExpandMoreIcon className={"icon"} />
          )}
        </div>
      </Grid>
      <EmailSearchDialog open={open} handleClose={handleClose} />
    </div>
  );
};
