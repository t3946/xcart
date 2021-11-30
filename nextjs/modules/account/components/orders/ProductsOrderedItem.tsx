import React from "react";
import { useSelector } from "react-redux";
import { AccountStore } from "@modules/account/ts/types/store.type";

interface ProductsOrderedItemProps {
  orderItem: any;
}

export const ProductsOrderedItem: React.FC<ProductsOrderedItemProps> = ({
  orderItem,
}) => {
  const breakpoints = useSelector(
    (store: AccountStore) => store.main.breakpoint
  );

  return (
    <div>
      {breakpoints && (
        <>
          <div className="products-order-item-title">
            The items below are shipped from Ogdensburg, NY, US
          </div>
          <div className="products-order-item-header">
            <div className={"products-order-item-header-sku"}>
              Item name / SKU
            </div>
            {breakpoints.md && (
              <>
                <div className={"products-order-item-header-price"}>
                  Price {breakpoints.sm && !breakpoints.lg && `x Qty`}
                </div>
                {breakpoints.lg && (
                  <div className={"products-order-item-header-qty"}>
                    Qty ordered
                  </div>
                )}
              </>
            )}

            <div className={"products-order-item-header-extended"}>
              Extended
            </div>
          </div>
          <div>
            {orderItem.orderGroupsItems.map((e) => {
              return (
                <div className="products-order-item">
                  <div className="order-item-body-product-left-part products-order-item-header-sku">
                    {breakpoints.md && (
                      <img
                        className="order-item-body-product-img"
                        src={e.image}
                      />
                    )}

                    <div className="table-content-mobile">
                      <a className="order-item-body-product-name">
                        {e.product}
                      </a>
                      <div className="order-item-body-product-sku">
                        {e.productcode}
                      </div>
                      {!breakpoints.md && (
                        <div className={"product-ordered-extended-mobile"}>
                          <div className="products-order-item-header-price">
                            US$ {e.price}
                          </div>
                          <div className="products-order-item-header-price">
                            х {e.amount}
                          </div>
                          <div className="products-order-item-header-price">
                            US$ {e.price}
                          </div>
                        </div>
                      )}
                    </div>
                  </div>
                  {breakpoints.md && (
                    <>
                      <div className="products-order-item-header-price">
                        US$ {e.price}
                        {breakpoints.sm && !breakpoints.lg && `x ${e.amount}`}
                      </div>
                      {breakpoints.lg && (
                        <div className="products-order-item-header-qty">
                          {e.amount}
                        </div>
                      )}
                      <div className="products-order-item-header-extended">
                        US$ {e.price}
                      </div>
                    </>
                  )}
                </div>
              );
            })}
          </div>

          <div className="products-order-item-transaction-total products-ordered-total">
            <div className="transaction-total-container product-ordered-total-container">
              <div className="total-left-side product-ordered-total-left">
                <div className="info-item-container">
                  <p className="label-info-item right-part">Payment status:</p>
                  <p className="left-part">{orderItem.a2b_status}</p>
                </div>
                <div className="info-item-container">
                  <p className="label-info-item right-part">Shipping status:</p>
                  <p className="left-part">{orderItem.a2c_status}</p>
                </div>
              </div>
              <div className="total-group-right-side product-ordered-total-right">
                <div className="info-item-container info-item-container-spacing product-ordered-total-container-spacing regular">
                  <p className=""> Regular shipping:</p>
                  <p className="">US$ {orderItem.shipping_gross}</p>
                </div>
                <div className="info-item-container info-item-container-spacing product-ordered-total-container-spacing tax">
                  <div className="">Sales Tax:</div>
                  <div className="">US$ {orderItem.total_pst}</div>
                </div>
                <div className="info-item-container info-item-container-spacing product-ordered-total-container-spacing tax">
                  <p className="">VAT Tax: </p>
                  <p className="">US$ {orderItem.total_tax}</p>
                </div>
                <div className="info-item-container info-item-container-spacing product-ordered-total-container-spacing subtotal">
                  <p className="">Subtotal:</p>
                  <p className="">US$ {orderItem.total_gross}</p>
                </div>
              </div>
            </div>
          </div>
        </>
      )}
    </div>
  );
};
