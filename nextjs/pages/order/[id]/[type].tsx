import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import { NextPage, NextPageContext } from "next";
import { OrderInfoHeader } from "@modules/account/components/orders/OrderInfoHeader";
import { useEffect } from "react";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import { setOrderView } from "@redux/actions/account-actions/OrdersActions";
import OrdersPage from "@modules/account/pages/OrdersPage";
import { OrderTrackingPage } from "@modules/account/pages/OrderTrackingPage";
interface OrderPage extends NextPage {
  orderId: number;
}
const OrderPage = ({ orderId }: OrderPage) => {
  const dispatch = useDispatch();
  const router = useRouter();
  useEffect(() => {
    dispatch(setOrderView(Number(router.query.id)));
  }, []);
  const getSection = () => {
    switch (router.query.type) {
      case "order-tracking":
        return <OrderTrackingPage />;
    }
  };
  return (
    <PageTwoColumns>
      <OrderInfoHeader orderId={orderId} />
      {getSection()}
    </PageTwoColumns>
  );
};
export default OrderPage;
// export const getServerSideProps = async ({ query, req }: NextPageContext) => {
//   // const instance = getInstance(req);
//   // const order = await instance
//   //   .get(`/api/account/orders/get-one/${query.id}`)
//   //   .then((res) => res.data);
//   // console.log(order);
//   return {
//     props: {
//       orderId: query.id,
//     },
//   };
// };
