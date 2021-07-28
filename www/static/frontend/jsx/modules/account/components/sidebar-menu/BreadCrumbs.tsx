import React from "react";
import { useLocation } from "react-router-dom";

export const BreadCrumbs = () => {
  const location = useLocation();

  const breadCrumbs = location.pathname.split("/").filter((e) => e !== "");

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
    <div className="container">
      <div className="bread-crumbs-container">
        {breadCrumbs.map((e, index) => {
          const lastChild = index === breadCrumbs.length - 1;
          return (
            <span
              className={`bread-crumbs ${lastChild && "bread-crumbs-last"}`}
            >
              {editRouteToLabel(e)} {!lastChild && " > "}
            </span>
          );
        })}
      </div>
    </div>
  );
};
