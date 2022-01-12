import React from "react";
import cn from "classnames";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useSelector, useDispatch } from "react-redux";
import RemoveHeader from "@modules/icon/components/header/RemoveHeader";
import Magnifier from "@modules/icon/components/common/magnifier/Light";
import SuggestionsListForAll from "@modules/old-components/SuggestionsListForAll";
import {
  getSuggestionsAction,
  setSuggestionsAction as setSuggests,
} from "@redux/actions/account-actions/SuggestionActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import Styles from "@modules/account/components/hat/Search.module.scss";

const searchUrl = "/search";
const timeout = 700;
const classes = {
  buttonSearch: [
    Styles.buttonSearch,
    Styles.searchForm__buttonSearch,
    "align-items-center",
    "justify-content-center",
    "d-none",
    "d-lg-block",
  ],
  inputSearch: ["rounded-0", "input-search", Styles.inputSearch],
};

function checkValue(query: string) {
  return query && query.length >= 3;
}

function hasNoSuggests(suggests: Record<any, any>) {
  return (
    !suggests.category_suggestions.length &&
    !suggests.phrase_suggestions.length &&
    !suggests.product_suggestions
  );
}

const Search: React.FC = () => {
  const [query, setQuery] = React.useState("");
  const breakpoint = useBreakpoint();
  const timerRef = React.useRef<NodeJS.Timeout>();
  const dispatch = useDispatch();
  const placeholder = useSelector(
    (e: StoreInterface) => e.config.cidev_header_code
  );

  const suggests = useSelector((e: StoreInterface) => e.suggestion);

  const isVisibleSearchMobile = useSelector(
    (e: StoreInterface) => e.searchMobile.isVisible
  );

  const isVisibleShadowPanel = useSelector(
    (e: Record<any, any>) => e.shadowPanel.isVisible
  );

  React.useEffect(() => {
    breakpoint({
      xs: function () {
        if (isVisibleSearchMobile && !isVisibleShadowPanel) {
          dispatch(setVisibleShadowPanelAction(true));
        } else if (!isVisibleSearchMobile && isVisibleShadowPanel) {
          dispatch(setVisibleShadowPanelAction(false));
        }
      },
      sm: undefined,
      md: undefined,
      lg: function () {
        if (suggests && !isVisibleShadowPanel) {
          dispatch(setVisibleShadowPanelAction(true));
        } else if (!suggests && isVisibleShadowPanel) {
          dispatch(setVisibleShadowPanelAction(false));
        }
      },
      xl: undefined,
      xxl: undefined,
    });
  }, [suggests, isVisibleSearchMobile]);

  const clearSearch = () => {
    setQuery("");
    dispatch(setSuggests(null));
  };

  const getSuggest = (query: string) => {
    dispatch(
      getSuggestionsAction({
        query: query,
        success(res) {
          if (hasNoSuggests(res.suggests)) {
            dispatch(setSuggests(null));
            return;
          }
          dispatch(setSuggests(res.suggests));
        },
      })
    );
  };

  const onChangeInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    timerRef.current && clearTimeout(timerRef.current);
    setQuery(e.target.value);
    if (checkValue(e.target.value)) {
      timerRef.current = setTimeout(() => {
        getSuggest(e.target.value);
      }, timeout);
    } else {
      suggests && dispatch(setSuggests(null));
    }
  };

  const onClickInput = () => {
    if (!suggests && checkValue(query)) {
      getSuggest(query);
    }
  };

  return (
    <div
      className={cn("flex-grow-1", {
        [Styles.searchMobile_hidden]: !isVisibleSearchMobile,
      })}
    >
      <form
        action={searchUrl}
        method="get"
        itemProp="potentialAction"
        itemScope
        itemType="https://schema.org/SearchAction"
        className={Styles.searchForm}
      >
        <div>
          <input
            type="text"
            name="q"
            className={cn(classes.inputSearch)}
            value={query}
            onChange={onChangeInput}
            onClick={onClickInput}
            placeholder={placeholder}
            itemProp="query-input"
            autoComplete="off"
          />

          <meta itemProp="target" content={searchUrl + "?q={query}"} />

          <a
            onClick={clearSearch}
            className={cn(Styles.buttonClear, {
              "d-block": query,
            })}
          >
            <RemoveHeader
              className={cn(Styles.buttonClear__svg, "position-absolute")}
            />
          </a>
          {suggests && (
            <SuggestionsListForAll
              suggestions={suggests}
              searchString={query}
            />
          )}
        </div>
        <button type="submit" className={cn(classes.buttonSearch)}>
          <Magnifier className={"d-block"} />
        </button>
      </form>
    </div>
  );
};

export default Search;
