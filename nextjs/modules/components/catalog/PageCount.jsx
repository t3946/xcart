import CatalogContext from "@/components/catalog/CatalogContext";

export default class PageCount extends Component {
  constructor() {
    super();
  }

  render() {
    const $pager = this.context.pager;

    const pageSize = $pager.pageSize;
    const currentPage = $pager.currentPage || 1;
    const currentPageSize = $pager.paginateCount;
    const total = $pager.total;

    return (
      <span className="page_count">
        <span className="count">
          {$pager ? pageSize * (currentPage - 1) + currentPageSize : "Loading"}
        </span>
        <span> / </span>
        <span className="full">{$pager ? total : "Loading"}</span>
        <span> items shown</span>
      </span>
    );
  }
}
PageCount.contextType = CatalogContext;
