import React from "react";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";

interface OrdersListProps {
  label: string;
  type: string;
}

export const OrdersList: React.FC<OrdersListProps> = ({ label, type }) => {
  return (
    <div>
      <div className="orders-list-header">
        <div className={"page-label"}>{label}</div>
        <div className={"d-flex align-items-center"}>
          <div>Time period:</div>
          <FormSelect
            classes={{
              group: "orders-list-header-select-group",
              selectHeader: "orders-list-header-select-header",
            }}
            value={{
              value: 1,
              viewValue: "Last 7 days",
            }}
            items={[
              {
                value: 1,
                viewValue: "Last 7 days",
              },
            ]}
            id="orders-select"
          />
        </div>
      </div>
      <div className="order-item-container">
        <div className="order-item-header-container">
          <div className="order-item-body-left-side header-left">
            <div>
              Order # <b>AR-265437</b>
            </div>
            <button className="form-button form-button__outline order-details-btn">
              order details
            </button>
          </div>
          <div className="order-item-body-right-side header-right">
            <div>
              <div>ORDER DATE</div>
              <div> April 27, 2021</div>
            </div>
            <div>
              <div className="order-item-header-grand-total">GRAND TOTAL</div>
              <div className="order-item-header-grand-total">
                <b>US$ 17.60</b>
              </div>
            </div>
          </div>
        </div>
        <div className="order-item-body-container">
          <div className="order-item-body-left-side">
            <div className="order-item-body-title">items ordered</div>
            <div className={"order-item-body-product-container"}>
              <div className="order-item-body-product-left-part">
                <img
                  className="order-item-body-product-img"
                  src="https://img2.wtftime.ru/store/2020/11/19/EYGJdrlu_amp_big.jpg"
                />
                <div>
                  <a className="order-item-body-product-name">
                    Ecstasy Crafts Architextures Treasures - Wooden Corkscrew
                  </a>
                  <div className="order-item-body-product-sku">
                    {" "}
                    ECS-7G25093
                  </div>
                </div>
              </div>
              <div className="order-item-body-product-right-part-text">x 3</div>
            </div>
            <button className="form-button form-button__outline">
              show more
            </button>
          </div>
          <div className="order-item-body-right-side">
            <div className="order-item-body-title">Shipping address</div>
            <div>
              1370 BRIDGETON HILL RD UPPER BLACK EDDY, PA 18972 United States
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
