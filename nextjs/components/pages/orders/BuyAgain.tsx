import * as React from "react";
import InnerPage from "@modules/account/components/shared/InnerPage";
import Catalog from "@modules/components/catalog/Catalog";

interface IProps {}

const BuyAgain: React.FC<IProps> = function (props: IProps) {
  const catalogProps = {
    catalogUrl: "/api/category/58225/other-crafts/",
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
      <InnerPage header={"Buy Again"}>
        <Catalog {...catalogProps} />
      </InnerPage>
    </div>
  );
};

export default BuyAgain;
