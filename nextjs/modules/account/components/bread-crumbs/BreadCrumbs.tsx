import React from "react";
import Link from "next/link";
import { Swiper, SwiperSlide } from "swiper/react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Styles from "@modules/account/components/bread-crumbs/BreadCrumbs.module.scss";
import cn from "classnames";

const BreadCrumbs: React.FC = () => {
  const breadcrumbs = useSelectorAccount((e) => e.breadcrumbs);
  const breadcrumbsList = [];

  let currentPath;

  if (process.browser) {
    currentPath = window.location.pathname;
  } else {
    currentPath = "/account" + process.next.url;
  }

  const currentPathParts = currentPath.split("/");

  let subPath = "";

  for (const part of currentPathParts) {
    if (part === "") {
      continue;
    }

    subPath += `/${part}`;

    for (const breadcrumb of breadcrumbs) {
      if (breadcrumb.path === subPath) {
        breadcrumbsList.push({ ...breadcrumb });
        break;
      }
    }
  }

  return (
    <Swiper
      spaceBetween={0}
      longSwipesRatio={0.05}
      slidesPerView="auto"
      resistance={true}
      resistanceRatio={0}
      className="account-page_breadcrumbs breadcrumb-list list-unstyled d-none d-md-block"
      itemType="https://schema.org/BreadcrumbList"
      itemProp="breadcrumb"
      itemScope
      onSwiper={(swiper) => swiper.slideToLoop(breadcrumbsList.length)}
    >
      {breadcrumbsList.map((item, i) => {
        const last = i + 1 === breadcrumbsList.length;
        //next has base url `/account` that will be added in <Link>
        const path = item.path.replace("/account", "") || "/";

        if (!last) {
          return (
            <SwiperSlide
              key={i}
              className={cn(Styles.breadcrumbSlide, "w-auto")}
            >
              <Link href={path}>
                <a
                  className="breadcrumb-link"
                  itemScope
                  itemType="https://schema.org/Thing"
                  itemProp="item"
                >
                  <span itemProp="name">{item.name}</span>
                </a>
              </Link>
              <meta itemProp="position" content={(i + 1).toString()} />
            </SwiperSlide>
          );
        } else {
          return (
            <SwiperSlide
              key={i}
              className={cn(Styles.breadcrumbSlide, "w-auto")}
            >
              <span
                itemScope
                itemType="https://schema.org/Thing"
                itemProp="item"
                id={item.path}
              >
                <span itemProp="name">{item.name}</span>
              </span>
              <meta itemProp="position" content={(i + 1).toString()} />
            </SwiperSlide>
          );
        }
      })}
    </Swiper>
  );
};

export default BreadCrumbs;
