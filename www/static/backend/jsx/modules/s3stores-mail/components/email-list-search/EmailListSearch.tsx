import React, { useState } from "react";
import { Button, Grid, IconButton } from "@material-ui/core";
import AddIcon from "@material-ui/icons/Add";
import SearchIcon from "@material-ui/icons/Search";
import ExpandLessIcon from "@material-ui/icons/ExpandLess";
import ExpandMoreIcon from "@material-ui/icons/ExpandMore";
import { useDispatch } from "react-redux";
import { setSearchOptions } from "../../../../redux/actions/emailActions";
import { EmailSearchDialog } from "../email-search-dialog.tsx/EmailSearchDialog";

export const EmailListSearch = () => {
  const [focus, setFocus] = useState(false);

  const [value, setValue] = useState("");

  const [open, setOpen] = React.useState(false);

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = (value: string) => {
    setOpen(false);
  };

  const dispatch = useDispatch();

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
            value={value}
            onChange={(e) => {
              setValue(e.target.value);
              dispatch(setSearchOptions(e.target.value));
            }}
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
