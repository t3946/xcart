import { render } from "preact";
import Catalog from "@/components/catalog/Catalog";
import { Provider } from "react-redux";
import Store from "@client/jsx/redux/stores/Store";

export default (() => {
  // init catalog
  const elem = document.getElementsByClassName("catalog-component")[0];

  if (!elem) {
    return;
  }

  const sortingOptions = JSON.parse(elem.dataset.sortingOptions);
  const hideSort = !!elem.dataset.hideSort;
  const catalogUrl = "/api" + document.location.pathname;

  return render(
    <Provider store={Store}>
      <Catalog
        sortingOptions={sortingOptions}
        sortKey={elem.dataset.currentSortingKey}
        hideSort={hideSort}
        catalogUrl={catalogUrl}
        checkoutUrl={elem.dataset.checkoutUrl}
        searchText={elem.dataset.searchText}
      />
    </Provider>,
    elem
  );
})();
