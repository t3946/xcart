import PageCount from "@modules/components/catalog/PageCount";
import cn from "classnames";
import CatalogViewMode from "@modules/components/catalog/CatalogViewMode";
import { createRef } from "preact";
import CatalogContext from "@modules/components/catalog/CatalogContext";
import React from "react";
import $ from "jquery";
import Select from "@modules/ui/forms/select/Select";
import Styles from "@modules/components/catalog/StateLine.module.scss";

export default class StateLine extends React.Component {
  constructor(props) {
    super(props);

    this.sortingOptions = props.sortingOptions;
    this.hideSort = props.hideSort;
    this.sortButton = createRef();

    this.state = {
      isOpenSortMenu: false,
      sortKey: props.sortKey,
    };
  }

  componentDidMount() {
    this._mounted = true;

    $(document).click(() => {
      if (this._mounted) {
        this.setState({ isOpenSortMenu: false });
      }
    });
  }

  componentWillUnmount() {
    this._mounted = false;
  }

  toggleSortList(e) {
    e.stopPropagation();

    $(this.sortButton.current).toggleClass("active");

    this.setState({
      isOpenSortMenu: !this.state.isOpenSortMenu,
    });
  }

  sortCatalog(e) {
    const sortKey = e.target.value.value;
    this.props.onSort(sortKey);
    this.setState({ sortKey });
  }

  sortingOptionsList() {
    if (this.hideSort) {
      return;
    }

    const classes = [
      Styles.sort,
      {
        active: this.state.isOpenSortMenu,
      },
      "d-flex",
      "w-100",
    ];

    return (
      <div className={cn(classes)}>
        <div
          className="action_button sort state-line-sort"
          onClick={this.toggleSortList.bind(this)}
        >
          <span className={cn("action state-line-sort__caption", Styles.label)}>
            Sort by
          </span>
          <Select
            isSearchable={false}
            clearable={false}
            classes={{
              indicatorSeparator: "d-none",
              control: Styles.select,
              menu: Styles.menu,
            }}
            options={(() => {
              const options = [];
              for (const key in this.sortingOptions) {
                const option = this.sortingOptions[key];
                options.push({ value: key, label: option });
              }
              return options;
            })()}
            name="sort"
            value={{
              label: this.sortingOptions[this.props.sortKey],
              value: this.props.sortKey,
            }}
            onChange={this.sortCatalog.bind(this)}
          />
        </div>
      </div>
    );
  }

  render() {
    const stateLineClasses = [
      this.props.classes.container,
      Styles.stateLine,
      "pcont",
      {
        "skeleton-box": this.context.pager === null,
      },
    ];

    return (
      <div className={cn(stateLineClasses, "d-flex align-items-center")}>
        <div className="row flex-grow-1 m-0">
          <div className="col-lg-3 d-none d-lg-flex align-items-center">
            <div className="page_count_wrap">
              {this.context.pager && <PageCount />}
            </div>
          </div>
          <div className="col-12 col-lg-9 justify-content-end d-flex">
            <div className="actions d-flex w-100 w-md-auto">
              {this.sortingOptionsList()}

              <CatalogViewMode />
            </div>
          </div>
        </div>
      </div>
    );
  }
}

StateLine.contextType = CatalogContext;
