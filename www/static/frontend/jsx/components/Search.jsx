import storeApp from "../redux/stores/StoreApp";
import SearchSuggestion from "./SearchSuggestion";
import { actionMobileSearch } from "../redux/reduсers/appHeadReduсer";

export default class Search {
  constructor(elements = "header .mobile__search-btn") {
    this.elements = {};
    this.showInput = false;
    this.init(elements);
    new SearchSuggestion();
  }

  init(elements) {
    this.elements["button"] = $(elements);
    if (this.elements["button"].length > 0) {
      this.elements["search"] = $("#" + this.elements["button"].data("swich"));
      this.toggler = this.elements["search"].data("toggler");
      this.initHasToggler = this.elements["search"].hasClass(this.toggler);
      this.showInput = false;
      this._bind();
    }
  }

  _bind() {
    this.unsubscribe = storeApp.subscribe(() => {
      let state = storeApp.getState();

      if (state.frontend) {
        if (state.frontend.header.mobileSearch) {
          this._showSearch();
        } else {
          this._hideSearch();
        }
      }
    });

    this.elements["button"].on("click", (e) => {
      if (!this.showInput) {
        this._storeSearchShow();
      } else {
        this._storeSearchHide();
      }
    });
  }

  _storeSearchHide() {
    actionMobileSearch(false);
  }

  _storeSearchShow() {
    actionMobileSearch(true);
  }

  _showSearch() {
    this.showInput = true;

    if (this.initHasToggler) {
      this.elements["search"].removeClass(this.toggler);
      return;
    }
    this.elements["search"].addClass(this.toggler);
  }

  _hideSearch() {
    this.showInput = false;

    if (this.initHasToggler) {
      this.elements["search"].addClass(this.toggler);
      return;
    }
    this.elements["search"].removeClass(this.toggler);
  }
}
