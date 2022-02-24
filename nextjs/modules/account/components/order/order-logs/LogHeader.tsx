import React from "react";
import classes from "./OrderLog.module.scss";

export const LogHeader = () => {
  return (
    <div className={classes.orderLogHeader}>
      <div className={classes.cell}>Date</div>
      <div className={classes.cell}>Name</div>
      <div className={classes.cell}>Type</div>
      <div className={classes.cell}>Action</div>
    </div>
  );
};
