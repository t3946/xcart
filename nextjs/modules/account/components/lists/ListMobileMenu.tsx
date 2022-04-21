import React from "react";
import { useRouter } from "next/router";
import { List } from "@modules/account/ts/types/list.type";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Link from "next/link";
import cn from "classnames";

export const ListMobileMenu: React.FC = () => {
  const lists: List[] = useSelectorAccount((state) => state.lists.lists);
  const router = useRouter();

  const redirectToList = (id: string) => {
    router.push(`/shopping-lists/${id}`);
  };

  function getListType(list) {
    return list.users.length ? "shared" : "private";
  }

  return (
    <div>
      <MobileMenuBackBtn redirectUrl={`/dashboard`} label={"account"} />

      <div className="page-label">Shopping lists</div>

      <div
        onClick={() => redirectToList("actions/add-list/")}
        className="create-list-btn-container-mobile"
      >
        <div className="sidebar-list-cross">
          <img src="/static/frontend/images/icons/account/cross-bold.svg" />
        </div>
        <div className="create-list-label create-list-label-mobile">
          create a list
        </div>
      </div>
      {lists.map((list) => (
        <Link
          href={`/shopping-lists/${list.product_list_id}`}
          key={list.product_list_id}
        >
          <a
            className={
              cn("list-mobile-menu-item", "d-flex", "justify-content-between", "alight-center", "text-decoration-none")
            }
          >
            <div className="list-mobile-menu-item-name">{list.name}</div>
            <img
              src={`/static/frontend/images/icons/account/list-${getListType(
                list
              )}.svg`}
              alt={""}
            />
          </a>
        </Link>
      ))}
    </div>
  );
};

export default ListMobileMenu;
