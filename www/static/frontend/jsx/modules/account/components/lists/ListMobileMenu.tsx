import React from "react";
import { useHistory } from "react-router-dom";
import { List } from "@client/modules/account/ts/types/list.type";

interface ListMobileMenuProps {
  lists: List[];
}

export const ListMobileMenu: React.FC<ListMobileMenuProps> = ({ lists }) => {
  const history = useHistory();

  const redirectToList = (hash: string) => {
    history.push(`/account/your-lists/${hash}`);
  };

  return (
    <div>
      <div className="page-label">Shopping lists</div>
      <div className="create-list-btn-container-mobile">
        <div className="sidebar-list-cross">
          <img src="/static/frontend/images/icons/account/cross-bold.svg" />
        </div>
        <div
          onClick={() => redirectToList("add-list/")}
          className="create-list-label create-list-label-mobile"
        >
          create a list
        </div>
      </div>
      {lists.map((e) => {
        return (
          <div
            onClick={() => redirectToList(e.cache_url)}
            className={"list-mobile-menu-item"}
          >
            <div className="list-mobile-menu-item-name">{e.name}</div>
          </div>
        );
      })}
    </div>
  );
};
