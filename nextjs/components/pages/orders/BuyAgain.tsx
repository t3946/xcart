import * as React from "react";
import InnerPage from "@modules/account/components/shared/InnerPage";
import Catalog from "@modules/components/catalog/Catalog";

const BuyAgain: React.FC<any> = function () {
  const catalogProps = {
    catalogUrl: "/api/category/bought-products",
    checkoutUrl: "/checkout/shipping/",
    hideSort: false,
    searchText: "",
    sortKey: "relevance",
    sortingOptions: {
      "-price": "Price high to low",
      brand: "Brand name",
      new: "New",
      price: "Price low to high",
      relevance: "Relevance",
    },
  };
  return (
    <div>
      <InnerPage header={"Buy Again"} hatClasses={"px-0"} bodyClasses={"px-0"}>
        <Catalog {...catalogProps} />
      </InnerPage>
    </div>
  );
};

export default BuyAgain;
