import React, { useEffect, useState } from "react";
import useCLickListener from "../../hooks/useClickListener";
import classnames from "classnames";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import { SelectValue } from "@modules/account/ts/types/select-value.type";

interface ShareListManagePeopleSelectProps {
  items: SelectValue<UserRightsActionsEnum, string>[];
  onClick: (value: SelectValue<UserRightsActionsEnum, string>) => void;
  value: SelectValue<UserRightsActionsEnum, string>;
  classes?: {
    group?: string | string[];
    input?: string | string[];
    selectList?: string | string[];
  };
  name: string;
  id?: string;
}

export const ShareListManagePeopleSelect: React.FC<
  ShareListManagePeopleSelectProps
> = ({ items, onClick, value, name, classes, id }) => {
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
    <div
      className={classnames(
        `select select-send share-list-select d-flex justify-content-between align-center ${
          open && "open"
        }`,
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
          value={selectedItem.value}
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
              onClick={() =>
                onClick({
                  value: UserRightsActionsEnum.DELETE,
                })
              }
              className="share-list-remove-user"
            >
              Remove
            </li>
          </ul>
        )}
      </div>
    </div>
  );
};
