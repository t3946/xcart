import React, { useContext, useEffect, useState } from "react";
import { Grid } from "@material-ui/core";
import SearchIcon from "@material-ui/icons/Search";
import ExpandLessIcon from "@material-ui/icons/ExpandLess";
import ExpandMoreIcon from "@material-ui/icons/ExpandMore";
import { EmailDialogContext } from "@s3stores-mail/contexts/email-send-context/EmailDialogContext";

const EmailListSearch: React.FC<any> = ({ subject, editSearchSubject }) => {
  useEffect(() => {
    setSearchValue(subject);
  }, [subject]);
  const [focus, setFocus] = useState(false);

  const [searchValue, setSearchValue] = useState(subject);

  const { handleClickOpen } = useContext(EmailDialogContext);

  const onChangeValue = (e) => {
    setSearchValue(e.target.value);
  };

  const onSubmit = (e) => {
    e.preventDefault();
    editSearchSubject(searchValue);
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
          <form onSubmit={onSubmit}>
            <input
              value={searchValue}
              onChange={onChangeValue}
              onBlur={() => setFocus(false)}
              onFocus={() => setFocus(true)}
              className="search-input"
            />
          </form>
        </Grid>
        <div onClick={handleClickOpen}>
          {focus ? (
            <ExpandLessIcon className={"icon"} />
          ) : (
            <ExpandMoreIcon className={"icon"} />
          )}
        </div>
      </Grid>
    </div>
  );
};

export default EmailListSearch;
