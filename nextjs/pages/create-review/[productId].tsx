import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import * as React from "react";
import ReviewForm from "@modules/account/components/review/ReviewForm";
import { getInstance } from "@services/axios/Instance";
import { NextPageContext } from "next";
import { AxiosResponse } from "axios";

export async function getServerSideProps(ctx: NextPageContext) {
  if (!ctx.req) {
    return {};
  }

  if (!process.initialState.user) {
    return { props: {} };
  }

  const instance = getInstance(ctx.req);
  const productId = ctx.query.productId;
  let product;
  let isUserCanWrite = true;

  await instance
    .post("/api-client/product/get", { productId })
    .then((res: AxiosResponse) => {
      product = res.data;
    });

  await instance
    .post("/api-client/review/get-current-user-comment", { productId })
    .then((res: AxiosResponse) => {
      if (res.data.review) {
        isUserCanWrite = false;
      }
    });

  return {
    props: {
      product,
      isUserCanWrite,
    },
  };
}

const CreateReviewPage = (props: any) => {
  const { product, isUserCanWrite } = props;

  // if (!isUserCanWrite) {
  //   return (
  //     <PageTwoColumns>
  //       <p>
  //         You already write review to this product. Every user can write only
  //         one review.
  //       </p>
  //     </PageTwoColumns>
  //   );
  // }

  return (
    <PageTwoColumns>
      {product && <ReviewForm product={product} />}
    </PageTwoColumns>
  );
};

export default CreateReviewPage;
