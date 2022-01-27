import React from "react";
import { useRouter } from "next/router";
import { List } from "@modules/account/ts/types/list.type";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

export const ListMobileMenu: React.FC = () => {
  const lists: List[] = useSelectorAccount((state) => state.lists.lists);
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
      {lists.map((e) => (
        <div
          key={e.cacheUrl}
          onClick={() => redirectToList(e.cacheUrl)}
          className={
            "list-mobile-menu-item d-flex justify-content-between alight-center"
          }
        >
          <div className="list-mobile-menu-item-name">{e.name}</div>
          <img
            src={`/static/frontend/images/icons/account/list-${e.listType}.svg`}
          />
        </div>
      ))}
    </div>
  );
};
