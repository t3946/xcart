import React, { Fragment } from "react";
import Highlighter from "react-highlight-words";
interface SuggestionList {
  search: string;
  title: string;
  items:
    | string[]
    | { id: number; image: string; link: string; name: string }[]
    | { id: number; link: string; name: string };
  typeList: string;
}
export const SuggestionList: React.FC<SuggestionList> = ({
  search,
  title,
  items,
  typeList,
}) => {
  const regExp = new RegExp("(" + search.split(" ").join("|") + ")", "gi");
  const getImage = (image: string) => {
    if (image) {
      return { backgroundImage: `url('${image}')` };
    }
    return {};
  };
  const getLink = (item) => {
    if (title === "Search suggestions") {
      return `/search?q=${item.replace(" ", "+")}`;
    }
    return item.link;
  };

  const getComponent = (item: any) => {
    switch (title) {
      case "Search suggestions":
        return (
          <span
            dangerouslySetInnerHTML={{
              __html: item.replace(regExp, "<b>$1</b>"),
            }}
            className="suggestions-item-link"
          />
        );
      case "Categories":
        return (
          <span
            dangerouslySetInnerHTML={{
              __html: item.name.replace(regExp, "<b>$1</b>"),
            }}
            className="suggestions-item-link"
          />
        );
      case "Products":
        return (
          <div className="product-wrapper">
            <span className="icon" style={getImage(item.image)}>
              <span className="show-for-sr">{item.name}</span>
            </span>
            <span
              className="label"
              dangerouslySetInnerHTML={{
                __html: item.name.replace(regExp, "<b>$1</b>"),
              }}
            />
          </div>
        );
    }
  };
  return (
    <div className={`${typeList} suggestions`}>
      <div className="suggestionsTitle">{title}</div>
      <ul>
        {items.map((item, i) => (
          <a className="suggestions-item-link" href={getLink(item)}>
            <li className={"item" + i}>{getComponent(item)}</li>
          </a>
        ))}
      </ul>
    </div>
  );
};
