import React from "react";
import Link from "next/link";
import classnames from "classnames";

interface sideBarMenuItemProps {
  to: string;
  label: string | React.ReactNode;
  badge?: number | string;
  className?: any;
  onClick?: any;
}

export const SideBarMenuItem: React.FC<sideBarMenuItemProps> = ({
  to,
  label,
  badge,
  className,
  onClick,
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

  //todo: у старого роутера был класс activeClassName="active-route"
  return (
    <Link href={to}>
      <a
        className={classnames(
          "sidebar-menu-item text-decoration-none",
          className
        )}
        onClick={onClick}
      >
        {label}
        {badgeTemplate()}
      </a>
    </Link>
  );
};
