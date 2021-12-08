import React, { Fragment } from "react";
import { Grid } from "@mui/material";
import { useDispatch, useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
import { unlockOrder } from "@redux/actions/fraudCheckActions";

export const FraudCheckHat: React.FC = () => {
  const dispatch = useDispatch();
  const orderId = useSelector((state: FraudCheckStore) => state.orderId);
  const settings = useSelector((state: FraudCheckStore) => state.data.settings);

  return (
    <Fragment>
      {settings && (
        <Grid
          container
          direction="column"
          alignItems="center"
          justifyContent="center"
        >
          <div className="title-parent-order_fraud">
            <div className="title-fraud">
              Fraud check for&#160;
              <a
                style={{ color: "Blue" }}
                href={`/admin/order.php?orderid=${orderId}`}
              >
                order # {`${settings.prefix}${orderId}`}
              </a>
            </div>
          </div>
          {settings.lock && (
            <div className="locked-order-wrapper">
              <div className="locked-order">
                You locked this order. Nobody can make any changes to it. The
                order will be unlocked at {settings.timeUnlocked}. You can also
              </div>
              <button
                className="button__unlock-order"
                onClick={() => dispatch(unlockOrder(orderId))}
              >
                Unlock it now
              </button>
              {settings.locked_orders && (
                <button
                  className="button__unlock-order"
                  onClick={() => dispatch(unlockOrder(orderId, true))}
                >
                  Unlock all orders locked by me
                </button>
              )}
            </div>
          )}
        </Grid>
      )}
    </Fragment>
  );
};
