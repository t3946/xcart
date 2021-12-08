import React from "react";
import Link from "next/link";
import { Swiper, SwiperSlide } from "swiper/react";
import { useSelector } from "react-redux";
import { AccountStore } from "@modules/account/ts/types/store.type";

const BreadCrumbs: React.FC = () => {
  const breadcrumbsStore = useSelector((e: AccountStore) => e.breadcrumbs);
  const breadcrumbsList = [];

  let path;

  if (process.browser) {
    path = window.location.pathname;
  } else {
    path = process.next.url;
  }

  const subPathsList = path.split("/");

  for (let i = 0, path = ""; i < subPathsList.length; i++) {
    const subPath = subPathsList[i];

    if (subPath === "") {
      continue;
    }

    path += `/${subPath}`;

    if (breadcrumbsStore[path]) {
      breadcrumbsList.push({
        name: breadcrumbsStore[path],
        path,
      });
    } else if (breadcrumbsStore[path + "/"]) {
      breadcrumbsList.push({
        name: breadcrumbsStore[path + "/"],
        path: path + "/",
      });
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

        if (!last) {
          return (
            <SwiperSlide key={i} className="breadcrumb-slide">
              <Link href={item.path}>
                <a
                  className="breadcrumb-link"
                  itemScope
                  itemType="https://schema.org/Thing"
                  itemProp="item"
                  id={item.path}
                >
                  <span itemProp="name">{item.name}</span>
                </a>
              </Link>
              <meta itemProp="position" content={(i + 1).toString()} />
            </SwiperSlide>
          );
        } else {
          return (
            <SwiperSlide key={i} className="breadcrumb-slide">
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
