import React, { useEffect, useState } from "react";
import useCLickListener from "../../hooks/useClickListener";
import { Grid } from "@material-ui/core";
import classnames from "classnames";
import { UserRightsActionsEnum } from "@client/modules/account/ts/consts/user-rights-actions.enum";

export const ShareListManagePeopleSelect = ({
  items,
  onClick,
  value,
  name,
  classes = undefined,
  id = undefined,
}) => {
  const selectedItem = value;
  const [open, setOpen] = useState(false);

  const clickListener = useCLickListener(setOpen, id);

  useEffect(() => {
    clickListener.startListen();

    return () => {
      clickListener.endListen();
    };
  });

  return (
    <Grid
      className={classnames(
        `select select-send share-list-select  ${open && "open"}`,
        classes?.group
      )}
      container
      alignItems="center"
      justifyContent="space-between"
    >
      <div
        onClick={() => {
          setOpen(!open);
        }}
        className={classnames("share-list-select-wrapper", classes?.input)}
      >
        <input
          value={selectedItem}
          className="select__input"
          type="hidden"
          name={name}
        />
        <div id={id} className="share-list-select-head">
          {selectedItem.viewValue}
        </div>
        {open && (
          <ul
            className={classnames(
              "share-list-select-list",
              classes?.selectList
            )}
          >
            {items.map((item) => {
              return (
                <li
                  onClick={() => onClick(item)}
                  className={`share-list-select-item ${
                    item.value === value.value &&
                    "share-list-select-item-selected"
                  }`}
                >
                  {item.viewValue}
                </li>
              );
            })}
            <li
              onClick={() => onClick({ value: UserRightsActionsEnum.DELETE })}
              className="share-list-remove-user"
            >
              Remove
            </li>
          </ul>
        )}
      </div>
    </Grid>
  );
};
