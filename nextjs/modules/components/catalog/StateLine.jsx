import PageCount from "@modules/components/catalog/PageCount";
import classnames from "classnames";
import CatalogViewMode from "@modules/components/catalog/CatalogViewMode";
import { createRef } from "preact";
import CatalogContext from "@modules/components/catalog/CatalogContext";
import React from "react";
import $ from "jquery";

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
    const sortKey = e.target.getAttribute("data-value");

    this.props.onSort(sortKey);
    this.setState({ sortKey });
  }

  sortingOptionsList() {
    console.log("props", this.props);
    if (this.hideSort) {
      return;
    }

    const classes = classnames("action_block", "sort", {
      active: this.state.isOpenSortMenu,
    });

    return (
      <div className={classes}>
        <div
          className="action_button sort state-line-sort"
          onClick={this.toggleSortList.bind(this)}
        >
          <span className="action state-line-sort__caption">Sort by</span>
          <span
            className="active_value show-for-large"
            onClick={this.toggleSortList.bind(this)}
            ref={this.sortButton}
          >
            {this.sortingOptions[this.props.sortKey]}
          </span>
        </div>

        <ul className="options no-bullet">
          {(() => {
            const options = [];

            for (const key in this.sortingOptions) {
              const option = this.sortingOptions[key];

              options.push(
                <li
                  key={`sort-option${key}`}
                  data-value={key}
                  className={classnames({ active: key === this.props.sortKey })}
                  onClick={this.sortCatalog.bind(this)}
                >
                  {option}
                </li>
              );
            }

            return options;
          })()}
        </ul>
      </div>
    );
  }

  render() {
    const stateLineClasses = [
      this.props.classes.container,
      "products-state-line",
      "pcont",
      {
        "skeleton-box": this.context.pager === null,
      },
    ];

    return (
      <div className={classnames(stateLineClasses, "d-block")}>
        <div className="row">
          <div className="col-lg-3 d-none d-lg-block">
            <div className="page_count_wrap">
              {this.context.pager && <PageCount />}
            </div>
          </div>
          <div className="col-12 col-lg-9">
            <div className="actions">
              <div className="action_group">{this.sortingOptionsList()}</div>

              <CatalogViewMode />
            </div>
          </div>
        </div>
      </div>
    );
  }
}

StateLine.contextType = CatalogContext;
