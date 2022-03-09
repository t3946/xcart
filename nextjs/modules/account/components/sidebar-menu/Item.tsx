import React from "react";
import Link from "next/link";
import classnames from "classnames";

import Styles from "@modules/account/components/sidebar-menu/Item.module.scss";

interface sideBarMenuItemProps {
  to: string;
  label: string | React.ReactNode;
  badge?: number | string;
  className?: any;
  onClick?: any;
  active?: boolean;
}

const Item: React.FC<sideBarMenuItemProps> = ({
  to,
  label,
  badge,
  className,
  onClick,
  active,
}) => {
  function badgeTemplate(): any {
    if (!badge) {
      return;
    }

    return (
      <span className="sidebar-badge sidebar-menu-item_badge d-flex align-items-center justify-content-center rounded-pill fw-bold">
        {badge}
      </span>
    );
  }

  if (active) {
    return (
      <span
        className={classnames(
          Styles.item,
          Styles.item_active,
          "text-decoration-none",
          className
        )}
      >
        {label}
        {badgeTemplate()}
      </span>
    );
  }

  return (
    <Link href={to}>
      <a
        className={classnames(Styles.item, "text-decoration-none", className)}
        onClick={onClick}
      >
        {label}
        {badgeTemplate()}
      </a>
    </Link>
  );
};

export default Item;
