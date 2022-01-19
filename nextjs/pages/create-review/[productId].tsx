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

  await instance
    .post("/api-client/product/get", { productId })
    .then((res: AxiosResponse) => {
      product = res.data;
    });

  return {
    props: {
      product,
    },
  };
}

const CreateReviewPage = (props: any) => {
  const { product } = props;

  return (
    <PageTwoColumns>
      {product && <ReviewForm product={product} />}
    </PageTwoColumns>
  );
};

export default CreateReviewPage;
