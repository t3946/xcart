export function viewPaginateInfo(
  page: number,
  itemsCount: number,
  pages: number,
  itemsOnPage: number
): string {
  if (page === pages) {
    const lastPage =
      pages % itemsOnPage === 0
        ? pages * itemsOnPage
        : (page - 1) * itemsOnPage + (itemsCount % itemsOnPage);
    return `${(page - 1) * itemsOnPage + 1} - ${lastPage}`;
  }
  return `${(page - 1) * itemsOnPage + 1} - ${page * itemsOnPage}`;
}
