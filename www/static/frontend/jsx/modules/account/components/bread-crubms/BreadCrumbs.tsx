import React from "react";
import { useLocation, NavLink } from "react-router-dom";
import { Swiper, SwiperSlide } from "swiper/react";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";

export const BreadCrumbs = (): any => {
  useLocation();
  const breadcrumbsStore = useSelector((e: StoreDto) => e.breadcrumbs);
  const breadcrumbsList = [];
  const subPathsList = window.location.pathname.split("/");

  for (let i = 0, path = ""; i < subPathsList.length; i++) {
    const subPath = subPathsList[i];

    if (subPath === "") {
      continue;
    }

    path += `/${subPath}`;
    const name = breadcrumbsStore[path] || breadcrumbsStore[path + "/"];

    if (name) {
      breadcrumbsList.push({
        name,
        path,
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
      className="breadcrumb-list no-bullet mb-2.25 mt-2.25"
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
              <NavLink
                to={item.path}
                className="breadcrumb-link"
                itemScope
                itemType="https://schema.org/Thing"
                itemProp="item"
                id={item.path}
                href={item.path}
              >
                <span itemProp="name">{item.name}</span>
              </NavLink>
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
