import documentReady from "../../utils/documentReady";

import { render } from "preact";
import Catalog from "@/components/catalog/Catalog";

(() => {
  let page = document.querySelector(".product-page");
  if (page) {
    let prices_table = page.querySelector(".table__prices--down");
    if (prices_table) {
      let prices_row = prices_table.querySelectorAll(".price-row");

      if (prices_row) {
        let timers = {};
        const $prices_table = $(page);
        const listPrice = parseFloat(
          $prices_table
            .find(".column-price .product-quantity-old-price .price")
            .text()
        );
        const $oldTotalPrice = $prices_table.find(
          ".column-extended .product-quantity-old-price .price"
        );

        $(document).on("component.quantity.change", (e, data) => {
          //update old total price
          $oldTotalPrice.text((listPrice * data.val).toFixed(2));

          if (
            data.product &&
            data.product.dataset.product === page.dataset.product
          ) {
            let allHide = true;
            let cnt = 0;

            prices_row.forEach((price) => {
              let hide =
                price.dataset.quantity <= data.val || cnt >= page.dataset.rows;
              let key = "price_" + price.dataset.quantity;

              price.classList.toggle("hidden", hide);
              price.classList.toggle("af-anim", hide);

              if (!hide) {
                allHide = false;
                cnt++;
              }
            });

            prices_table.classList.toggle("hidden", allHide);
          }
        });
      }
    }

    let questionsContainer = $("#questions");

    documentReady(() => {
      $("#product_tabs").on("click", "#questions-label", () => {
        $.ajax("/product-question/", {
          data: {
            productId: questionsContainer.data("productid"),
          },
          success: (html) => {
            if (html) {
              questionsContainer.html(html);
              let formConstructedEvent = new CustomEvent("form.constructed", {
                detail: {},
              });
              document.dispatchEvent(formConstructedEvent);
            }
          },
        });
      });

      $("#questions").on("submit", "form", (event) => {
        event.preventDefault();

        $.ajax("/product-question/", {
          method: "POST",
          data: $(event.target).serialize(),
          success: (html) => {
            if (html) {
              questionsContainer.html(html);
              let messageInfo = questionsContainer.find("form").get(0).dataset;

              if (!("messageText" in messageInfo)) {
                return;
              }
              let text = questionsContainer
                .find("." + messageInfo["messageText"])
                .html();
              window.addFlashMessage(text, messageInfo["messageType"], true);
            }
          },
        });
      });
    });

    // group product
    const elem = document.getElementsByClassName("groupped-products")[0];

    if (!elem) {
      return;
    }

    const sortingOptions = JSON.parse(elem.dataset.sortingOptions);
    const hideSort = !!elem.dataset.hideSort;
    const pager = JSON.parse(elem.dataset.pager);

    render(
        <Catalog
          sortingOptions={sortingOptions}
          sortKey={elem.dataset.currentSortingKey}
          hideSort={hideSort}
          pager={pager}
          catalogUrl={"/api" + elem.dataset.catalogUrl}
          checkoutUrl={elem.dataset.checkoutUrl}
          mode={elem.dataset.mode}
          searchText=''
        />,
      elem
    );
  }
})();
