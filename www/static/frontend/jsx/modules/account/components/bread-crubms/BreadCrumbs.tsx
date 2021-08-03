import React from "react";
import { useLocation, NavLink } from "react-router-dom";
import { Swiper, SwiperSlide } from "swiper/react";

export const BreadCrumbs = () => {
  const location = useLocation();

  const breadCrumbsNames = location.pathname.split("/").filter((e) => e !== "");

  const breadCrumbs = breadCrumbsNames.map((name) => {
    return {
      name: editRouteToLabel(name),
      path: "#",
    };
  });

  function editRouteToLabel(route: string) {
    const labels = route.split("-");

    return labels
      .map((e) => {
        const word = e.split("");
        word[0] = word[0].toLocaleUpperCase();
        return word.join("");
      })
      .join(" ");
  }

  return (
    <Swiper
      spaceBetween={0}
      longSwipesRatio={0.05}
      slidesPerView="auto"
      resistance={true}
      resistanceRatio={0}
      className="breadcrumb-list no-bullet mb-2.25 mt-2.25"
      itemType="http://schema.org/BreadcrumbList"
      itemProp="breadcrumb"
      itemScope
      onSwiper={(swiper) => swiper.slideToLoop(breadCrumbs.length)}
    >
      {breadCrumbs.map((item, i) => {
        const last = i + 1 === breadCrumbs.length;

        if (!last) {
          return (
            <SwiperSlide key={i} className="breadcrumb-slide">
              <NavLink
                to={"#"}
                className="breadcrumb-link"
                itemScope
                itemType="http://schema.org/Thing"
                itemProp="item"
                id={item.path}
                href={item.path}
              >
                <span itemProp="name">{item.name}</span>
              </NavLink>
              <meta itemProp="position" content={i + 1} />
            </SwiperSlide>
          );
        } else {
          return (
            <SwiperSlide key={i} className="breadcrumb-slide">
              <span
                itemScope
                itemType="http://schema.org/Thing"
                itemProp="item"
                id={item.path}
              >
                <span itemProp="name">{item.name}</span>
              </span>
              <meta itemProp="position" content={i + 1} />
            </SwiperSlide>
          );
        }
      })}
    </Swiper>
  );
};
