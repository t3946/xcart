import React from "react";
import { useRouter } from "next/router";
import { List } from "@modules/account/ts/types/list.type";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

interface ListMobileMenuProps {
  lists: List[];
}

export const ListMobileMenu: React.FC<ListMobileMenuProps> = ({ lists }) => {
  const router = useRouter();

  const redirectToList = (hash: string) => {
    router.push(`/shopping-lists/${hash}`);
  };

  return (
    <div>
      <MobileMenuBackBtn redirectUrl={`/account/`} label={"account"} />
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
            className={
              "list-mobile-menu-item d-flex justify-content-between alight-center"
            }
          >
            <div className="list-mobile-menu-item-name">{e.name}</div>
            <img
              src={`/static/frontend/images/icons/account/list-${e.list_info.list_type}.svg`}
            />
          </div>
        );
      })}
    </div>
  );
};
