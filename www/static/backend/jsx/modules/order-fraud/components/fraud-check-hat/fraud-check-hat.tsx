import React, { useContext } from "react";
import { Grid } from "@material-ui/core";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";

const api = new ApiService();
export const FraudCheckHat: React.FC = () => {
  const { orderId, settings, setSettings } = useContext(FraudCheckOrderContext);
  const unlockItNow = () => {
    api.get(`/api/order/fraud-check/unlock/${orderId}`).then((res) => {
      if (res.status) {
        setSettings((prev) => {
          delete prev.lock;
          return { ...prev };
        });
      }
    });
  };

  return (
    <Grid
      container
      direction="column"
      alignItems="center"
      justifyContent="center"
    >
      <div className="title-parent-order_fraud">
        <div className="title-fraud">
          Fraud check for <a href="#">order # SG-270448</a>
        </div>
      </div>
      {settings.lock?.status && (
        <div className="locked-order-wrapper">
          <div className="locked-order">
            You locked this order. Nobody can make any changes to it. The order
            will be unlocked at {settings?.lock.timeUnlocked}. You can also
          </div>
          <button className="button__unlock-order" onClick={unlockItNow}>
            Unlock it now
          </button>
        </div>
      )}
    </Grid>
  );
};
