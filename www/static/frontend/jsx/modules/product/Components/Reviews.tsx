import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import React from "react";
import Review from "@client/modules/product/Components/Review/Review";
import { useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";

interface PropsInterface {
  productId: number;
}

const Reviews: React.FC<any> = function (props: PropsInterface) {
  const reviews = useSelector((e: AccountStore) => e.productsReviews)[
    props.productId
  ];
  const sortItems = [
    {
      value: 1,
      previewValue: "preview value",
      viewValue: "view value",
    },
    {
      value: 2,
      previewValue: "preview value 2",
      viewValue: "view value 2",
    },
  ];
  const [selectedItem, setSelectedItem] = React.useState(sortItems[0]);

  function reviewsTemplate() {
    const reviewsTemplates = [];

    if (!reviews) {
      return reviewsTemplates;
    }

    for (const review of reviews) {
      reviewsTemplates.push(<Review {...review} />);
    }

    return reviewsTemplates;
  }

  return (
    <>
      <h3
        className={
          "product-reviews-header product-reviews-header_big product-reviews_column-header mb-md-20 d-flex justify-content-between"
        }
      >
        <span>Top reviews from the United States</span>

        <FormSelect
          items={sortItems}
          onClick={(item) => {
            setSelectedItem(item);
          }}
          name={"name-qwe123"}
          value={selectedItem}
          classes={{ group: "w-auto" }}
        />
      </h3>

      {reviewsTemplate()}

      <div className="product-reviews__see-more-reviews">
        <button className={"form-button form-button__outline"}>
          See more reviews
        </button>
      </div>
    </>
  );
};

export default Reviews;
