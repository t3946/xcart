import React from "react";
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

  const getComponent = (item: any) => {
    switch (title) {
      case "Search suggestions":
        return (
          <a
            href={`/search?q=${item.replace(" ", "+")}`}
            dangerouslySetInnerHTML={{
              __html: item.replace(regExp, "<b>$1</b>"),
            }}
            className="suggestions-item-link"
          />
        );
      case "Categories":
        return (
          <a
            href={item.link}
            dangerouslySetInnerHTML={{
              __html: item.name.replace(regExp, "<b>$1</b>"),
            }}
            className="suggestions-item-link"
          />
        );
      case "Products":
        return (
          <a href={item.link}>
            <span className="icon" style={getImage(item.image)}>
              <span className="show-for-sr">{item.name}</span>
            </span>
            <span
              className="label"
              dangerouslySetInnerHTML={{
                __html: item.name.replace(regExp, "<b>$1</b>"),
              }}
            />
          </a>
        );
    }
  };
  return (
    <div className={`${typeList} suggestions`}>
      <div className="suggestionsTitle">{title}</div>
      <ul>
        {items.map((item, i) => (
          <li className={"item" + i}>{getComponent(item)}</li>
        ))}
      </ul>
    </div>
  );
};
