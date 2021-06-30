import { render } from "preact";
import Catalog from "@/components/catalog/Catalog";

export default (() => {
  // init catalog
  const elem = document.getElementsByClassName("catalog-component")[0];

  if (!elem) {
    return;
  }

  const sortingOptions = JSON.parse(elem.dataset.sortingOptions);
  const hideSort = !!elem.dataset.hideSort;
  const pager = JSON.parse(elem.dataset.pager);

  return render(
    <Catalog
      sortingOptions={sortingOptions}
      sortKey={elem.dataset.currentSortingKey}
      hideSort={hideSort}
      pager={pager}
      catalogUrl={"/api" + elem.dataset.catalogUrl}
      checkoutUrl={elem.dataset.checkoutUrl}
      searchText={elem.dataset.searchText}
    />,
    elem
  );
})();
